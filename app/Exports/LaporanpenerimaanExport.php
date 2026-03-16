<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class LaporanpenerimaanExport implements FromView
{
    use Exportable;
    public $data, $tanggal1, $tanggal2, $pengguna;

    public function __construct($data, $tanggal1, $tanggal2, $pengguna)
    {
        $this->data = $data;
        $this->tanggal1 = $tanggal1;
        $this->tanggal2 = $tanggal2;
        $this->pengguna = $pengguna;
    }

    public function view(): View
    {
        //
        return view('livewire.laporan.penerimaan.cetak', [
            'cetak' => true,
            'data' => $this->data,
            'tanggal1' => $this->tanggal1,
            'tanggal2' => $this->tanggal2,
            'pengguna' => $this->pengguna,
        ]);
    }
}
