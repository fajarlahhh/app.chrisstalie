<?php

namespace App\Livewire\Laporan\Keuanganbulanan\Aruskas;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\KeuanganTemplateLaporanKeuangan;
use App\Models\KeuanganSaldo;
use App\Models\KodeAkun;

class Index extends Component
{
    #[Url]
    public $bulan;
    public $template;

    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
        $this->template = KeuanganTemplateLaporanKeuangan::where('jenis', 'Arus Kas')->orderBy('urutan')->get();
    }

    public function cetak()
    {
        $cetak = view('livewire.laporan.keuanganbulanan.aruskas.cetak', [
            'cetak' => true,
            'data' => $this->getData(),
            'bulan' => $this->bulan,
        ])->render();
        session()->flash('cetak', $cetak);
    }

    public function getData()
    {
        $saldo = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->bulan . '-01' . ' +1 month')))->get();
        $saldoAwal = KeuanganSaldo::where('periode', date('Y-m-01', strtotime($this->bulan . '-01')))->get();
        $dataKodeAkun = KodeAkun::where('detail', 1)->get();
        $data = [];
        $detail = [];
        foreach ($this->template as $item) {
            $nilai = '';
            if ($item['kode_akun']) {
                if ($item['kategori'] == 'Mutasi') {
                    $nilai = 0;
                    foreach (explode(';', $item['kode_akun']) as $kodeAkun) {
                        $debet = $saldo->where('kode_akun_id', $kodeAkun)->sum('debet_jurnal');
                        $kredit = $saldo->where('kode_akun_id', $kodeAkun)->sum('kredit_jurnal');
                        $kodeAkun = $dataKodeAkun->where('id', $kodeAkun)->first();
                        if (in_array($kodeAkun->kategori, ['Aktiva', 'Beban'])) {
                            $nilai += $debet - $kredit;
                        }
                        if (in_array($kodeAkun->kategori, ['Kewajiban', 'Ekuitas', 'Pendapatan'])) {
                            $nilai += $kredit - $debet;
                        }
                    }
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }

                if ($item['kategori'] == 'Mutasi (-)') {
                    $nilai = 0;
                    foreach (explode(';', $item['kode_akun']) as $kodeAkun) {
                        $debet = $saldo->where('kode_akun_id', $kodeAkun)->sum('debet_jurnal');
                        $kredit = $saldo->where('kode_akun_id', $kodeAkun)->sum('kredit_jurnal');
                        $kodeAkun = $dataKodeAkun->where('id', $kodeAkun)->first();
                        if (in_array($kodeAkun->kategori, ['Aktiva', 'Beban'])) {
                            $nilai += ($debet - $kredit) * -1;
                        }
                        if (in_array($kodeAkun->kategori, ['Kewajiban', 'Ekuitas', 'Pendapatan'])) {
                            $nilai += ($kredit - $debet) * -1;
                        }
                    }
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }

                if ($item['kategori'] == 'Debet') {
                    $nilai = 0;
                    foreach (explode(';', $item['kode_akun']) as $kodeAkun) {
                        $nilai +=  $saldo->where('kode_akun_id', $kodeAkun)->sum('debet_jurnal');
                    }
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }

                if ($item['kategori'] == 'Kredit') {
                    $nilai = 0;
                    foreach (explode(';', $item['kode_akun']) as $kodeAkun) {
                        $nilai +=  $saldo->where('kode_akun_id', $kodeAkun)->sum('kredit_jurnal');
                    }
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }

                if ($item['kategori'] == 'Saldo Awal') {
                    $nilai = 0;
                    foreach (explode(';', $item['kode_akun']) as $kodeAkun) {
                        $debet = $saldoAwal->where('kode_akun_id', $kodeAkun)->sum('debet');
                        $kredit = $saldoAwal->where('kode_akun_id', $kodeAkun)->sum('kredit');
                        $firstChar = substr($kodeAkun, 0, 1);
                        if (in_array($firstChar, ['4', '2', '3'])) {
                            $nilai += $kredit - $debet;
                        }
                        if (in_array($firstChar, ['1', '5', '6', '7'])) {
                            $nilai += $debet - $kredit;
                        }
                    }
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }

                if ($item['kategori'] == 'Saldo Akhir') {
                    $nilai = 0;
                    foreach (explode(';', $item['kode_akun']) as $kodeAkun) {
                        $debet = $saldo->where('kode_akun_id', $kodeAkun)->sum('debet');
                        $kredit = $saldo->where('kode_akun_id', $kodeAkun)->sum('kredit');
                        $kodeAkun = $dataKodeAkun->where('id', $kodeAkun)->first();
                        if (in_array($kodeAkun->kategori, ['Aktiva', 'Beban'])) {
                            $nilai += $kredit - $debet;
                        }
                        if (in_array($kodeAkun->kategori, ['Kewajiban', 'Ekuitas', 'Pendapatan'])) {
                            $nilai += $debet - $kredit;
                        }
                    }
                    $detail[] = [
                        'key' => $item['urutan'],
                        'nilai' => $nilai,
                    ];
                }
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
                // Cek jika rumus memiliki format 'sum(x:y) - sum(a:b)'
                // Parsing rumus yang lebih dinamis: bisa support operasi penjumlahan dan pengurangan bertingkat pada rumus, contoh: sum(62:63) - sum(65:66) + sum(30:57)
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
                'nilai' => $nilai == '' ? '' : ($nilai < 0 ? '(' . number_format_id($nilai * -1, 2) . ')' : number_format_id($nilai, 2)),
            ];
        }
        return $data;
    }

    public function render()
    {
        return view(
            'livewire.laporan.keuanganbulanan.aruskas.index',
            [
                'data' => $this->getData()
            ]
        );
    }
}
