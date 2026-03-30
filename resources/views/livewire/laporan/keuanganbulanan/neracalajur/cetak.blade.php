@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Neraca Lajur</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $bulan }}</td>
        </tr>
    </table>
@endif

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th rowspan="2">KODE AKUN</th>
            <th colspan="2">NERACA SALDO</th>
            <th colspan="2">MUTASI</th>
            <th colspan="2">LABA RUGI</th>
            <th colspan="2">NERACA</th>
        </tr>
        <tr>
            <th>DEBET</th>
            <th>KREDIT</th>
            <th>DEBET</th>
            <th>KREDIT</th>
            <th>DEBET</th>
            <th>KREDIT</th>
            <th>DEBET</th>
            <th>KREDIT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td class="text-nowrap">{{ $row['id'] }}</td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['saldo_debet'] }}
                    @else
                        {{ number_format_id($row['saldo_debet'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['saldo_kredit'] }}
                    @else
                        {{ number_format_id($row['saldo_kredit'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['jurnal_debet'] }}
                    @else
                        {{ number_format_id($row['jurnal_debet'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['jurnal_kredit'] }}
                    @else
                        {{ number_format_id($row['jurnal_kredit'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['laba_rugi_debet'] }}
                    @else
                        {{ number_format_id($row['laba_rugi_debet'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['laba_rugi_kredit'] }}
                    @else
                        {{ number_format_id($row['laba_rugi_kredit'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['necara_debet'] }}
                    @else
                        {{ number_format_id($row['necara_debet'], 2) }}
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if ($cetak)
                        {{ $row['necara_kredit'] }}
                    @else
                        {{ number_format_id($row['necara_kredit'], 2) }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>TOTAL</th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('saldo_debet') }}
                @else
                    {{ number_format_id(collect($data)->sum('saldo_debet'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('saldo_kredit') }}
                @else
                    {{ number_format_id(collect($data)->sum('saldo_kredit'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('jurnal_debet') }}
                @else
                    {{ number_format_id(collect($data)->sum('jurnal_debet'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('jurnal_kredit') }}
                @else
                    {{ number_format_id(collect($data)->sum('jurnal_kredit'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('laba_rugi_debet') }}
                @else
                    {{ number_format_id(collect($data)->sum('laba_rugi_debet'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('laba_rugi_kredit') }}
                @else
                    {{ number_format_id(collect($data)->sum('laba_rugi_kredit'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('necara_debet') }}
                @else
                    {{ number_format_id(collect($data)->sum('necara_debet'), 2) }}
                @endif
            </th>
            <th class="text-end text-nowrap">
                @if ($cetak)
                    {{ collect($data)->sum('necara_kredit') }}
                @else
                    {{ number_format_id(collect($data)->sum('necara_kredit'), 2) }}
                @endif
            </th>
        </tr>
    </tfoot>
</table>
