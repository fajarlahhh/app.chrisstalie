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
    public $jenis_prabayar;
    public $tanggal_mulai_daftar;
    public $tanggal_selesai_daftar;
    public $tanggal_mulai_berlaku;
    public $tanggal_selesai_berlaku;
    public $jenis;
    public $dataKodeAkun;
    public $kode_akun_kewajiban_id;

    public function submit()
    {
        $this->validateWithCustomMessages(
            [
                'nama' => 'required',
                'uraian' => 'required',
                'jenis' => 'required',
                'masa_aktif' => $this->jenis_prabayar == 'Masa Aktif' ? 'required|numeric' : 'nullable|numeric',
                'jenis_prabayar' => $this->jenis == 'Prabayar' ? 'required' : 'nullable',
                'kode_akun_kewajiban_id' => $this->jenis == 'Prabayar' ? 'required' : 'nullable',
                'tanggal_mulai_daftar' => $this->jenis_prabayar == 'Periode Tanggal' ? 'required|date|after_or_equal:now' : 'nullable|date',
                'tanggal_selesai_daftar' => $this->jenis_prabayar == 'Periode Tanggal' ? 'required|date|after:tanggal_mulai_daftar' : 'nullable|date',
                'tanggal_mulai_berlaku' => $this->jenis_prabayar == 'Periode Tanggal' ? 'required|date|after_or_equal:tanggal_mulai_daftar' : 'nullable|date',
                'tanggal_selesai_berlaku' => $this->jenis_prabayar == 'Periode Tanggal' ? 'required|date|after:tanggal_mulai_berlaku' : 'nullable|date',
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
                $this->data->jenis_prabayar = $this->jenis_prabayar;
                $this->data->tanggal_mulai_daftar = $this->tanggal_mulai_daftar;
                $this->data->tanggal_selesai_daftar = $this->tanggal_selesai_daftar;
                $this->data->tanggal_mulai_berlaku = $this->tanggal_mulai_berlaku;
                $this->data->tanggal_selesai_berlaku = $this->tanggal_selesai_berlaku;
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
