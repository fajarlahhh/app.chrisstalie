<?php

namespace App\Livewire\Datamaster\Paketperawatan;

use Livewire\Component;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;
use App\Models\PaketPerawatan;
use App\Models\TarifTindakan;

class Form extends Component
{
    use CustomValidationTrait;
    public $data;
    public $dataTindakan = [];

    public $nama;
    public $uraian;
    public $tarif;
    public $masa_aktif;
    public $tindakan = [];
    public $jenis;

    public function submit()
    {
        $this->validateWithCustomMessages([
            'nama' => 'required',
            'uraian' => 'required',
            'tarif' => 'required|numeric',
            'jenis' => 'required',
            'masa_aktif' => $this->jenis == 'Non Bundling' ? 'required|numeric' : 'nullable|numeric',

        ]);
        DB::transaction(function () {
            $this->data->nama = $this->nama;
            $this->data->uraian = $this->uraian;
            $this->data->tarif = $this->tarif;
            $this->data->masa_aktif = $this->masa_aktif;
            $this->data->jenis = $this->jenis;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();

            $this->data->paketPerawatanDetail()->delete();
            $this->data->paketPerawatanDetail()->insert(collect($this->tindakan)->map(fn($q) => [
                'paket_perawatan_id' => $this->data->id,
                'tarif_tindakan_id' => $q['id'],
                'qty' => $q['qty'],
            ])->toArray());
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect(url()->previous());
    }

    public function mount(PaketPerawatan $data)
    {
        $this->dataTindakan = TarifTindakan::orderBy('nama')->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        if ($this->data->exists) {
            $this->tindakan = $this->data->paketPerawatanDetail->map(fn($q) => [
                'id' => $q->tarif_tindakan_id,
                'qty' => $q->qty,
                'tarif' => $q->tarifTindakan->tarif,
                'subtotal' => $q->tarifTindakan->tarif * $q->qty,
            ])->toArray();
        }
    }

    public function render()
    {
        return view('livewire.datamaster.paketperawatan.form');
    }
}
