<?php

namespace App\Livewire\Klinik\Paketperawatan;

use App\Class\BarangClass;
use App\Models\BarangSatuan;
use App\Models\PaketPerawatan;
use App\Models\PasienPaketPrabayar;
use App\Models\Registrasi;
use App\Models\RegistrasiPaketPerawatan;
use App\Models\Stok;
use App\Models\TarifTindakan;
use App\Models\TarifTindakanAlatBarang;
use App\Models\Tindakan;
use App\Models\TindakanAlatBarang;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    use CustomValidationTrait;

    public $dataPaketPerawatan = [];
    public $data;
    public $registrasi_paket_perawatan = [], $pasien_paket_prabayar = [];
    public $bahan = [];
    public $dataBarang = [];
    public $dataPasienPaketPrabayar = [];

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        if ($this->data->pembayaran) {
            return abort(404);
        }
        $this->dataPasienPaketPrabayar = PasienPaketPrabayar::where('pasien_id', $data->pasien_id)
            ->whereRaw('qty > (
		select count(*)
	FROM
		registrasi_paket_perawatan 
	WHERE
		pasien_paket_prabayar.id = registrasi_paket_perawatan.pasien_paket_prabayar_id 
	AND pembayaran_id IS NULL 
	)')
            ->with('paketPerawatan.paketPerawatanDetail.tarifTindakan')->get()->map(
                function ($q) {
                    return [
                        'id' => $q->id,
                        'paket_perawatan_id' => $q->paket_perawatan_id,
                        'nama' => $q->paketPerawatan->nama,
                        'uraian' => $q->paketPerawatan->uraian,
                        'kode_akun_id' => $q->kode_akun_paket_perawatan_id,
                        'tarif' => $q->total / $q->qty,
                        'tarif_tindakan_id' => $q->paketPerawatan->paketPerawatanDetail->first()->tarif_tindakan_id,
                        'tarif_tindakan_nama' => $q->paketPerawatan->paketPerawatanDetail->first()->tarifTindakan->nama,
                        'qty' => $q->qty,
                        'qty_terpakai' => $q->qty_terpakai
                    ];
                }
            )->toArray();
        $this->dataPaketPerawatan = PaketPerawatan::with('paketPerawatanDetail.tarifTindakan')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama,
            'uraian' => $q->uraian,
            'tarif' => $q->tarif,
            'jenis' => $q->jenis,
            'detail' => $q->paketPerawatanDetail->map(fn($r) => [
                'paket_perawatan_id' => $r->paket_perawatan_id,
                'id' => $r->id,
                'tarif_tindakan_id' => $r->tarifTindakan->id,
                'nama' => $r->tarifTindakan->nama,
                'qty' => $r->qty,
                'tarif' => $r->tarifTindakan->tarif,
            ])->toArray(),
        ])->toArray();
        if ($data->registrasiPaketPerawatan->count() > 0) {
            $this->registrasi_paket_perawatan = $data->registrasiPaketPerawatan->map(fn($q) => [
                'id' => $q->paket_perawatan_id,
                'biaya' => $q->biaya,
                'catatan' => $q->catatan,
                'jenis' => $q->pasien_paket_prabayar_id ? "Prabayar" : "Bundling",
                'kode_akun_id' => $q->kode_akun_id,
                'pasien_paket_prabayar_id' => $q->pasien_paket_prabayar_id,
            ])->toArray();
        } 
    }

    public function submit()
    {
        if ($this->data->pembayaran) {
            return abort(404);
        }
        $this->dataBarang = collect(BarangClass::getBarang());
        $paketPerawatanDetail = collect($this->dataPaketPerawatan)->whereIn('id', collect($this->registrasi_paket_perawatan)->pluck('id')->toArray())->pluck('detail')->collapse()->toArray();

        $this->bahan = TindakanAlatBarang::whereNotNull('barang_satuan_id')->whereIn('tindakan_id', collect($paketPerawatanDetail)->pluck('tarif_tindakan_id'))->get()->map(function ($q) {
            $barang = collect($this->dataBarang)->firstWhere('id', $q->barang_satuan_id);
            return [
                'barang_id' => $barang['barang_id'],
                'nama' => $barang['nama'],
                'satuan' => $barang['satuan'],
                'kode_akun_id' => $barang['kode_akun_id'],
                'kode_akun_penjualan_id' => $barang['kode_akun_penjualan_id'],
                'kode_akun_modal_id' => $barang['kode_akun_modal_id'],
                'qty' => $q->qty,
                'biaya' => $q->biaya,
                'barang_satuan_id' => $q->barang_satuan_id,
                'rasio_dari_terkecil' => $q->rasio_dari_terkecil,
            ];
        })->toArray();
        $this->validateWithCustomMessages([
            'registrasi_paket_perawatan' => 'required|array',
            'registrasi_paket_perawatan.*.id' => 'required|distinct',
            'bahan.*.qty' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $bahan = $this->bahan[$index] ?? null;
                    if (!$bahan) return;

                    $stokTersedia = Stok::where('barang_id', $bahan['barang_id'])
                        ->available()
                        ->count();
                    if (($value * ($bahan['rasio_dari_terkecil'] ?? 1)) > $stokTersedia) {
                        $stokAvailable = $stokTersedia / $bahan['rasio_dari_terkecil'];
                        $fail("Stok bahan {$bahan['nama']} tidak mencukupi. Tersisa {$stokAvailable} {$bahan['satuan']}. Yang dibutuhkan untuk tindakan  {$value} {$bahan['satuan']}.");
                    }
                }
            ],
        ]);
        DB::transaction(function () use ($paketPerawatanDetail) {
            RegistrasiPaketPerawatan::where('registrasi_id', $this->data->id)->delete();

            $tindakanAlatBarang = [];
            $dataTarifTindakanAlatBarang = TarifTindakanAlatBarang::whereIn('tarif_tindakan_id', collect($paketPerawatanDetail)->pluck('id'))->get();
            $dataBarangSatuan = BarangSatuan::whereIn('id', collect($dataTarifTindakanAlatBarang)->pluck('barang_satuan_id'))->get();
            $dataTarifTindakan = TarifTindakan::with('tarifTindakanAlatBarang.barangSatuan')->orderBy('nama')->get()->map(fn($q) => [
                'id' => $q->id,
                'nama' => $q->nama,
                'biaya_jasa_dokter' => $q->biaya_jasa_dokter,
                'biaya_jasa_perawat' => $q->biaya_jasa_perawat,
                'biaya_alat_barang' => $q->biaya_alat_barang,
                'tarif' => $q->tarif
            ])->toArray();

            foreach (collect($this->registrasi_paket_perawatan) as $q) {
                $registrasiPaketPerawatan = new RegistrasiPaketPerawatan();
                $registrasiPaketPerawatan->registrasi_id = $this->data->id;
                $registrasiPaketPerawatan->paket_perawatan_id = $q['id'];
                $registrasiPaketPerawatan->pasien_id = $this->data->pasien_id;
                $registrasiPaketPerawatan->biaya = $q['biaya'];
                $registrasiPaketPerawatan->jenis = $q['jenis'];
                $registrasiPaketPerawatan->qty = 1;
                $registrasiPaketPerawatan->catatan = $q['catatan'];
                $registrasiPaketPerawatan->pasien_paket_prabayar_id = $q['pasien_paket_prabayar_id'];
                $registrasiPaketPerawatan->pengguna_id = auth()->id();
                $registrasiPaketPerawatan->save();

                $paketPerawatanDetailTindakan = collect($paketPerawatanDetail)->where('paket_perawatan_id', $q['id']);
                foreach ($paketPerawatanDetailTindakan as $r) {
                    $tarifTindakan = collect($dataTarifTindakan)->where('id', $r['tarif_tindakan_id'])->first();
                    $tindakan = new Tindakan();
                    $tindakan->registrasi_id = $this->data->id;
                    $tindakan->tarif_tindakan_id = $r['tarif_tindakan_id'];
                    $tindakan->pasien_id = $this->data->pasien_id;
                    $tindakan->biaya = $r['tarif'];
                    $tindakan->biaya_jasa_dokter = $tarifTindakan['biaya_jasa_dokter'];
                    $tindakan->biaya_jasa_perawat = $tarifTindakan['biaya_jasa_perawat'];
                    $tindakan->biaya_alat_barang = $tarifTindakan['biaya_alat_barang'];
                    if ($q['pasien_paket_prabayar_id']) {
                        $tindakan->qty = 1;
                    } else {
                        $tindakan->qty = $r['qty'];
                    }

                    $tindakan->registrasi_paket_perawatan_id = $registrasiPaketPerawatan->id;
                    $tindakan->paket_perawatan_id = $q['id'];
                    $tindakan->pengguna_id = auth()->id();
                    $tindakan->save();

                    $tarifTindakanAlatBarang = $dataTarifTindakanAlatBarang->where('tarif_tindakan_id', $r['tarif_tindakan_id']);

                    foreach ($tarifTindakanAlatBarang as $s) {
                        $barangSatuan = $s->aset_id ? null : $dataBarangSatuan->firstWhere('id', $s->barang_satuan_id);

                        $tindakanAlatBarang[] = [
                            'tindakan_id' => $tindakan->id,
                            'aset_id' => $s->aset_id,
                            'qty' => $r['qty'] * $s->qty,
                            'biaya' => $r['qty'] * $s->biaya,
                            'barang_satuan_id' => $s->barang_satuan_id,
                            'barang_id' => $barangSatuan ? $barangSatuan['barang_id'] : null,
                            'rasio_dari_terkecil' => $barangSatuan ? $barangSatuan['rasio_dari_terkecil'] : null,
                            'tarif_tindakan_id' => $r['tarif_tindakan_id'],
                        ];
                    }
                }
            }
            TindakanAlatBarang::insert($tindakanAlatBarang);
            session()->flash('success', 'Berhasil menyimpan data');
        });
    }

    public function render()
    {
        return view('livewire.klinik.paketperawatan.form');
    }
}
