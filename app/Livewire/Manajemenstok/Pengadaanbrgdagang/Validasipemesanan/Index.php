<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Validasipemesanan;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\PengadaanPemesanan;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Belum Validasi', $bulan;

    public function updated()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
        $this->status = $this->status ?: 'Belum Validasi';
    }

    public function print($id)
    {
        $data = PengadaanPemesanan::findOrFail($id);
        $cetak = view('livewire.manajemenstok.pengadaanbrgdagang.pemesanan.cetak', [
            'data' => $data,
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $data = PengadaanPemesanan::findOrFail($id);
            $data->update(['validator_id' => null, 'validated_at' => null, 'supplier_id' => $data->supplier_lama_id, 'supplier_lama_id' => null]);
            $barang = $data->pengadaanPemesananDetail->map(fn($q) => [
                'pengadaan_pemesanan_detail_id' => $q->id,
                'qty_lama' => $q->qty_lama,
                'harga_beli_lama' => (float)$q->harga_beli_lama,
            ])->toArray();

            foreach ($barang as $key => $value) {
                $this->data->pengadaanPemesananDetail()->where('id', $value['pengadaan_pemesanan_detail_id'])->update([
                    'qty' => $value['qty_lama'],
                    'harga_beli' => $value['harga_beli_lama'],
                    'qty_lama' => null,
                    'harga_beli_lama' => null,
                    'harga_beli_terkecil' => $value['qty_lama'] / $value['harga_beli_lama'],
                ]);
            }
            session()->flash('success', 'Berhasil menghapus data');
        });
    }
    private function getData()
    {
        $data = PengadaanPemesanan::with([
            'supplier',
            'penanggungJawab',
            'pengguna',
            'pengadaanPemesananDetail.barangSatuan.barang',
            'pengadaanPemesananDetail.barangSatuan.satuanKonversi',
            'pengadaanPermintaan',
            'pengadaanPemesananVerifikasi.pengguna',
            'stokMasuk',
        ])
            ->when($this->status == 'Belum Validasi', function ($q) {
                $q->whereNull('validator_id');
            })
            ->when($this->status == 'Sudah Validasi', function ($q) {
                $q->where(fn($r) => $r->whereNotNull('validator_id')->where('validated_at', 'like', $this->bulan . '%'));
            })
            ->where(fn($z) => $z->whereHas('pengadaanPermintaan', function ($q) {
                $q->where(
                    fn($q) => $q
                        ->where('deskripsi', 'like', '%' . $this->cari . '%')
                        ->when(auth()->user()->hasRole('operator|guest'), fn($q) => $q->whereIn('jenis_barang', ['Persediaan Apotek', 'Alat Dan Bahan']))
                );
            })->orWhereHas('supplier', function ($q) {
                $q->where('nama', 'like', '%' . $this->cari . '%');
            })
                ->orWhere('catatan', 'like', '%' . $this->cari . '%')
                ->orWhere('nomor', 'like', '%' . $this->cari . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $data;
    }

    public function render()
    {
        // $permintaan = DB::table('pengadaan_permintaan')
        //     ->whereNotIn('id', function ($query) {
        //         $query->select('pengadaan_permintaan_id')
        //             ->from('pengadaan_pemesanan');
        //     })
        //     ->whereIn('id', function ($query) {
        //         $query->select('pengadaan_permintaan_id')
        //             ->from('stok_masuk');
        //     })
        //     ->get();
        // dd($permintaan);
        return view('livewire.manajemenstok.pengadaanbrgdagang.validasipemesanan.index', [
            'data' => $this->getData()
        ]);
    }
}
