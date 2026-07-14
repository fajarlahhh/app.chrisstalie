<?php

namespace App\Livewire\Laporan\Statistik\Penggunaanalat;

use App\Models\TindakanAlatBarang;
use App\Exports\LaporanstatistikExport;
use Livewire\Component;
use Livewire\Attributes\Url;

class Index extends Component
{

    #[Url]
    public $tanggal1, $tanggal2;

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-01');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
    }


    public function getData()
    {
        return TindakanAlatBarang::with('tindakan.registrasi', 'alat')->whereNotNull('aset_id')->select('tindakan_alat_barang.*')
            ->leftJoin('tindakan', 'tindakan_alat_barang.tindakan_id', '=', 'tindakan.id')
            ->leftJoin('pembayaran', 'tindakan.registrasi_id', '=', 'pembayaran.registrasi_id')
            ->whereBetween('pembayaran.tanggal', [$this->tanggal1, $this->tanggal2])->get()->groupBy('aset_id')->map(function ($q) {
                return [
                    'aset_id' => $q->first()->aset_id,
                    'nama' => $q->first()->alat->nama,
                    'biaya_satuan' => $q->first()->biaya,
                    'biaya' => $q->sum(fn($q) => $q->biaya * $q->qty),
                    'qty' => $q->sum('qty'),
                ];
            })->sortByDesc('qty')->values()->toArray();
    }

    public function export()
    {
        return (new LaporanstatistikExport($this->getData(), $this->tanggal1, $this->tanggal2, 'penggunaanalat'))->download('penggunaanalat_' . $this->tanggal1 . '-' . $this->tanggal2 . '.xls');
    }

    public function render()
    {
        return view('livewire.laporan.statistik.penggunaanalat.index', [
            'data' => $this->getData(),
        ]);
    }
}
