<?php

namespace App\Livewire\Pengaturan\Laporanbulanan\Neraca;

use Livewire\Component;
use App\Models\KeuanganTemplateLaporanKeuangan;
use App\Models\KodeAkun;

class Index extends Component
{

    public $aktiva, $kewajiban, $ekuitas, $dataKodeAkun = [], $kodeAkunBelumMasukAktiva, $kodeAkunBelumMasukKewajiban, $kodeAkunBelumMasukEkuitas;

    public function mount()
    {
        $this->aktiva = $this->getAktiva();
        $this->kewajiban = $this->getKewajiban();
        $this->ekuitas = $this->getEkuitas();
        
        $this->dataKodeAkun = KodeAkun::detail()->whereIn('kategori', ['Aktiva', 'Kewajiban', 'Ekuitas'])->get()->toArray();
        $this->kodeAkunBelumMasukAktiva = collect($this->dataKodeAkun)->where('kategori', 'Aktiva')->whereNotIn('id', collect($this->aktiva)->pluck('kode_akun')->flatten()->toArray());
    }
    
    public function addAktiva()
    {
        $this->aktiva[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
        ];
    }

    public function addKewajiban()
    {
        $this->kewajiban[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
        ];
    }

    public function addEkuitas()
    {
        $this->ekuitas[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
        ];
    }

    public function sortAktiva()
    {
        $this->aktiva = array_values(collect($this->aktiva)->sortBy('urutan')->toArray());
    }

    public function deleteAktiva($index)
    {
        unset($this->aktiva[$index]);
        $this->aktiva = array_values($this->aktiva);
    }

    public function deleteKewajiban($index)
    {
        unset($this->kewajiban[$index]);
        $this->kewajiban = array_values($this->kewajiban);
    }

    public function deleteEkuitas($index)
    {
        unset($this->ekuitas[$index]);
        $this->ekuitas = array_values($this->ekuitas);
    }

    public function getAktiva()
    {
        return KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->where('kategori', 'Aktiva')->orderBy('urutan')->get()->map(fn($q) => [
            'id' => $q->id,
            'urutan' => $q->urutan,
            'nomor' => $q->nomor,
            'uraian' => $q->uraian,
            'kode_akun' => explode(';', $q->kode_akun),
            'rumus' => $q->rumus,
        ])->toArray();
    }
    
    public function getKewajiban()
    {
        return KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->where('kategori', 'Kewajiban')->orderBy('urutan')->get()->toArray();
    }

    public function getEkuitas()
    {
        return KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->where('kategori', 'Ekuitas')->orderBy('urutan')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.pengaturan.laporanbulanan.neraca.index');
    }
}
