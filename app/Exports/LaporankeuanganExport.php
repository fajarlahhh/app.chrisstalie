<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class LaporankeuanganExport implements FromView
{
    use Exportable;
    public $data, $bulan, $laporan, $jenis;

    public function __construct($data, $bulan, $laporan, $jenis = 'cetak')
    {
        $this->data = $data;
        $this->bulan = $bulan;
        $this->laporan = $laporan;
        $this->jenis = $jenis;
    }

    public function view(): View
    {
        //
        return view('livewire.laporan.keuanganbulanan.' . $this->laporan . '.' . $this->jenis, [
            'cetak' => true,
            'data' => $this->data,
            'bulan' => $this->bulan,
            'tahun' => $this->bulan,
        ]);
    }
}
