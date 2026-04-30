<?php

namespace App\Livewire\Kepegawaian\Penggajian;

use App\Models\KepegawaianPegawai;
use Livewire\Component;
use App\Models\KodeAkun;
use App\Class\JurnalkeuanganClass;
use App\Models\KepegawaianPenggajian;
use Illuminate\Support\Facades\DB;
use App\Traits\CustomValidationTrait;
use App\Traits\KodeakuntransaksiTrait;

class Form extends Component
{
    use CustomValidationTrait;
    use KodeakuntransaksiTrait;
    public $dataPegawai = [], $dataUnsurGaji = [], $dataKodeAkun = [], $metode_bayar;
    public $tanggal, $periode, $detail = [], $pegawai_id;

    public function mount()
    {
        $this->dataKodeAkun = KodeAkun::detail()->whereIn('id', $this->getKodeAkunTransaksiByTransaksi(['Pembayaran', 'Hutang Gaji'])->pluck('kode_akun_id'))->get()->toArray();
        $this->tanggal = date('Y-m-01');
        $this->periode = date('Y-m');
        $this->updatedPeriode($this->periode);
    }

    public function updatedPegawaiId($value)
    {
        $this->detail = [];
        $this->detail = collect(collect($this->dataPegawai)->where('id', $value)->first()['kepegawaian_pegawai_unsur_gaji'])->map(fn($q) => [
            'kode_akun_id' => $q['kode_akun_id'],
            'kode_akun_nama' => $q['kode_akun']['nama'],
            'kode_akun_sumber_dana_id' => null,
            'debet' => $q['nilai'],
            'kredit' => 0,
            'sifat' => $q['sifat'],
        ])->toArray();
    }

    public function updatedPeriode($value)
    {
        $this->detail = [];
        $this->dataPegawai = KepegawaianPegawai::with('kepegawaianPegawaiUnsurGaji.kodeAkun')->orderBy('nama', 'asc')->whereNotIn('id', KepegawaianPenggajian::where('periode', $value . '-01')->get()->pluck('kepegawaian_pegawai_id'))
            ->where('tanggal_masuk', '<', \Carbon\Carbon::parse($value . '-01')->format('Y-m-t'))
            ->where(fn($q) => $q->where('tanggal_keluar', '>', \Carbon\Carbon::parse($value . '-01')->format('Y-m-01'))->orWhereNull('tanggal_keluar'))
            ->get()->toArray();
    }

    public function submit()
    {
        $this->validateWithCustomMessages([
            'periode' => 'required',
            'tanggal' => 'required',
            'pegawai_id' => 'required',
            'detail.*.kode_akun_sumber_dana_id' => 'required',
        ]);

        if (JurnalkeuanganClass::tutupBuku(substr($this->tanggal, 0, 7) . '-01')) {
            session()->flash('danger', 'Pembukuan periode ini sudah ditutup');
            return;
        }
       
        DB::transaction(function () {
            $detail1 = collect($this->detail)->map(fn($q) => [
                'kode_akun_id' => $q['kode_akun_id'],
                'kode_akun_nama' => $q['kode_akun_nama'],
                'debet' => $q['debet'],
                'kredit' => 0,
            ])->toArray();
            $detail2 = collect($this->detail)->map(fn($q) => [
                'kode_akun_id' => $q['kode_akun_sumber_dana_id'],
                'kode_akun_nama' => collect($this->dataKodeAkun)->where('id', $q['kode_akun_sumber_dana_id'])->first()['nama'],
                'debet' => 0,
                'kredit' => $q['debet'],
            ])->toArray();
            $detail = array_merge($detail1, $detail2);
              
            $penggajian = new KepegawaianPenggajian();
            $penggajian->tanggal = $this->tanggal;
            $penggajian->periode = $this->periode . '-01';
            $penggajian->detail = collect($this->detail)->map(fn($q) => [
                'kode_akun_id' => $q['kode_akun_id'],
                'kode_akun_nama' => $q['kode_akun_nama'],
                'kode_akun_sumber_dana_id' => $q['kode_akun_sumber_dana_id'],
                'kode_akun_sumber_dana_nama' => collect($this->dataKodeAkun)->where('id', $q['kode_akun_sumber_dana_id'])->first()['nama'],
                'debet' => $q['debet'],
                'kredit' => $q['kredit'],
                'sifat' => $q['sifat'],
            ])->toArray();
            $penggajian->kepegawaian_pegawai_id = $this->pegawai_id;
            $penggajian->pengguna_id = auth()->id();
            $penggajian->save();
            $this->jurnalKeuangan($penggajian, $detail);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        return redirect()->to('kepegawaian/penggajian');
    }


    private function jurnalKeuangan($penggajian, $detail)
    {
        JurnalkeuanganClass::insert(
            jenis: 'Pengeluaran',
            sub_jenis: 'Pengeluaran Gaji Pegawai',
            tanggal: $this->tanggal,
            uraian: 'Gaji Bulan ' . $this->periode,
            system: 1,
            foreign_key: 'kepegawaian_penggajian_id',
            foreign_id: $penggajian->id,
            detail: collect($detail)->groupBy('kode_akun_id')->map(fn($q) => [
                'debet' => $q->sum('debet'),
                'kredit' => $q->sum('kredit'),
                'kode_akun_id' => $q->first()['kode_akun_id'],
            ])->values()->toArray()
        );
    }
    public function render()
    {
        return view('livewire.kepegawaian.penggajian.form');
    }
}
