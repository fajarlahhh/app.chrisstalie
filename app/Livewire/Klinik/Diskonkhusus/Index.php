<?php

namespace App\Livewire\Klinik\Diskonkhusus;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Registrasi;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari = '', $tanggal, $status = 1;

    public function mount()
    {
        if (empty($this->tanggal)) {
            $this->tanggal = date('Y-m-d');
        }
    }

    public function getQuery()
    {
        $query = Registrasi::query()
            ->with(['pasien', 'nakes', 'pengguna', 'pembayaran'])
            ->whereHas('pasien', function ($q) {
                if (!empty($this->cari)) {
                    $q->where('nama', 'like', '%' . $this->cari . '%');
                }
            });

        if ($this->status == 2) {
            $query->whereHas('pembayaran', function ($q) {
                $q->whereDate('tanggal', $this->tanggal);
            });
        } elseif ($this->status == 1) {
            $query->whereDoesntHave('pembayaran')->whereDoesntHave('pembayaran');
        }

        return $query->orderBy('id', 'asc');
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.klinik.diskonkhusus.index', [
            'data' => $this->getQuery()->paginate(10)
        ]);
    }
}
