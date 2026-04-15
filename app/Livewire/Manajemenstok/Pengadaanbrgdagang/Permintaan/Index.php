<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Permintaan;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\PengadaanPermintaan;
use App\Models\PengadaanVerifikasi;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $status = 'Belum Kirim Verifikasi', $bulan;

    public function updated()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function delete($id)
    {
        PengadaanPermintaan::findOrFail($id)->delete();
        session()->flash('success', 'Berhasil menghapus data');
    }

    private function getData()
    {
        $data = PengadaanPermintaan::with([
            'pengadaanPermintaanDetail.barangSatuan.satuanKonversi',
            'pengadaanPermintaanDetail.barangSatuan.barang',
            'pengadaanPemesanan.stokMasuk',
            'pengadaanPemesanan.pengadaanTagihan',
            'pengadaanPemesananDetail',
            'pengadaanVerifikasi',
            'pengguna'
        ])
            ->where(fn($q) => $q
                ->where('deskripsi', 'like', '%' . $this->cari . '%')
                ->orWhere('nomor', 'like', '%' . $this->cari . '%'))
            ->when(auth()->user()->hasRole('operator|guest'), fn($q) => $q->whereIn('jenis_barang', ['Persediaan Apotek', 'Alat Dan Bahan']))
            ->where('created_at', 'like', $this->bulan . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $data;
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.permintaan.index', [
            'data' => $this->getData()
        ]);
    }
}
