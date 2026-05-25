<?php

namespace App\Livewire\Member\Paketprabayar;

use App\Models\Pasien;
use Livewire\Component;
use App\Models\MetodeBayar;
use App\Models\PaketPerawatan;
use App\Class\JurnalkeuanganClass;
use Illuminate\Support\Facades\DB;
use App\Models\PasienPaketPrabayar;
use App\Traits\CustomValidationTrait;

class Form extends Component
{
    use CustomValidationTrait;

    public $dataNakes = [];
    public $pasien;
    public $tanggal;
    public $pasien_id;
    public $rm;
    public $catatan;
    public $nakes_id;
    public $nik;
    public $nama;
    public $alamat;
    public $jenis_kelamin;
    public $tanggal_lahir;
    public $no_hp;
    public $dataPaketPerawatan = [];
    public $paket_perawatan_id;
    public $paketPerawatan;
    public $dataMetodeBayar = [];
    public $metode_bayar = 1;
    public $total_tagihan;
    public $keterangan;
    public $total_bayar = 0;

    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
        $this->dataPaketPerawatan = PaketPerawatan::where('jenis', 'Prabayar')->get()->toArray();
        $this->dataMetodeBayar = MetodeBayar::orderBy('nama')->get()->toArray();
    }

    public function updatedPasienId($id)
    {
        $this->pasien_id = $id;
        $this->pasien = Pasien::find($id);

        if ($this->pasien) {
            $this->rm = $this->pasien->id;
            $this->nik = $this->pasien->nik;
            $this->nama = $this->pasien->nama;
            $this->alamat = $this->pasien->alamat;
            $this->jenis_kelamin = $this->pasien->jenis_kelamin;
            $this->tanggal_lahir = $this->pasien->tanggal_lahir ? $this->pasien->tanggal_lahir->format('Y-m-d') : null;
            $this->no_hp = $this->pasien->no_hp;
        } else {
            $this->resetPasien();
        }
    }

    public function resetPasien()
    {
        $this->reset([
            'nik',
            'rm',
            'nama',
            'alamat',
            'jenis_kelamin',
            'tanggal_lahir',
            'no_hp',
            'pasien_id',
        ]);
    }

    public function updatedPaketPerawatanId($value)
    {
        $this->paketPerawatan = PaketPerawatan::find($value);
    }

    public function submit()
    {
        if ($this->total_bayar < $this->total_tagihan) {
            $this->addError('total_bayar', 'Total Pembayaran tidak mencukupi');
            return;
        }
        $rules = [
            'tanggal' => 'required',
            'paket_perawatan_id' => 'required',
            'metode_bayar' => 'required',
            'total_bayar' => 'required|numeric|min:' . $this->total_tagihan,
        ];

        if (!$this->pasien_id) {
            $rules = array_merge(
                $rules,
                [
                    'nik' => 'required|unique:pasien,nik',
                    'nama' => 'required',
                    'alamat' => 'required',
                    'jenis_kelamin' => 'required',
                    'tanggal_lahir' => 'required',
                    'no_hp' => 'required',
                ]
            );
        }

        $this->validateWithCustomMessages($rules);

        DB::transaction(
            function () {
                $pasien = $this->_simpanPasien();
                $pembayaran = $this->_simpanPasienPaketPrabayar($pasien->id);

                $detail = [
                    [
                        'kode_akun_id' => $pembayaran->kode_akun_pembayaran_id,
                        'debet' => $this->total_tagihan,
                        'kredit' => 0,
                    ],
                    [
                        'kode_akun_id' =>  $pembayaran->kode_akun_paket_perawatan_id,
                        'debet' => 0,
                        'kredit' => $this->total_tagihan,
                    ],

                ];
                $this->_jurnalKeuangan(
                    $pembayaran->id,
                    (collect($detail)->groupBy('kode_akun_id')->map(
                        fn ($q) => [
                            'kode_akun_id' => $q->first()['kode_akun_id'],
                            'debet' => $q->sum('debet'),
                            'kredit' => $q->sum('kredit'),
                        ]
                    )->toArray())
                );
                $cetak = view('livewire.member.paketprabayar.cetak', [
                    'cetak' => true,
                    'data' => PasienPaketPrabayar::findOrFail($pembayaran->id),
                ])->render();
                session()->flash('cetak', $cetak);
                session()->flash('success', 'Berhasil menyimpan data');
            }
        );

        return redirect('/member/paketprabayar');
    }

    private function _jurnalKeuangan($pembayaranId, $detail)
    {
        JurnalkeuanganClass::insert(
            jenis: 'Hutang',
            sub_jenis: 'Hutang Paket Perawatan ' . $this->paketPerawatan->nama,
            tanggal: $this->tanggal,
            uraian: 'Hutang Paket Perawatan ' . $this->paketPerawatan->nama . ' No. Transaksi: ' . $pembayaranId . ' a/n ' . $this->nama . ' Ket : ' . $this->keterangan,
            system: 1,
            foreign_key: 'pasien_paket_prabayar_id',
            foreign_id: $pembayaranId,
            detail: $detail
        );
    }

    private function _simpanPasienPaketPrabayar(string $pasienId)
    {
        $dataTerakhir = PasienPaketPrabayar::where('tanggal', 'like', date('Y-m', strtotime($this->tanggal)) . '%')->orderBy('id', 'desc')->first();

        if ($dataTerakhir) {
            $id = $dataTerakhir->id + 1;
        } else {
            $id = date('Ym') . '00001';
        }
        $pasienPaketPrabayar = new PasienPaketPrabayar();
        $pasienPaketPrabayar->id = $id;
        $pasienPaketPrabayar->total = $this->total_tagihan;
        $pasienPaketPrabayar->pasien_id = $pasienId;
        $pasienPaketPrabayar->paket_perawatan_id = $this->paket_perawatan_id;
        $pasienPaketPrabayar->tanggal_aktif = $this->tanggal;
        $pasienPaketPrabayar->tanggal_berakhir = date('Y-m-d', strtotime($this->tanggal . ' + ' . $this->paketPerawatan->masa_aktif . ' days'));
        $pasienPaketPrabayar->qty = $this->paketPerawatan->paketPerawatanDetail->first()->qty;
        $pasienPaketPrabayar->aktif = true;
        $pasienPaketPrabayar->metode_bayar = collect($this->dataMetodeBayar)->where('id', $this->metode_bayar)->first()['nama'];
        $pasienPaketPrabayar->bayar = $this->total_bayar;
        $pasienPaketPrabayar->kode_akun_pembayaran_id = collect($this->dataMetodeBayar)->where('id', $this->metode_bayar)->first()['kode_akun_id'];
        $pasienPaketPrabayar->kode_akun_paket_perawatan_id = $this->paketPerawatan->kode_akun_kewajiban_id;
        $pasienPaketPrabayar->tanggal = $this->tanggal;
        $pasienPaketPrabayar->keterangan = $this->keterangan;
        $pasienPaketPrabayar->pengguna_id = auth()->id();
        $pasienPaketPrabayar->save();
        return $pasienPaketPrabayar;
    }

    private function _simpanPasien()
    {
        if (!$this->pasien_id) {
            $pasien = new Pasien();
            $last = Pasien::where('created_at', 'like', date('Y-m') . '%')
                ->orderBy('created_at', 'desc')
                ->first();

            $lastRm = $last ? (int)substr($last->id, 6, 4) : 0;
            $pasien->id = date('y.m.') . sprintf('%04d', $lastRm + 1);
            $pasien->pengguna_id = auth()->id();
        } else {
            $pasien = Pasien::find($this->pasien_id);
        }

        $pasien->nik = $this->nik;
        $pasien->nama = $this->nama;
        $pasien->alamat = $this->alamat;
        $pasien->jenis_kelamin = $this->jenis_kelamin;
        $pasien->tanggal_lahir = $this->tanggal_lahir;
        $pasien->no_hp = $this->no_hp;
        $pasien->tanggal_daftar = $this->tanggal;
        $pasien->save();
        return $pasien;
    }

    public function render()
    {
        return view('livewire.member.paketprabayar.form');
    }
}
