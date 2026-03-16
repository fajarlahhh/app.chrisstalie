@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Penerimaan</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $tanggal1 }} s/d {{ $tanggal2 }}</td>
        </tr>
        <tr>
            <th class="w-100px">Kasir</th>
            <th class="w-10px">:</th>
            <td>{{ $pengguna ? $pengguna : 'Semua Kasir' }}</td>
        </tr>
        <tr>
            <th class="w-100px">Metode Bayar</th>
            <th class="w-10px">:</th>
            <td>{{ $metode_bayar ? $metode_bayar : 'Semua Metode Bayar' }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white">No.</th>
            <th class="bg-gray-300 text-white">No. Nota</th>
            <th class="bg-gray-300 text-white">Nama</th>
            <th class="bg-gray-300 text-white">Alamat</th>
            <th class="bg-gray-300 text-white">Jenis Kelamin</th>
            <th class="bg-gray-300 text-white">Tindakan</th>
            <th class="bg-gray-300 text-white">Resep</th>
            <th class="bg-gray-300 text-white">Penjualan Barang</th>
            <th class="bg-gray-300 text-white">Total Sebelum Diskon</th>
            <th class="bg-gray-300 text-white">Diskon</th>
            <th class="bg-gray-300 text-white">Total Setelah Diskon</th>
            @if ($metode_bayar)
                <th class="bg-gray-300 text-white" nowrap>{{ $metode_bayar }}</th>
            @else
                <th class="bg-gray-300 text-white" nowrap>Metode Bayar 1</th>
                <th class="bg-gray-300 text-white" nowrap>Metode Bayar 2</th>
            @endif
            @role('administrator|supervisor')
                <th class="bg-gray-300 text-white">Kasir</th>
            @endrole
            <th class="bg-gray-300 text-white">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_bayar_1 = 0;
            $total_bayar_2 = 0;
        @endphp
        @foreach ($data as $row)
            @php
                $diskon = $row['total_diskon_barang'] + $row['total_diskon_tindakan'] + $row['diskon'];
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row['id'] }}</td>
                <td>
                    {{ isset($row['registrasi']) && isset($row['registrasi']['pasien']) && isset($row['registrasi']['pasien']['nama']) ? $row['registrasi']['pasien']['nama'] : '' }}
                </td>
                <td>
                    {{ isset($row['registrasi']) && isset($row['registrasi']['pasien']) && isset($row['registrasi']['pasien']['alamat']) ? $row['registrasi']['pasien']['alamat'] : '' }}
                </td>
                <td>
                    {{ isset($row['registrasi']) && isset($row['registrasi']['pasien']) && isset($row['registrasi']['pasien']['jenis_kelamin']) ? $row['registrasi']['pasien']['jenis_kelamin'] : '' }}
                </td>
                <td class="text-end">{{ $cetak ? $row['total_tindakan'] : number_format($row['total_tindakan']) }}</td>
                <td class="text-end">{{ $cetak ? $row['total_resep'] : number_format($row['total_resep']) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_barang'] : number_format($row['total_barang']) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_tindakan'] + $row['total_resep'] + $row['total_barang'] : number_format($row['total_tindakan'] + $row['total_resep'] + $row['total_barang']) }}
                </td>
                <td class="text-end">{{ $cetak ? $diskon : number_format($diskon) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_tagihan'] : number_format($row['total_tagihan']) }}
                </td>
                @if ($metode_bayar)
                    <td class="text-end" nowrap>
                        @if ($metode_bayar == $row['metode_bayar'])
                            {{ $cetak ? $row['bayar'] : number_format($row['bayar']) }}
                            @php
                                $total_bayar_1 += $row['bayar'];
                            @endphp
                        @else
                            {{ $cetak ? $row['bayar_2'] : number_format($row['bayar_2']) }}
                            @php
                                $total_bayar_2 += $row['bayar_2'];
                            @endphp
                        @endif
                    </td>
                @else
                    <td nowrap>
                        {{ $cetak ? ($row['metode_bayar'] ? $row['metode_bayar'] . ' : ' . $row['bayar'] : '') : ($row['metode_bayar'] ? $row['metode_bayar'] . ' : ' . number_format($row['bayar']) : '') }}
                        @php
                            $total_bayar_1 += $row['bayar'];
                        @endphp
                    </td>
                    <td nowrap>
                        {{ $cetak ? ($row['metode_bayar_2'] ? $row['metode_bayar_2'] . ' : ' . $row['bayar_2'] : '') : ($row['metode_bayar_2'] ? $row['metode_bayar_2'] . ' : ' . number_format($row['bayar_2']) : '') }}
                        @php
                            $total_bayar_2 += $row['bayar_2'];
                        @endphp
                    </td>
                @endif
                @role('administrator|supervisor')
                    <td>{{ $row['pengguna']['nama'] }}</td>
                @endrole
                <td>{{ $row['keterangan'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">Total</th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_tindakan') : number_format($data->sum('total_tindakan')) }}
            </th>
            <th class="text-end">{{ $cetak ? $data->sum('total_resep') : number_format($data->sum('total_resep')) }}
            </th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_barang') : number_format($data->sum('total_barang')) }}</th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_tindakan') + $data->sum('total_resep') + $data->sum('total_barang') : number_format($data->sum('total_tindakan') + $data->sum('total_resep') + $data->sum('total_barang')) }}
            </th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_diskon_barang') + $data->sum('total_diskon_tindakan') + $data->sum('diskon') : number_format($data->sum('total_diskon_barang') + $data->sum('total_diskon_tindakan') + $data->sum('diskon')) }}
            </th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_tagihan') : number_format($data->sum('total_tagihan')) }}
            </th>
            @if (!$metode_bayar)
                <th class="text-end">
                    {{ $cetak ? $total_bayar_1 : number_format($total_bayar_1) }}
                </th>
                <th class="text-end">
                    {{ $cetak ? $total_bayar_2 : number_format($total_bayar_2) }}
                </th>
            @else
                <th class="text-end">
                    {{ $cetak ? $total_bayar_1 + $total_bayar_2 : number_format($total_bayar_1 + $total_bayar_2) }}
                </th>
            @endif
            @role('administrator|supervisor')
                <th colspan="3"></th>
            @endrole
            @role('operator')
                <th colspan="2"></th>
            @endrole
        </tr>
    </tfoot>
</table>
