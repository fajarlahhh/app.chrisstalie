<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class LaporanpersediaanExport implements FromView
{
    use Exportable;
    public $data, $dataStok, $bulan, $persediaan, $kode_akun, $cari, $jenis;

    public function __construct($data, $dataStok, $bulan, $persediaan, $kode_akun, $cari, $jenis)
    {
        $this->data = $data;
        $this->dataStok = $dataStok;
        $this->bulan = $bulan;
        $this->persediaan = $persediaan;
        $this->kode_akun = $kode_akun;
        $this->cari = $cari;
        $this->jenis = $jenis;
    }

    public function view(): View
    {
        return view('livewire.laporan.barangdagang.persediaan.cetak', [
            'cetak' => true,
            'data' => $this->data,
            'dataStok' => $this->dataStok,
            'bulan' => $this->bulan,
            'persediaan' => $this->persediaan,
            'kode_akun' => $this->kode_akun,
            'cari' => $this->cari,
            'jenis' => $this->jenis,
        ]);
    }
}
