<?php

namespace App\Livewire\Laporan\Penyusutanaset;

use App\Models\Aset;
use Livewire\Component;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaoranpenyusutanasetExport;

class Index extends Component
{
    #[Url]
    public $tahun;

    public function mount()
    {
        $this->tahun = date('Y');
    }

    public function export()
    {
        return Excel::download(new LaoranpenyusutanasetExport(
            $this->getData(),
            $this->tahun
        ), 'penyusutanaset.xlsx');
    }

    public function getData()
    {
        return Aset::with('pengguna', 'kodeAkun', 'kodeAkunSumberDana', 'keuanganJurnal')->where('metode_penyusutan', 'Garis Lurus')->orderBy('nama')->where('tanggal_perolehan', '<=', $this->tahun . '-12-31')->get()->map(function ($row) {
            $bulanPerolehan = str_replace('-', '', substr($row->tanggal_perolehan, 0, 7));
            $bulanTerminasi = str_replace('-', '', substr($row->tanggal_terminasi, 0, 7));
            return [
                'nama' => $row->nama,
                'kategori' => $row->kodeAkun->nama,
                'metode_penyusutan' => $row->metode_penyusutan,
                'tanggal_perolehan' => $row->tanggal_perolehan,
                'masa_manfaat' => $row->masa_manfaat,
                'tanggal_terminasi' => $row->tanggal_terminasi,
                'harga_perolehan' => $row->harga_perolehan,
                'nilai_residu' => $row->nilai_residu,
                'nilai_penyusutan_1' => $bulanPerolehan <= $this->tahun . '01' && $bulanTerminasi >= $this->tahun . '01' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_2' => $bulanPerolehan <= $this->tahun . '02' && $bulanTerminasi >= $this->tahun . '02' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_3' => $bulanPerolehan <= $this->tahun . '03' && $bulanTerminasi >= $this->tahun . '03' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_4' => $bulanPerolehan <= $this->tahun . '04' && $bulanTerminasi >= $this->tahun . '04' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_5' => $bulanPerolehan <= $this->tahun . '05' && $bulanTerminasi >= $this->tahun . '05' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_6' => $bulanPerolehan <= $this->tahun . '06' && $bulanTerminasi >= $this->tahun . '06' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_7' => $bulanPerolehan <= $this->tahun . '07' && $bulanTerminasi >= $this->tahun . '07' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_8' => $bulanPerolehan <= $this->tahun . '08' && $bulanTerminasi >= $this->tahun . '08' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_9' => $bulanPerolehan <= $this->tahun . '09' && $bulanTerminasi >= $this->tahun . '09' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_10' => $bulanPerolehan <= $this->tahun . '10' && $bulanTerminasi >= $this->tahun . '10' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_11' => $bulanPerolehan <= $this->tahun . '11' && $bulanTerminasi >= $this->tahun . '11' ? $row->nilai_penyusutan : 0,
                'nilai_penyusutan_12' => $bulanPerolehan <= $this->tahun . '12' && $bulanTerminasi >= $this->tahun . '12' ? $row->nilai_penyusutan : 0,
            ];
        });
    }

    public function render()
    {
        return view('livewire.laporan.penyusutanaset.index', [
            'data' => $this->getData(),
        ]);
    }
}
