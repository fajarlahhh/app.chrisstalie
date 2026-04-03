<?php

namespace App\Livewire\Laporan\Barangdagang\Persediaan;

use App\Models\Stok;
use App\Models\Barang;
use Livewire\Component;
use App\Models\KodeAkun;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    #[Url]
    public $cari, $persediaan, $kode_akun_id, $bulan;
    public $dataKodeAkun = [], $dataStok;

    public function mount()
    {
        $this->dataKodeAkun = KodeAkun::detail()->where('parent_id', '11300')->get()->toArray();
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    private function getData()
    {
        $this->dataStok = Stok::select(DB::raw('barang_id, tanggal_kedaluarsa, harga_beli, count(*) as stok'))
            ->where('tanggal_masuk', '<', \Carbon\Carbon::parse($this->bulan . '-01')->addMonth()->format('Y-m-01'))
            ->where(fn($q) => $q->whereNull('tanggal_keluar')->orWhereNull('stok_keluar_id')->orWhere('tanggal_keluar', '>=', \Carbon\Carbon::parse($this->bulan . '-01')->addMonth()->format('Y-m-01')))
            ->groupBy('barang_id', 'tanggal_kedaluarsa', 'harga_beli')->get();
            
        return Barang::with(['barangSatuanUtama.satuanKonversi', 'barangSatuan', 'kodeAkun'])
            ->when($this->persediaan, fn($q) => $q->where('persediaan', $this->persediaan))
            ->when($this->kode_akun_id, function ($q) {
                $q->where('kode_akun_id', $this->kode_akun_id);
            })
            ->where(fn($q) => $q
                ->where('nama', 'like', '%' . $this->cari . '%'))
            ->orderBy('nama')
            ->get();
    }

    public function print()
    {
        $this->getData();
        $cetak = view('livewire.laporan.barangdagang.persediaan.cetak', [
            'cetak' => true,
            'data' => $this->getData(),
            'dataStok' => $this->dataStok,
            'persediaan' => $this->persediaan,
            'kode_akun' => $this->kode_akun_id ? collect($this->dataKodeAkun)->where('id', $this->kode_akun_id)->first()?->nama : '',
            'cari' => $this->cari,
        ])->render();
        session()->flash('cetak', $cetak);
    }


    public function render()
    {
        return view('livewire.laporan.barangdagang.persediaan.index', [
            'data' => $this->getData(),
        ]);
    }
}
