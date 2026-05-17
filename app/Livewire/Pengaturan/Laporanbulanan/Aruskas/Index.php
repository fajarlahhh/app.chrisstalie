<?php

namespace App\Livewire\Pengaturan\Laporanbulanan\Aruskas;

use Livewire\Component;
use App\Models\KodeAkun;
use App\Models\KeuanganTemplateLaporanKeuangan;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $data, $dataKodeAkun = [], $kodeAkunDouble = [];

    public function mount()
    {
        $this->data = $this->getData();
        $this->dataKodeAkun = KodeAkun::detail()->get()->toArray();
        // Cari kode_akun_id yang double muncul di $this->data
        $kodeAkunAll = [];

        foreach ($this->data as $item) {
            if (isset($item['kode_akun']) && is_array($item['kode_akun'])) {
                foreach ($item['kode_akun'] as $kode) {
                    $kodeAkunAll[] = $kode;
                }
            }
        }

        $this->kodeAkunDouble = array_values(array_unique(array_diff_assoc($kodeAkunAll, array_unique($kodeAkunAll))));
    }

    public function addData()
    {
        $this->data[] = [
            'urutan' => '',
            'nomor' => '',
            'uraian' => '',
            'kode_akun' => '',
            'rumus' => '',
            'isi' => 'Tidak Ada',
            'kategori' => null,
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
            KeuanganTemplateLaporanKeuangan::where('jenis', 'Arus Kas')->delete();
            KeuanganTemplateLaporanKeuangan::insert(collect($this->data)->map(fn($item) => [
                'jenis' => 'Arus Kas',
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
            session()->flash('success', 'Berhasil mengupdate arus kas');
        });
    }

    public function getData()
    {
        return KeuanganTemplateLaporanKeuangan::where('jenis', 'Arus Kas')->orderBy('urutan')->get()->map(fn($q) => [
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
        return view('livewire.pengaturan.laporanbulanan.aruskas.index');
    }
}
