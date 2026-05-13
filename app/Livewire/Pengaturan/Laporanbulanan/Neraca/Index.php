<?php

namespace App\Livewire\Pengaturan\Laporanbulanan\Neraca;

use App\Models\KeuanganTemplateLaporanKeuangan;
use App\Models\KodeAkun;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{

    public $aktiva, $pasiva, $dataKodeAkun = [], $kodeAkunBelumMasuk;

    public function mount()
    {
        $this->aktiva = $this->getAktiva();
        $this->pasiva = $this->getPasiva();
        $this->dataKodeAkun = KodeAkun::detail()->whereIn('kategori', ['Aktiva', 'Kewajiban', 'Ekuitas'])->get()->toArray();
        $this->kodeAkunBelumMasuk = collect($this->dataKodeAkun)->whereNotIn(
            'id',
            array_merge(collect($this->aktiva)->pluck('kode_akun')->flatten()->toArray(), collect($this->pasiva)->pluck('kode_akun')->flatten()->toArray())
        );
    }

    public function addAktiva()
    {
        $this->aktiva[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
            'isi' => 'Kode Akun',
            'kategori' => 'Aktiva',
        ];
    }

    public function addPasiva()
    {
        $this->pasiva[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
            'isi' => 'Kode Akun',
            'kategori' => null,
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

    public function deletePasiva($index)
    {
        unset($this->pasiva[$index]);
        $this->pasiva = array_values($this->pasiva);
    }

    public function simpanAktiva()
    {
        DB::transaction(function () {
            KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->where('kategori', 'Aktiva')->delete();
            KeuanganTemplateLaporanKeuangan::insert(collect($this->aktiva)->map(fn($item) => [
                'jenis' => 'Neraca',
                'kategori' => $item['kategori'],
                'urutan' => $item['urutan'],
                'nomor' => $item['nomor'],
                'uraian' => $item['uraian'],
                'kode_akun' => $item['kode_akun'] ? implode(';', $item['kode_akun']) : null,
                'rumus' => $item['rumus'],
                'isi' => $item['isi'],
                'pengguna_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray());
            $this->aktiva = $this->getAktiva();
            session()->flash('success', 'Berhasil mengupdate aktiva');
        });
    }


    public function simpanPasiva()
    {
        DB::transaction(function () {
            KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->whereIn('kategori', ['Kewajiban', 'Ekuitas'])->delete();
            KeuanganTemplateLaporanKeuangan::insert(collect($this->pasiva)->map(fn($item) => [
                'jenis' => 'Neraca',
                'kategori' => $item['kategori'],
                'urutan' => $item['urutan'],
                'nomor' => $item['nomor'],
                'uraian' => $item['uraian'],
                'kode_akun' => $item['kode_akun'] ? implode(';', $item['kode_akun']) : null,
                'rumus' => $item['rumus'],
                'isi' => $item['isi'],
                'pengguna_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray());
            $this->pasiva = $this->getPasiva();
            session()->flash('success', 'Berhasil mengupdate pasiva');
        });
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
            'kategori' => $q->kategori,
            'isi' => $q->isi,
        ])->toArray();
    }

    public function getPasiva()
    {
        return KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->whereIn('kategori', ['Kewajiban', 'Ekuitas'])->orderBy('urutan')->get()->map(fn($q) => [
            'id' => $q->id,
            'urutan' => $q->urutan,
            'nomor' => $q->nomor,
            'uraian' => $q->uraian,
            'kode_akun' => explode(';', $q->kode_akun),
            'rumus' => $q->rumus,
            'kategori' => $q->kategori,
            'isi' => $q->isi,
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.pengaturan.laporanbulanan.neraca.index');
    }
}
