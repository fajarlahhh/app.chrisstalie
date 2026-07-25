<?php

namespace App\Livewire\Klinik\Diskonkhusus;

use App\Models\Nakes;
use App\Models\Registrasi;
use App\Models\Diskon;
use App\Models\Tindakan;
use Illuminate\Support\Facades\DB;
use App\Models\TarifTindakan;
use App\Class\BarangClass;
use Livewire\Component;

class Form extends Component
{
    public $dataTindakan = [], $tindakan = [];
    public $paketPerawatan = [];
    public $data;
    public $dataBarang, $resep = [];
    public $ulangTahun = false;

    public function mount(Registrasi $data)
    {
        $this->data = $data->load('tindakan', 'tindakan.tarifTindakan', 'resepObat');

        if ($this->data->pembayaran) {
            return abort(404);
        }
        $dataNakes = Nakes::withTrashed()->get();


        if ($this->data->pasien->tanggal_lahir) {
            $tglLahir = \Carbon\Carbon::parse($this->data->pasien->tanggal_lahir)->startOfDay();
            $today = now()->startOfDay();

            $b1 = $tglLahir->copy()->year($today->year);
            $b2 = $tglLahir->copy()->year($today->year - 1);
            $b3 = $tglLahir->copy()->year($today->year + 1);

            if (
                abs($today->diffInDays($b1, false)) <= 5 ||
                abs($today->diffInDays($b2, false)) <= 5 ||
                abs($today->diffInDays($b3, false)) <= 5
            ) {
                $this->ulangTahun = true;
            }
        }
        $this->dataBarang = BarangClass::getBarang('Apotek');
        $diskon = Diskon::whereNotNull('tarif_tindakan_id')->where('tanggal_mulai', '<=', date('Y-m-d'))->where('tanggal_berakhir', '>=', date('Y-m-d'))->get();
        $this->dataTindakan = TarifTindakan::with('tarifTindakanAlatBarang.barangSatuan')->orderBy('nama')->get()->map(fn($q) => [
            'id' => $q->id,
            'nama' => $q->nama,
            'biaya_jasa_dokter' => $q->biaya_jasa_dokter,
            'biaya_jasa_perawat' => $q->biaya_jasa_perawat,
            'biaya_alat_barang' => $q->biaya_alat_barang,
            'tarif' => $q->tarif,
            'promo_ultah' => $this->ulangTahun ? $q->promo_ultah : 0,
            'promo_tindakan' => $diskon->where('tarif_tindakan_id', $q->id)->first() ? $diskon->where('tarif_tindakan_id', $q->id)->first() : 0
        ])->toArray();
        $this->tindakan = $data->tindakan
            ->whereNull('paket_perawatan_id')
            ->map(
                fn($q) => [
                    'id' => $q->id,
                    'nama' => $q->tarifTindakan?->nama,
                    'paket_perawatan_id' => $q->paket_perawatan_id,
                    'qty' => (float) $q->qty,
                    'harga_jual' => (float) $q->tarifTindakan->tarif,
                    'diskon' =>  (int)$q->diskon > 0 && ((float)$q->tarifTindakan->tarif * (float)$q->qty) > 0 ? (int) round(($q->diskon / ((float)$q->tarifTindakan->tarif * (float)$q->qty)) * 100) : 0,
                    'catatan' => $q->catatan,
                    'membutuhkan_inform_consent' => $q->membutuhkan_inform_consent == 1 ? true : false,
                    'membutuhkan_sitemarking' => $q->membutuhkan_sitemarking == 1 ? true : false,
                    'dokter' => $dataNakes->where('id', $q->dokter_id)->first()?->nama,
                    'perawat' => $dataNakes->where('id', $q->perawat_id)->first()?->nama,
                    'biaya_jasa_dokter' => (float) $q->biaya_jasa_dokter,
                    'biaya_jasa_perawat' => (float) $q->biaya_jasa_perawat,
                    'biaya_alat_barang' => (float) $q->biaya_alat_barang,
                    'kunjungan_paket_perawatan_id' => $q->kunjungan_paket_perawatan_id,
                    'promo_ultah' => $this->ulangTahun ? collect($this->dataTindakan)->where('id', $q->tarif_tindakan_id)->first()['promo_ultah'] : 0,
                    'promo_tindakan' => $diskon->where('tarif_tindakan_id', $q->tarif_tindakan_id)->first() ? $diskon->where('tarif_tindakan_id', $q->tarif_tindakan_id)->first() : 0,
                ],
            )
            ->values()
            ->toArray();
        $this->resep = collect($data->resepObat)
            ->groupBy('resep')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'resep' => $first->resep,
                    'catatan' => $first->catatan,
                    'nama' => $first->nama,
                    'barang' =>  $group->map(function ($r) {
                        $barang = collect($this->dataBarang)->firstWhere('id', $r->barang_satuan_id);
                        if (!$barang) {
                            return [
                                'id' => null,
                                'nama' => 'Terjadi Kesalahan Resep Obat',
                                'satuan' => null,
                                'kode_akun_id' => null,
                                'kode_akun_penjualan_id' => null,
                                'kode_akun_modal_id' => null,
                                'harga' => null,
                                'qty' => null,
                                'subtotal' => null,
                                'rasio_dari_terkecil' => null,
                            ];
                        }
                        return [
                            'id' => $r->id,
                            'nama' => $barang['nama'],
                            'satuan' => $barang['satuan'],
                            'diskon' => ($r->harga * $r->qty) > 0 ? (int) round(($r->diskon / ($r->harga * $r->qty)) * 100) : 0,
                            'kode_akun_id' => $barang['kode_akun_id'],
                            'kode_akun_penjualan_id' => $barang['kode_akun_penjualan_id'],
                            'kode_akun_modal_id' => $barang['kode_akun_modal_id'],
                            'harga' => $r->harga,
                            'qty' => $r->qty,
                            'subtotal' => $r->harga * $r->qty,
                            'rasio_dari_terkecil' => $barang['rasio_dari_terkecil'],
                        ];
                    })->toArray(),
                ];
            })
            ->values()->toArray();
    }

    public function submit()
    {
        if ($this->data->pembayaran) {
            return abort(404);
        }

        $this->validate([
            'tindakan.*.diskon' => 'required|numeric|between:0,100',
            'resep.*.barang.*.diskon' => 'required|numeric|between:0,100',
        ]);

        DB::transaction(function () {
            foreach ($this->tindakan as $t) {
                Tindakan::where('id', $t['id'])->update([
                    'diskon' => ($t['harga_jual'] * $t['qty']) * $t['diskon'] / 100,
                ]);
            }
            foreach ($this->resep as $group) {
                foreach ($group['barang'] as $b) {
                    if ($b['id']) {
                        \App\Models\ResepObat::where('id', $b['id'])->update([
                            'diskon' => ($b['harga'] * $b['qty']) * $b['diskon'] / 100,
                        ]);
                    }
                }
            }

            session()->flash('success', 'Berhasil menyimpan data');
        });

        return $this->redirect('/klinik/diskonkhusus');
    }
    public function render()
    {
        return view('livewire.klinik.diskonkhusus.form');
    }
}
