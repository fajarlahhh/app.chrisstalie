<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class DatamasterExport implements FromView
{
    use Exportable;
    public $data, $jenis, $folder;

    public function __construct($data, $jenis, $folder = 'datamaster')
    {
        $this->data = $data;
        $this->jenis = $jenis;
        $this->folder = $folder;
    }

    public function view(): View
    {
        //
        return view('livewire.' . $this->folder . '.' . $this->jenis . '.tabel', [
            'cetak' => true,
            'data' => $this->data,
        ]);
    }
}
