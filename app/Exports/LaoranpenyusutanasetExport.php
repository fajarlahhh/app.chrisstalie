<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaoranpenyusutanasetExport implements FromView
{
    public $data, $tahun;

    public function __construct($data, $tahun)
    {
        $this->data = $data;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        //
        return view('livewire.laporan.penyusutanaset.cetak', [
            'cetak' => true,
            'data' => $this->data,
            'tahun' => $this->tahun,
        ]);
    }
}
