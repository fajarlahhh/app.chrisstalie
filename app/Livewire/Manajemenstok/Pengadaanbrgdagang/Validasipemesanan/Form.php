<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Validasipemesanan;

use App\Models\KepegawaianPegawai;
use App\Models\PengadaanPemesanan;
use App\Models\Pengguna;
use App\Models\Supplier;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    use CustomValidationTrait;
    public $dataBarang = [], $dataPengguna = [], $barang = [], $deskripsi, $data, $verifikator_id, $status = 'Ditolak', $catatan, $dataSupplier = [], $supplier_id, $barangSudahDipesan = [], $tanggal, $tanggal_estimasi_kedatangan, $penanggung_jawab_id;

    public function submit()
    {
        // Validasi: jumlah total qty dari semua barang harus lebih dari 0
        $totalQty = array_sum(array_map(function ($b) {
            return isset($b['qty']) ? (float)$b['qty'] : 0;
        }, $this->barang));
        if ($totalQty <= 0) {
            $this->addError('barang', 'Total jumlah barang yang dipesan harus lebih dari 0.');
            return;
        }
        $this->validateWithCustomMessages([
            'tanggal_estimasi_kedatangan' => 'required|date',
            'supplier_id' => 'required|integer|exists:supplier,id',
            'penanggung_jawab_id' => 'required|integer|exists:pengguna,id',
            'barang' => 'required|array',
            'barang.*.id' => 'required|integer',
            'barang.*.qty' => [
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $matches = [];
                    if (preg_match('/^barang\.(\d+)\.qty$/', $attribute, $matches)) {
                        $index = (int)$matches[1];
                        if (isset($this->barang[$index]['qty_disetujui']) && $value > $this->barang[$index]['qty_disetujui']) {
                            $fail('Max ' . ($this->barang[$index]['qty_disetujui'] - $this->barang[$index]['qty_sudah_dipesan']) . ' ' . ($this->barang[$index]['satuan'] ?? ''));
                        }
                    }
                }
            ],
        ]);

        DB::transaction(function () {
            $this->data->supplier_id = $this->supplier_id;
            $this->data->supplier_lama_id = $this->data->supplier_id;
            $this->data->validator_id = auth()->id();
            $this->data->validated_at = now();
            $this->data->save();

            foreach ($this->barang as $key => $value) {
                $this->data->pengadaanPemesananDetail()->where('id', $value['pengadaan_pemesanan_detail_id'])->update([
                    'qty' => $value['qty'],
                    'harga_beli' => $value['harga_beli'],
                    'qty_lama' => $value['qty_lama'],
                    'harga_beli_lama' => $value['harga_beli_lama'],
                    'harga_beli_terkecil' => $value['harga_beli'] / $value['rasio_dari_terkecil'],
                ]);
            }

            $cetak = view('livewire.manajemenstok.pengadaanbrgdagang.pemesanan.cetak', [
                'data' => $this->data,
                'apoteker' => KepegawaianPegawai::where('apoteker', 1)->first(),
            ])->render();
            session()->flash('cetak', $cetak);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/manajemenstok/pengadaanbrgdagang/validasipemesanan');
    }

    public function mount(PengadaanPemesanan $data)
    {
        $this->data = $data;
        if ($this->data->validator_id != null) {
            return abort(404);
        }
        $this->fill($this->data->toArray());
        $this->barang = $data->pengadaanPemesananDetail->map(fn($q) => [
            'pengadaan_pemesanan_detail_id' => $q->id,
            'id' => $q->barang_satuan_id,
            'barang_id' => $q->barang_id,
            'nama' => $q->barangSatuan->barang->nama,
            'satuan' => $q->barangSatuan->nama,
            'rasio_dari_terkecil' => $q->rasio_dari_terkecil,
            'qty' => $q->qty,
            'harga_beli' => (float)$q->harga_beli,
            'qty_lama' => $q->qty,
            'harga_beli_lama' => (float)$q->harga_beli,
        ])->toArray();
        $this->dataSupplier = Supplier::whereNotNull('konsinyator')->orderBy('nama')->get()->toArray();
        $this->dataPengguna = Pengguna::role(['supervisor', 'administrator'])->with('kepegawaianPegawai')->whereNotNull('kepegawaian_pegawai_id')->orderBy('nama')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.validasipemesanan.form');
    }
}
