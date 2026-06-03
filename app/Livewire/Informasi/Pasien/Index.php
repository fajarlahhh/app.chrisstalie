<?php

namespace App\Livewire\Informasi\Pasien;

use Livewire\Component;
use App\Models\Pasien;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $noRm;

    public $dataPasien;
    public $rekamMedis;

    public function updatedNoRm()
    {
        $this->dataPasien = $this->getRekamMedis($this->noRm);
    }

    private function getRekamMedis($id)
    {        
        return Pasien::with(
            'pembayaranTerakhir.registrasi.nakes',
            'pembayaranTerakhir.registrasi.tug',
            'pembayaranTerakhir.registrasi.pengguna',
            'pembayaranTerakhir.registrasi.pemeriksaanAwal.pengguna',
            'pembayaranTerakhir.registrasi.diagnosis.pengguna',
            'pembayaranTerakhir.registrasi.tindakan.pengguna',
            'pembayaranTerakhir.registrasi.tindakan.tarifTindakan',
            'pembayaranTerakhir.registrasi.tindakan.dokter',
            'pembayaranTerakhir.registrasi.tindakan.perawat',
            'pembayaranTerakhir.registrasi.tindakan.barangSatuan',
            'pembayaranTerakhir.registrasi.tindakan.barangSatuan.barang',
            'pembayaranTerakhir.registrasi.siteMarking.pengguna',
            'pembayaranTerakhir.registrasi.resepObat.pengguna',
            'pembayaranTerakhir.registrasi.resepObat.barangSatuan',
            'pembayaranTerakhir.registrasi.resepObat.barangSatuan.barang',
            'pembayaranTerakhir.registrasi.resepObat.barangSatuan.barang.kodeAkun',
            'pembayaranTerakhir.stokKeluar.barang',
            'pembayaranTerakhir.stokKeluar.barangSatuan',
            'pembayaranTerakhir.pengguna'
        )->find($id);
    }

    public function mount()
    {
        if ($this->noRm) {
            $this->dataPasien = $this->getRekamMedis($this->noRm);
        } else {
            $this->dataPasien = null;
        }
    }

    public function render()
    {
        return view('livewire.informasi.pasien.index');
    }
}
