@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Harian Kas</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $tanggal }}</td>
        </tr>
        <tr>
            <th class="w-100px">Pengguna</th>
            <th class="w-10px">:</th>
            <td>{{ $pengguna }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white">No.</th>
            <th class="bg-gray-300 text-white">Uraian</th>
            <th class="bg-gray-300 text-white">Jumlah</th>
            <th class="bg-gray-300 text-white">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="w-10px">1.</th>
            <td colspan="3">Pemasukkan</td>
        </tr>
        {{-- 1a. Pemasukkan Kunjungan --}}
        <tr>
            <td></td>
            <td colspan="3"><strong>a. Pendapatan</strong></td>
        </tr>
        @foreach ($dataKunjungan->groupBy('metode_bayar') as $index => $row)
            <tr>
                <td></td>
                <td>&nbsp;&nbsp;&nbsp;- Pendapatan {{ $index }}</td>
                <td class="text-end">{{ number_format_id($row->sum('total_tagihan')) }}</td>
                <td>
                    Diskon : {{ number_format_id($row->sum(fn($q) => $q['total_diskon_barang'] + $q['total_diskon_tindakan'] + $q['total_diskon_resep'] + $q['diskon'])) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <th>Total Pendapatan</th>
            <th class="text-end">{{ number_format_id($dataKunjungan->sum('total_tagihan')) }}</th>
            <th>Total Diskon : {{ number_format_id($dataKunjungan->sum(fn($q) => $q['total_diskon_barang'] + $q['total_diskon_tindakan'] + $q['total_diskon_resep'] + $q['diskon'])) }}</th>
        </tr>
        {{-- 1b. Pemasukkan Pembelian Prabayar --}}
        <tr>
            <td></td>
            <td colspan="3"><strong>b. Pembelian Prabayar</strong></td>
        </tr>
        @foreach ($dataPrabayar->groupBy('metode_bayar') as $index => $row)
            <tr>
                <td></td>
                <td>&nbsp;&nbsp;&nbsp;- Prabayar {{ $index }}</td>
                <td class="text-end">{{ number_format_id($row->sum('total_tagihan')) }}</td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <th>Total Prabayar</th>
            <th class="text-end">{{ number_format_id($dataPrabayar->sum('total_tagihan')) }}</th>
            <th></th>
        </tr>
        @php $totalPemasukkan = $dataKunjungan->sum('total_tagihan') + $dataPrabayar->sum('total_tagihan'); @endphp
        <tr>
            <th></th>
            <th>Total Pemasukkan</th>
            <th class="text-end">{{ number_format_id($totalPemasukkan) }}</th>
            <th>Total Diskon : {{ number_format_id($dataKunjungan->sum(fn($q) => $q['total_diskon_barang'] + $q['total_diskon_tindakan'] + $q['total_diskon_resep'] + $q['diskon'])) }}</th>
        </tr>
        <tr>
            <td class="w-10px">2.</td>
            <td colspan="3">Pengeluaran</td>
        </tr>
        @php
            $totalPengeluaran = 0;
        @endphp
        @foreach (\App\Models\KodeAkun::where('parent_id', '11100')->whereIn('id', $dataPengeluaran->where('kredit', '>', 0)->unique('kode_akun_id')->pluck('kode_akun_id')->toArray())->get() as $item)
            <tr>
                <td></td>
                <td>{{ $item['nama'] }}</td>
                <td class="text-end"></td>
                <td>
                </td>
            </tr>
            @foreach ($dataPengeluaran->where('kode_akun_id', $item['id']) as $item)
                @php
                    $pengeluaran = $dataPengeluaran->where('id', $item['id'])->where('debet', '>', 0);
                    $totalPengeluaran += $pengeluaran->sum('debet');
                @endphp
                <tr>
                    <td></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;{{ $pengeluaran->first()['kode_akun_nama'] }}</td>
                    <td class="text-end">{{ number_format_id($pengeluaran->sum('debet')) }}</td>
                    <td>
                        {{ $item->uraian }}
                    </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <th></th>
            <th>Total Pengeluaran</th>
            <th class="text-end">{{ number_format_id($totalPengeluaran) }}
            </th>
        </tr>
        <tr>
            <td colspan="4">
                REKAPITULASI KEUANGAN : <br>
                <table class="ms-20px">
                    <tr>
                        <td class="w-200px">Total Pemasukkan</td>
                        <td class="w-10px">:</td>
                        <td class="text-end">{{ number_format_id($totalPemasukkan) }}</td>
                    </tr>
                    <tr>
                        <td>Total Pengeluaran</td>
                        <td>: </td>
                        <td class="text-end">
                            {{ number_format_id($totalPengeluaran) }}</td>
                    </tr>
                    <tr>
                        <td>Total Keuntungan</td>
                        <td>: </td>
                        <td class="text-end">
                            {{ number_format_id($totalPemasukkan - $totalPengeluaran) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
