<?php

namespace App\Livewire\Pengaturan\Diskon;

use Livewire\Component;
use App\Class\BarangClass;
use App\Models\Diskon;
use App\Models\TarifTindakan;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    use CustomValidationTrait;
    public $jenis = 'Tindakan';
    public $data;
    public $tanggal_mulai;
    public $tanggal_berakhir;
    public $uraian;
    public $tarif_tindakan_id;
    public $barang_satuan_id;
    public $diskon = 0;
    public $barang = null;
    public $tarifTindakanAlat = null;
    public $dataTarifTindakan = [];
    public $dataBarang = [];

    public function mount(Diskon $data)
    {
        $this->data = $data;
        $this->dataBarang = collect(BarangClass::getBarang('Apotek'));
        $this->dataTarifTindakan = TarifTindakan::all();
    }

    public function submit()
    {
        if ($this->jenis == 'Tindakan') {
            $this->validateWithCustomMessages([
                'tarif_tindakan_id' => 'required',
            ]);
        } else {
            $this->validateWithCustomMessages([
                'barang_satuan_id' => 'required',
            ]);
        }


        $this->validateWithCustomMessages([
            'diskon' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date',
            'uraian' => 'required|string|max:255',
        ]);

        DB::transaction(function () {
            $this->data->tarif_tindakan_id = $this->jenis == 'Tindakan' ? $this->tarif_tindakan_id : null;
            $this->data->barang_satuan_id = $this->jenis == 'Tindakan' ? null : $this->barang_satuan_id;
            $this->data->diskon = $this->diskon;
            $this->data->tanggal_mulai = $this->tanggal_mulai;
            $this->data->tanggal_berakhir = $this->tanggal_berakhir;
            $this->data->uraian = $this->uraian;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pengaturan/diskon');
    }

    public function render()
    {
        return view('livewire.pengaturan.diskon.form');
    }
}
