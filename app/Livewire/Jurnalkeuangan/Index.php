<?php

namespace App\Livewire\Jurnalkeuangan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\KeuanganJurnal;
use App\Class\JurnalkeuanganClass;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $tanggal1, $tanggal2, $jenis, $kategori;

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-01');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
    }

    public function delete($id)
    {
        $data = KeuanganJurnal::findOrFail($id);
        if (JurnalkeuanganClass::tutupBuku(substr($data->tanggal, 0, 7) . '-01')) {
            session()->flash('danger', 'Pembukuan periode ini sudah ditutup');
            return;
        }
        $data->delete();
        session()->flash('success', 'Berhasil menghapus data');
    }

    public function getData()
    {
        return KeuanganJurnal::with(['keuanganJurnalDetail.kodeAkun', 'pengguna'])
            ->when($this->jenis, fn($q) => $q->where('jenis', $this->jenis))
            ->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
            ->when($this->kategori == 1, fn($q) => $q->where('system', $this->kategori))
            ->when($this->kategori == 2, fn($q) => $q->where('system', '0'))
            ->where(
                fn($q) => $q
                    ->where('id', 'like', $this->cari . '%')
                    ->orWhere('nomor', 'like', $this->cari . '%')
                    ->orWhere('uraian', 'like', '%' . $this->cari . '%')
            )
            ->orderBy('tanggal', 'desc')
            ->when(!auth()->user()->hasRole(['administrator', 'supervisor']), fn($q) => $q->where('pengguna_id', auth()->id()))
            ->paginate(10);
    }

    public function getJenis()
    {
        return KeuanganJurnal::whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])->select('jenis')->groupBy('jenis')->orderBy('jenis', 'asc')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.jurnalkeuangan.index', [
            'data' => $this->getData(),
            'dataJenis' => $this->getJenis()
        ]);
    }
}
