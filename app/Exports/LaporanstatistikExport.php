<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class LaporanstatistikExport implements FromView
{
    use Exportable;
    public $data, $tanggal1, $tanggal2, $laporan;

    public function __construct($data, $tanggal1, $tanggal2, $laporan)
    {
        $this->data = $data;
        $this->tanggal1 = $tanggal1;
        $this->tanggal2 = $tanggal2;
        $this->laporan = $laporan;
    }

    public function view(): View
    {
        return view('livewire.laporan.statistik.' . $this->laporan . '.cetak', [
            'cetak' => true,
            'tanggal1' => $this->tanggal1,
            'tanggal2' => $this->tanggal2,
            'data' => $this->data,
        ]);
    }
}
