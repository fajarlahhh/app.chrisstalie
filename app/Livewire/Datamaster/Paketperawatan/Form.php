<?php

namespace App\Livewire\Datamaster\Paketperawatan;

use App\Models\KodeAkun;
use Livewire\Component;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;
use App\Models\PaketPerawatan;
use App\Models\TarifTindakan;

class Form extends Component
{
    use CustomValidationTrait;
    public PaketPerawatan $data;
    public $dataTindakan = [];
    public $nama;
    public $uraian;
    public $tarif;
    public $masa_aktif;
    public $tindakan = [];
    public $jenis;
    public $dataKodeAkun;
    public $kode_akun_kewajiban_id;

    public function submit()
    {
        $this->validateWithCustomMessages(
            [
                'nama' => 'required',
                'uraian' => 'required',
                'tarif' => 'required|numeric',
                'jenis' => 'required',
                'masa_aktif' => $this->jenis == 'Prabayar' ? 'required|numeric' : 'nullable|numeric',
            ]
        );
        DB::transaction(
            function () {
                $this->data->nama = $this->nama;
                $this->data->uraian = $this->uraian;
                $this->data->tarif = collect($this->tindakan)->sum(fn($q) => $q['harga_jual'] * $q['qty']);
                $this->data->masa_aktif = $this->masa_aktif;
                $this->data->qty = $this->jenis == 'Prabayar' ? collect($this->tindakan)->sum('qty') : null;
                $this->data->jenis = $this->jenis;
                $this->data->kode_akun_kewajiban_id = $this->kode_akun_kewajiban_id;
                $this->data->pengguna_id = auth()->id();
                $this->data->save();

                $this->data->paketPerawatanDetail()->delete();
                $this->data->paketPerawatanDetail()->insert(
                    collect($this->tindakan)->map(
                        fn($q) => [
                            'paket_perawatan_id' => $this->data->id,
                            'tarif_tindakan_id' => $q['id'],
                            'qty' => $q['qty'],
                            'harga_jual' => $q['harga_jual'],
                        ]
                    )
                        ->toArray()
                );
                session()->flash('success', 'Berhasil menyimpan data');
            }
        );
        $this->redirect(url()->previous());
    }

    public function mount(PaketPerawatan $data)
    {
        $this->dataTindakan = TarifTindakan::orderBy('nama')->get()->toArray();
        $this->dataKodeAkun = KodeAkun::whereIn('kategori', ['Kewajiban', 'Pendapatan'])->detail()->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        if ($this->data->exists) {
            $this->tindakan = $this->data->paketPerawatanDetail->map(
                fn($q) => [
                    'id' => $q->tarif_tindakan_id,
                    'qty' => $q->qty,
                    'tarif' => $q->tarifTindakan->tarif,
                    'harga_jual' => $q->harga_jual,
                    'subtotal' => $q->harga_jual * $q->qty,
                ]
            )->toArray();
        }
    }

    public function render()
    {
        return view('livewire.datamaster.paketperawatan.form');
    }
}
