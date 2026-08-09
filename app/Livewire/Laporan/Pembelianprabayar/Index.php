<?php

namespace App\Livewire\Laporan\Pembelianprabayar;

use Livewire\Component;
use App\Models\Pengguna;
use App\Models\PasienPaketPrabayar;
use App\Models\MetodeBayar;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $tanggal1, $tanggal2, $pengguna_id, $metode_bayar;

    public $dataMetodeBayar = [];

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-d');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
        $this->dataMetodeBayar = MetodeBayar::get()->toArray();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.pembelianprabayar.cetak', [
            'cetak'       => true,
            'tanggal1'    => $this->tanggal1,
            'tanggal2'    => $this->tanggal2,
            'pengguna'    => $this->pengguna_id
                ? Pengguna::find($this->pengguna_id)?->kepegawaianPegawai?->nama
                    ?? Pengguna::find($this->pengguna_id)?->nama
                : 'Semua Pengguna',
            'metodeBayar' => $this->metode_bayar ?: 'Semua',
            'data'        => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    private function getData()
    {
        return PasienPaketPrabayar::with(['pasien', 'paketPerawatan', 'pengguna'])
            ->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
            ->when($this->pengguna_id, fn($q) => $q->where('pengguna_id', $this->pengguna_id))
            ->when($this->metode_bayar, fn($q) => $q->where('metode_bayar', $this->metode_bayar))
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    public function render()
    {
        $data = $this->getData();

        return view('livewire.laporan.pembelianprabayar.index', [
            'data'          => $data,
            'dataMetodeBayar' => $this->dataMetodeBayar,
            'dataPengguna'  => auth()->user()->hasRole(['administrator', 'supervisor'])
                ? Pengguna::whereIn('id', $data->pluck('pengguna_id')->unique()->toArray())->get()
                : Pengguna::where('id', auth()->id())->get(),
        ]);
    }
}
