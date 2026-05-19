<?php

namespace App\Livewire\Pengaturan\Kodeakuntransaksi;

use Livewire\Component;

class Index extends Component
{
    public $kategori = [
        'Aset Dalam Penyelesaian',
        'Aset Tetap',
        'Deposit Member',
        'HPP',
        'Hutang',
        'Kas In',
        'Kas Out',
        'Diskon Pendapatan',
        'Biaya Penyusutan',
        'Penyusutan Aset',
        'PPN Pembelian',
        'Diskon Pembelian'
    ];
    public function render()
    {
        return view('livewire.pengaturan.kodeakuntransaksi.index');
    }
}
