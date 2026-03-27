<?php

namespace App\Livewire\Datamaster\Icd10;

use Livewire\Component;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Icd10;

class Form extends Component
{
    use CustomValidationTrait;
    public $data;
    
    public $kode;
    public $uraian;
    
    public function submit()
    {
        $this->validateWithCustomMessages([
            'kode' => 'required|unique:icd10,id,' . $this->data->id,
            'uraian' => 'required', 
        ]);

        DB::transaction(function () {
            $this->data->id = $this->kode;
            $this->data->uraian = $this->uraian;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect(url()->previous());
    }

    public function mount(Icd10 $data)
    {
        
        $this->data = $data;
        $this->kode = $this->data->id;
        $this->fill($this->data->toArray());
    }
    public function render()
    {
        return view('livewire.datamaster.icd10.form');
    }
}
