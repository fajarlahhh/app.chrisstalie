<?php

namespace App\Livewire\Laporan\Statistik\Kunjunganpasien;

use Livewire\Component;
use App\Models\Pembayaran;
use App\Exports\LaporanstatistikExport;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $tanggal1, $tanggal2, $sort = 'qty', $jenis = 'perpasien';

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-01');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
    }

    public function getData()
    {
        if ($this->jenis == 'perpasien') {
            return Pembayaran::with('pasien', 'registrasi.tindakan.tarifTindakan', 'stokKeluar.barang')->whereNotNull('pasien_id')->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
                ->get()->map(function ($q) {
                    return [
                        'tanggal' => $q->tanggal,
                        'pasien_id' => $q->pasien_id,
                        'id' => $q->pasien->id,
                        'alamat' => $q->pasien->alamat,
                        'jenis_kelamin' => $q->pasien->jenis_kelamin,
                        'nama' => $q->pasien->nama,
                        'biaya' => $q->total_tagihan,
                        'tindakan' => $q->registrasi?->tindakan?->map(fn($r) => [
                            'nama' => $r->tarifTindakan->nama . ' (' . $r->qty . ' x ' . number_format_id($r->biaya - $r->diskon, 2) . ')',
                        ])->toArray(),
                        'barang' => $q->stokKeluar->where('harga', '>', 0)->map(fn($r) => [
                            'nama' => $r->barang->nama . ' (' . $r->qty . ' x ' . number_format_id($r->harga - $r->diskon, 2) . ')',
                        ])->toArray(),
                    ];
                })
                ->groupBy('pasien_id')->map(function ($q) {
                    return [
                        'pasien_id' => $q->first()['pasien_id'],
                        'id' => $q->first()['id'],
                        'alamat' => $q->first()['alamat'],
                        'jenis_kelamin' => $q->first()['jenis_kelamin'],
                        'nama' => $q->first()['nama'],
                        'biaya' => $q->sum(fn($q) => $q['biaya']),
                        'qty' => $q->count(),
                        'tindakan' => implode("<br>", $q->pluck('tindakan')->flatten()->toArray()),
                        'barang' => implode("<br>", $q->pluck('barang')->flatten()->toArray()),
                    ];
                })->sortByDesc($this->sort)->values()->toArray();
        } else if ($this->jenis == 'pertanggal') {
            return Pembayaran::with('pasien', 'registrasi.tindakan.tarifTindakan', 'stokKeluar.barang')->whereNotNull('pasien_id')->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
                ->get()->map(function ($q) {
                    return [
                        'tanggal' => $q->tanggal,
                        'biaya' => $q->total_tagihan,
                        'tindakan' => $q->registrasi?->tindakan?->map(fn($r) => [
                            'nama' => $r->tarifTindakan->nama,
                        ])->toArray(),
                        'barang' => $q->stokKeluar->where('harga', '>', 0)->map(fn($r) => [
                            'nama' => $r->barang->nama,
                        ])->toArray(),
                    ];
                })
                ->groupBy('tanggal')->map(function ($q) {
                    return [
                        'tanggal' => $q->first()['tanggal'],
                        'biaya' => $q->sum(fn($q) => $q['biaya']),
                        'qty' => $q->count(),
                        'tindakan' => implode("<br>", $q->pluck('tindakan')->flatten()->toArray()),
                        'barang' => implode("<br>", $q->pluck('barang')->flatten()->toArray()),
                    ];
                })->sortByDesc($this->sort)->values()->toArray();
        } else {
            $query = Pembayaran::with([
                'registrasi.pasien',
                'pengguna',
                'registrasi.tindakan.tarifTindakan',
                'registrasi.resepObat.barang',
                'stokKeluarPenjualan.barang'
            ])->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2]);
            if (!auth()->user()->hasRole(['administrator', 'supervisor'])) {
                $query->where('pengguna_id', auth()->id());
            }
            return $query->get();
        }
    }

    public function export()
    {
        return (new LaporanstatistikExport($this->getData(), $this->tanggal1, $this->tanggal2, 'kunjunganpasien', $this->jenis))->download('kunjunganpasien_' . $this->tanggal1 . '-' . $this->tanggal2 . '.xls');
    }

    public function render()
    {
        return view('livewire.laporan.statistik.kunjunganpasien.index', [
            'data' => $this->getData(),
        ]);
    }
}
