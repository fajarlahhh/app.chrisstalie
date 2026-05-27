<?php

namespace App\Livewire\Laporan\Laporanhariankas;

use App\Models\Sale;
use App\Models\Kasir;
use App\Models\KeuanganJurnal;
use Livewire\Component;
use App\Models\KodeAkun;
use App\Models\Pengguna;
use App\Models\Pembayaran;
use App\Models\Expenditure;
use App\Models\MetodeBayar;
use App\Models\KeuanganJurnalDetail;
use App\Models\PasienPaketPrabayar;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $tanggal, $pengguna_id;

    public $dataKodeAkun = [], $dataMetodeBayar = [];

    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
        $this->dataKodeAkun = KodeAkun::all()->toArray();
        $this->dataMetodeBayar = MetodeBayar::all()->toArray();
    }

    public function print()
    {
        $cetak = view('livewire.laporan.laporanhariankas.cetak', [
            'cetak' => true,
            'tanggal' => $this->tanggal,
            'dataMetodeBayar' => $this->dataMetodeBayar,
            'dataKodeAkun' => $this->dataKodeAkun,
            'pengguna' => $this->pengguna_id ? Pengguna::find($this->pengguna_id)?->kepegawaianPegawai?->nama ?? Pengguna::find($this->pengguna_id)?->nama : 'Semua Pengguna',
            'data' => $this->getData(),
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getPendapatan()
    {
        $prabayar = PasienPaketPrabayar::with(['pengguna'])
        ->where('tanggal', 'like', $this->tanggal . '%')->get()->map(fn($q) => [
            'metode_bayar' => $q['metode_bayar'],
            'bayar' => $q['bayar'],
            'selisih' => 0,
            'metode_bayar_2' => null,
            'bayar_2' => 0,
            'total_diskon_barang' => 0,
            'total_diskon_tindakan' =>0,
            'diskon' => 0,
            'pengguna_id' => $q['pengguna_id']
        ])->toArray();
        $pembayaran = Pembayaran::with(['pengguna'])
            ->where('tanggal', 'like', $this->tanggal . '%')->get()->map(fn($q) => [
                'metode_bayar' => $q['metode_bayar'],
                'bayar' => $q['bayar'],
                'selisih' => $q['selisih'],
                'metode_bayar_2' => $q['metode_bayar_2'],
                'bayar_2' => $q['bayar_2'],
                'total_diskon_barang' => $q['total_diskon_barang'],
                'total_diskon_tindakan' => $q['total_diskon_tindakan'],
                'diskon' => $q['diskon'],
                'pengguna_id' => $q['pengguna_id']
            ])->toArray();
        return collect(array_merge($pembayaran, $prabayar));
    }

    public function getPengeluaran()
    {
        return KeuanganJurnal::with('keuanganJurnalDetail.kodeAkun', 'pengguna')
            ->leftJoin('keuangan_jurnal_detail', 'keuangan_jurnal.id', '=', 'keuangan_jurnal_detail.keuangan_jurnal_id')
            ->leftJoin('kode_akun', 'keuangan_jurnal_detail.kode_akun_id', '=', 'kode_akun.id')->select(
                'keuangan_jurnal.id as id',
                'keuangan_jurnal.uraian as uraian',
                'keuangan_jurnal_detail.kode_akun_id as kode_akun_id',
                'keuangan_jurnal_detail.debet as debet',
                'keuangan_jurnal_detail.kredit as kredit',
                'kode_akun.nama as kode_akun_nama'
            )
            ->whereIn('jenis', ['Pembelian', 'Pengeluaran'])
            ->where('tanggal', $this->tanggal)->get();
    }

    public function render()
    {
        $dataPendapatan = $this->getPendapatan();
        $dataPengeluaran = $this->getPengeluaran();

        $pendapatan = [];
        foreach ($this->getPendapatan()->when($this->pengguna_id, fn($q) => $q->where('pengguna_id', $this->pengguna_id)) as $key => $value) {
            $pendapatan[] = [
                'metode_bayar' => $value['metode_bayar'],
                'total_tagihan' => $value['bayar'] - $value['selisih'],
                'total_diskon_barang' => $value['total_diskon_barang'],
                'total_diskon_tindakan' => $value['total_diskon_tindakan'],
                'diskon' => $value['diskon']
            ];
            if ($value['metode_bayar_2'] != null) {
                $pendapatan[] = [
                    'metode_bayar' => $value['metode_bayar_2'],
                    'total_tagihan' => $value['bayar_2'],
                    'total_diskon_barang' => 0,
                    'total_diskon_tindakan' => 0,
                    'diskon' => 0
                ];
            }
        }

        return view('livewire.laporan.laporanhariankas.index', [
            'dataPendapatan' =>  collect($pendapatan),
            'dataPengeluaran' =>  $this->getPengeluaran()->when($this->pengguna_id, fn($q) => $q->where('pengguna_id', $this->pengguna_id)),
            'dataPengguna' => Pengguna::whereIn('id', (array_merge($dataPendapatan->pluck('pengguna_id')->unique()->toArray(), $dataPengeluaran->pluck('pengguna_id')->unique()->toArray())))->get()
        ]);
    }
}
