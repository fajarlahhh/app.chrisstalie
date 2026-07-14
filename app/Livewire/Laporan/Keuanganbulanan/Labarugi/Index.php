<?php

namespace App\Livewire\Laporan\Keuanganbulanan\Labarugi;

use App\Models\KeuanganSaldo;
use App\Models\KeuanganTemplateLaporanKeuangan;
use App\Models\KodeAkun;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Exports\LaporankeuanganExport;

class Index extends Component
{
    #[Url]
    public $bulan, $tahun, $jenis = 'Bulanan';
    public $template, $kodeAkunBelumMasuk;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
        $this->tahun = $this->tahun ?: date('Y');
        $this->template = KeuanganTemplateLaporanKeuangan::where('jenis', 'Laba Rugi')->orderBy('urutan')->get();


        $kodeAkunTemplate = explode(';', implode(';', $this->template->whereNotNull('kode_akun')->pluck('kode_akun')->toArray()));
        $this->kodeAkunBelumMasuk = KodeAkun::whereIn('kategori', ['Pendapatan', 'Beban'])->detail()->whereNotIn('id', $kodeAkunTemplate)->get();
    }

    public function export()
    {
        return (new LaporankeuanganExport($this->getData(true), ($this->jenis=='Bulanan' ? $this->bulan: $this->tahun), 'labarugi', strtolower($this->jenis)))->download('labarugi' . $this->bulan . '.xls');
    }

    public function cetak()
    {
        $cetak = view('livewire.laporan.keuanganbulanan.labarugi.' . $this->jenis, [
            'cetak' => true,
            'data' => $this->getData(true),
            'bulan' => $this->bulan,
            
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData($cetak = false)
    {
        // $data = KeuanganLaporanBulanan::where('Laba Rugi')->where('periode', date('Y-m-01', strtotime($this->bulan . '-01' . ' +1 month')))->get();
        if ($this->jenis == 'Bulanan') {
            $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->bulan . '-01' . ' +1 month')))->get();

            $data = [];
            $detail = [];
            foreach ($this->template as $item) {
                $nilai = '';
                if ($item['kode_akun']) {

                    $debet = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('debet_jurnal');
                    $kredit = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('kredit_jurnal');

                    $nilai = $item['kategori'] == 'Pendapatan' ? $kredit - $debet : $debet - $kredit;
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }

                if ($item['rumus']) {
                    if (preg_match('/sum\((\d+):(\d+)\)/', $item['rumus'], $matches)) {
                        $start = intval($matches[1]);
                        $end = intval($matches[2]);
                        $nilai = array_sum(
                            array_column(
                                array_filter($detail, function ($det) use ($start, $end) {
                                    return $det['key'] >= $start && $det['key'] <= $end;
                                }),
                                'nilai'
                            )
                        );
                    }
                    if (preg_match_all('/([+-]?)\s*sum\((\d+):(\d+)\)/', $item['rumus'], $matches, PREG_SET_ORDER)) {
                        $nilai = 0;
                        foreach ($matches as $match) {
                            $operator = $match[1] ?: '+';
                            $start = intval($match[2]);
                            $end = intval($match[3]);

                            $sum = array_sum(
                                array_column(
                                    array_filter($detail, function ($det) use ($start, $end) {
                                        return $det['key'] >= $start && $det['key'] <= $end;
                                    }),
                                    'nilai'
                                )
                            );

                            if ($operator === '-') {
                                $nilai -= $sum;
                            } else {
                                $nilai += $sum;
                            }
                        }
                    }
                }

                $data[] = [
                    'nomor' => $item->nomor,
                    'kode_akun' => $item->kode_akun,
                    'uraian' => $item->uraian /*. ($item->kode_akun ? ' <small>(' . implode(', ', explode(',', $item->kode_akun)) . ')</small>' : '')*/,
                    'nilai' => $nilai == '' ? '' : (!$cetak ? number_format_id($nilai, 2) : $nilai),
                ];
            }
            return $data;
        } else {
            $data = [];
            foreach ($this->template as $index => $item) {
                $data[$index] = [
                    'nomor' => $item->nomor,
                    'kode_akun' => $item->kode_akun,
                    'uraian' => $item->uraian /*. ($item->kode_akun ? ' <small>(' . implode(', ', explode(',', $item->kode_akun)) . ')</small>' : '')*/,
                    'total_nilai' => 0,
                    'has_nilai' => false,
                ];
            }

            for ($i = 1; $i <= 12; $i++) {
                $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->tahun . "-" . $i . '-01' . ' +1 month')))->get();

                $detail = [];
                foreach ($this->template as $index => $item) {
                    $nilai = '';
                    if ($item['kode_akun']) {

                        $debet = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('debet_jurnal');
                        $kredit = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('kredit_jurnal');

                        $nilai = $item['kategori'] == 'Pendapatan' ? $kredit - $debet : $debet - $kredit;
                        $detail[] = [
                            'key' => $item['urutan'],
                            'nilai' => $nilai,
                        ];
                    }

                    if ($item['rumus']) {
                        if (preg_match('/sum\((\d+):(\d+)\)/', $item['rumus'], $matches)) {
                            $start = intval($matches[1]);
                            $end = intval($matches[2]);
                            $nilai = array_sum(
                                array_column(
                                    array_filter($detail, function ($det) use ($start, $end) {
                                        return $det['key'] >= $start && $det['key'] <= $end;
                                    }),
                                    'nilai'
                                )
                            );
                        }
                        if (preg_match_all('/([+-]?)\s*sum\((\d+):(\d+)\)/', $item['rumus'], $matches, PREG_SET_ORDER)) {
                            $nilai = 0;
                            foreach ($matches as $match) {
                                $operator = $match[1] ?: '+';
                                $start = intval($match[2]);
                                $end = intval($match[3]);

                                $sum = array_sum(
                                    array_column(
                                        array_filter($detail, function ($det) use ($start, $end) {
                                            return $det['key'] >= $start && $det['key'] <= $end;
                                        }),
                                        'nilai'
                                    )
                                );

                                if ($operator === '-') {
                                    $nilai -= $sum;
                                } else {
                                    $nilai += $sum;
                                }
                            }
                        }
                    }

                    $data[$index]['nilai_bulan_' . $i] = $nilai == '' ? '' : (!$cetak ? number_format_id($nilai, 2) : $nilai);
                    if ($nilai !== '') {
                        $data[$index]['total_nilai'] += $nilai;
                        $data[$index]['has_nilai'] = true;
                    }
                }
            }

            foreach ($data as $index => $item) {
                $data[$index]['total'] = $item['has_nilai'] ? (!$cetak ? number_format_id($item['total_nilai'], 2) : $item['total_nilai']) : '';
            }

            return array_values($data);
        }
    }

    public function render()
    {
        return view(
            'livewire.laporan.keuanganbulanan.labarugi.index',
            [
                'data' => $this->getData()
            ]
        );
    }
}
