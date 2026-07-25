<?php

namespace App\Livewire\Pengaturan\Diskon;

use Livewire\Component;
use App\Models\Diskon;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Maatwebsite\Excel\Files\Disk;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari;

    public function updated()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            Diskon::findOrFail($id)
                ->forceDelete();
            session()->flash('success', 'Berhasil menghapus data');
        } catch (\Throwable $th) {
            session()->flash('danger', 'Gagal menghapus data');
        };
    }

    private function getData($paginate = true)
    {
        $query = Diskon::with([
            'barangSatuan.barang',
            'tarifTindakan'
        ])->where('tanggal_berakhir', '>=', date('Y-m-d'))
            ->where(fn($q) => $q
                ->where('uraian', 'like', '%' . $this->cari . '%'))
            ->orderBy('uraian');

        return $paginate ? $query->paginate(10) : $query->get();
    }

    public function render()
    {
        return view('livewire.pengaturan.diskon.index', [
            'data' => $this->getData(true),
        ]);
    }
}
