<?php

namespace App\Livewire\Member\Paketprabayar;

use Livewire\Component;
use App\Models\Member;
use App\Models\PasienPaketPrabayar;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $cari = '';

    #[Url]
    public string $status = 'aktif';

    public function mount()
    {
    }

    public function delete($id)
    {
        $data = PasienPaketPrabayar::find($id);
        if ($data->qty_sisa == 0) {
            $data->delete();
            session()->flash('success', 'Berhasil menghapus data');
        } else {
            session()->flash('danger', 'Data tidak bisa dihapus karena paket sudah digunakan');
        }
    }

    public function getData($paginate = true)
    {
        $query = PasienPaketPrabayar::with('paketPerawatan.paketPerawatanDetail.tarifTindakan', 'pengguna')->where(fn ($q) => $q
            ->orWhereHas('pasien', fn ($r) => $r->where('nama', 'like', '%' . $this->cari . '%')));
        if ($this->status == 'aktif') {
            $query->aktif();
        }
        if ($this->status == 'tidak aktif') {
            $query->tidakAktif();
        }
        return $paginate ? $query->paginate(10) : $query;
    }

    public function render()
    {
        return view('livewire.member.paketprabayar.index', [
            'data' => $this->getData(true),
        ]);
    }
}
