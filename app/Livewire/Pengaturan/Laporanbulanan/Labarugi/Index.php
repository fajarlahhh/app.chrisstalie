<?php

namespace App\Livewire\Pengaturan\Laporanbulanan\Labarugi;

use App\Models\KeuanganTemplateLaporanKeuangan;
use Livewire\Component;
use App\Models\KodeAkun;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $data, $dataKodeAkun = [], $kodeAkunBelumMasuk;

    public function mount()
    {
        $this->data = $this->getData();
        $this->dataKodeAkun = KodeAkun::detail()->whereIn('kategori', ['Pendapatan', 'Beban'])->get()->toArray();
        $this->kodeAkunBelumMasuk = collect($this->dataKodeAkun)->whereNotIn(
            'id',
            collect($this->data)->pluck('kode_akun')->flatten()->toArray()
        );
    }

    public function addData()
    {
        $this->data[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
            'isi' => 'Kode Akun',
            'kategori' => 'Pendapatan',
        ];
    }

    public function sortData()
    {
        $this->data = array_values(collect($this->data)->sortBy('urutan')->toArray());
    }

    public function deleteData($index)
    {
        unset($this->data[$index]);
        $this->data = array_values($this->data);
    }

    public function simpan()
    {
        DB::transaction(function () {
            KeuanganTemplateLaporanKeuangan::where('jenis', 'Laba Rugi')->delete();
            KeuanganTemplateLaporanKeuangan::insert(collect($this->data)->map(fn($item) => [
                'jenis' => 'Laba Rugi',
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
            $this->data = $this->getData();
            session()->flash('success', 'Berhasil mengupdate laba rugi');
        });
    }

    public function getData()
    {
        return KeuanganTemplateLaporanKeuangan::where('jenis', 'Laba Rugi')->orderBy('urutan')->get()->map(fn($q) => [
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
        return view('livewire.pengaturan.laporanbulanan.labarugi.index');
    }
}
