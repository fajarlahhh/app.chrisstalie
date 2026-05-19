<?php

namespace App\Livewire\Datamaster\Paketperawatan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\KodeAkun;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DatamasterExport;
use App\Models\PaketPerawatan;

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
            PaketPerawatan::findOrFail($id)
                ->forceDelete();
            session()->flash('success', 'Berhasil menghapus data');
        } catch (\Throwable $th) {
            session()->flash('danger', 'Gagal menghapus data');
        };
    }

    private function getData($paginate = true)
    {
        $query = PaketPerawatan::with([
            'pengguna',
            'paketPerawatanDetail',
        ])
            ->where(fn($q) => $q
                ->where('nama', 'like', '%' . $this->cari . '%'))
            ->orderBy('nama');

        return $paginate ? $query->paginate(10) : $query->get();
    }

    public function export()
    {
        return Excel::download(new DatamasterExport($this->getData(false), 'paketperawatan'), 'paket_perawatan.xlsx');
    }

    public function render()
    {
        return view('livewire.datamaster.paketperawatan.index', [
            'data' => $this->getData(true),
        ]);
    }
}
