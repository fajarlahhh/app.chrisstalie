<?php

namespace App\Livewire\Laporan\Keuanganbulanan\Neraca;

use Livewire\Component;
use App\Models\KeuanganTemplateLaporanKeuangan;
use App\Models\KeuanganSaldo;
use App\Models\KodeAkun;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $bulan, $tahun, $jenis = 'Bulanan';
    public $template;
    public $kodeAkunBelumMasuk;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
        $this->tahun = $this->tahun ?: date('Y');
        $this->template = KeuanganTemplateLaporanKeuangan::where('jenis', 'Neraca')->orderBy('urutan')->get();

        $kodeAkunTemplate = explode(';', implode(';', $this->template->whereNotNull('kode_akun')->pluck('kode_akun')->toArray()));
        $this->kodeAkunBelumMasuk = KodeAkun::whereIn('kategori', ['Aktiva', 'Kewajiban', 'Ekuitas'])->detail()->whereNotIn('id', $kodeAkunTemplate)->get();
    }

    public function cetak()
    {
        $cetak = view('livewire.laporan.keuanganbulanan.neraca.' . strtolower($this->jenis), [
            'cetak' => true,
            'dataAktiva' => $this->getDataAktiva(),
            'dataPasiva' => $this->getDataPasiva(),
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getDataAktiva()
    {
        if ($this->jenis == 'Bulanan') {
            $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->bulan . '-01' . ' +1 month')))->get();
            $data = [];
            $detail = [];
            foreach ($this->template->where('kategori', 'Aktiva')->sortBy('urutan') as $item) {
                $nilai = '';
                if ($item['kode_akun']) {
                    $debet = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('debet');
                    $kredit = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('kredit');

                    $nilai = $debet - $kredit;
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
                    'uraian' => $item->uraian,
                    'nilai' => $nilai == '' ? '' : number_format_id($nilai, 2),
                ];
            }
            return $data;
        } else {
            $data = [];
            $templateData = $this->template->where('kategori', 'Aktiva')->sortBy('urutan');
            foreach ($templateData as $index => $item) {
                $data[$index] = [
                    'nomor' => $item->nomor,
                    'kode_akun' => $item->kode_akun,
                    'uraian' => $item->uraian,
                    'total_nilai' => 0,
                    'has_nilai' => false,
                ];
            }

            for ($i = 1; $i <= 12; $i++) {
                $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->tahun . "-" . $i . '-01' . ' +1 month')))->get();
                $detail = [];
                foreach ($templateData as $index => $item) {
                    $nilai = '';
                    if ($item['kode_akun']) {
                        $debet = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('debet');
                        $kredit = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('kredit');

                        $nilai = $debet - $kredit;
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

                    $data[$index]['nilai_bulan_' . $i] = $nilai == '' ? '' : number_format_id($nilai, 2);
                    if ($nilai !== '') {
                        $data[$index]['total_nilai'] += $nilai;
                        $data[$index]['has_nilai'] = true;
                    }
                }
            }

            return array_values($data);
        }
    }

    public function getDataPasiva()
    {
        if ($this->jenis == 'Bulanan') {
            $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->bulan . '-01' . ' +1 month')))->get();
            $data = [];
            $detail = [];
            foreach ($this->template->where('kategori', '!=', 'Aktiva')->sortBy('urutan') as $item) {
                $nilai = '';
                if ($item['kode_akun']) {
                    $debet = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('debet');
                    $kredit = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('kredit');

                    $nilai = $kredit - $debet;
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
                    'uraian' => $item->uraian,
                    'nilai' => $nilai == '' ? '' : number_format_id($nilai, 2),
                ];
            }
            return $data;
        } else {
            $data = [];
            $templateData = $this->template->where('kategori', '!=', 'Aktiva')->sortBy('urutan');
            foreach ($templateData as $index => $item) {
                $data[$index] = [
                    'nomor' => $item->nomor,
                    'kode_akun' => $item->kode_akun,
                    'uraian' => $item->uraian,
                    'total_nilai' => 0,
                    'has_nilai' => false,
                ];
            }

            for ($i = 1; $i <= 12; $i++) {
                $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->tahun . "-" . $i . '-01' . ' +1 month')))->get();
                $detail = [];
                foreach ($templateData as $index => $item) {
                    $nilai = '';
                    if ($item['kode_akun']) {
                        $debet = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('debet');
                        $kredit = $saldo->whereIn('kode_akun_id', explode(';', $item['kode_akun']))->sum('kredit');

                        $nilai = $kredit - $debet;
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

                    $data[$index]['nilai_bulan_' . $i] = $nilai == '' ? '' : number_format_id($nilai, 2);
                    if ($nilai !== '') {
                        $data[$index]['total_nilai'] += $nilai;
                        $data[$index]['has_nilai'] = true;
                    }
                }
            }

            return array_values($data);
        }
    }

    public function render()
    {
        return view('livewire.laporan.keuanganbulanan.neraca.index', [
            'dataAktiva' => $this->getDataAktiva(),
            'dataPasiva' => $this->getDataPasiva(),
        ]);
    }
}
