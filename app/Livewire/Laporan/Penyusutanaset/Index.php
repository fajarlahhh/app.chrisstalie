<?php

namespace App\Livewire\Laporan\Penyusutanaset;

use App\Exports\LaoranpenyusutanasetExport;
use App\Models\Aset;
use App\Models\AsetPenyusutan;
use App\Models\TindakanAlatBarang;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    #[Url]
    public $tahun;

    public function mount()
    {
        $this->tahun = $this->tahun ?? date('Y');
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
        $asetPenyusutan = TindakanAlatBarang::whereNotNull('aset_id')->select(
            'aset_id',
            DB::raw('DATE_FORMAT(tindakan.created_at, "%Y-%m") as bulan'),
            DB::raw('SUM(tindakan_alat_barang.biaya * tindakan_alat_barang.qty) as nilai')
        )->leftJoin('tindakan', 'tindakan.id', '=', 'tindakan_id')
            ->whereYear('tindakan.created_at', $this->tahun)->groupBy('aset_id', 'bulan')
            ->get()->map(function ($q) {
                return [
                    'aset_id' => $q->aset_id,
                    'bulan' => $q->bulan,
                    'nilai' => $q->nilai,
                ];
            });
        return Aset::with('pengguna', 'kodeAkun', 'kodeAkunSumberDana', 'keuanganJurnal')->orderBy('nama')->where('tanggal_perolehan', '<=', $this->tahun . '-12-31')->get()->map(function ($row) use ($asetPenyusutan) {
            $bulanPerolehan = str_replace('-', '', substr($row->tanggal_perolehan, 0, 7));
            $bulanTerminasi = str_replace('-', '', substr($row->tanggal_terminasi, 0, 7));
            if ($row->metode_penyusutan == 'Garis Lurus') {
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
            } else {
                return [
                    'nama' => $row->nama,
                    'kategori' => $row->kodeAkun->nama,
                    'metode_penyusutan' => $row->metode_penyusutan,
                    'tanggal_perolehan' => $row->tanggal_perolehan,
                    'masa_manfaat' => $row->masa_manfaat,
                    'tanggal_terminasi' => $row->tanggal_terminasi,
                    'harga_perolehan' => $row->harga_perolehan,
                    'nilai_residu' => $row->nilai_residu,
                    'nilai_penyusutan_1' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-01')->sum('nilai'),
                    'nilai_penyusutan_2' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-02')->sum('nilai'),
                    'nilai_penyusutan_3' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-03')->sum('nilai'),
                    'nilai_penyusutan_4' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-04')->sum('nilai'),
                    'nilai_penyusutan_5' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-05')->sum('nilai'),
                    'nilai_penyusutan_6' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-06')->sum('nilai'),
                    'nilai_penyusutan_7' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-07')->sum('nilai'),
                    'nilai_penyusutan_8' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-08')->sum('nilai'),
                    'nilai_penyusutan_9' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-09')->sum('nilai'),
                    'nilai_penyusutan_10' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-10')->sum('nilai'),
                    'nilai_penyusutan_11' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-11')->sum('nilai'),
                    'nilai_penyusutan_12' => $asetPenyusutan->where('aset_id', $row->id)->where('bulan', $this->tahun . '-12')->sum('nilai'),
                ];
            }
        });
    }

    public function render()
    {
        return view('livewire.laporan.penyusutanaset.index', [
            'data' => $this->getData(),
        ]);
    }
}
