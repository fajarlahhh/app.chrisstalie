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
    </table>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white" rowspan="2">No.</th>
            <th class="bg-gray-300 text-white" rowspan="2">No. Nota</th>
            <th class="bg-gray-300 text-white" rowspan="2">Nama</th>
            <th class="bg-gray-300 text-white" rowspan="2">Alamat</th>
            <th class="bg-gray-300 text-white" rowspan="2">Jenis Kelamin</th>
            <th class="bg-gray-300 text-white" rowspan="2">Tindakan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Resep</th>
            <th class="bg-gray-300 text-white" rowspan="2">Penjualan Barang</th>
            <th class="bg-gray-300 text-white" rowspan="2">Total Sebelum Diskon</th>
            <th class="bg-gray-300 text-white" rowspan="2">Diskon</th>
            <th class="bg-gray-300 text-white" rowspan="2">Total Setelah Diskon</th>
            <th class="bg-gray-300 text-white" colspan="{{ $data->pluck('metode_bayar')->unique()->count() }}">Metode Bayar</th>
            @role('administrator|supervisor')
                <th class="bg-gray-300 text-white" rowspan="2">Kasir</th>
            @endrole
            <th class="bg-gray-300 text-white" rowspan="2">Keterangan</th>
        </tr>
        <tr>
            @foreach ($data->pluck('metode_bayar')->unique() as $item)
                <th class="bg-gray-300 text-white">{{ $item }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
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
                <td class="text-end">{{ $cetak ? $row['total_tindakan'] : number_format_id($row['total_tindakan']) }}</td>
                <td class="text-end">{{ $cetak ? $row['total_resep'] : number_format_id($row['total_resep']) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_barang'] : number_format_id($row['total_barang']) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_tindakan'] + $row['total_resep'] + $row['total_barang'] : number_format_id($row['total_tindakan'] + $row['total_resep'] + $row['total_barang']) }}
                </td>
                <td class="text-end">{{ $cetak ? $diskon : number_format_id($diskon) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_tagihan'] : number_format_id($row['total_tagihan']) }}
                </td>
                @foreach ($data->pluck('metode_bayar')->unique() as $item)
                    <td class="text-end" nowrap>
                        @if ($row['metode_bayar'] == $item)
                            {{ $cetak ? ($row['bayar'] - $row['selisih']) : number_format_id($row['bayar'] - $row['selisih']) }}
                        @endif
                        @if ($row['metode_bayar_2'] == $item)
                            {{ $cetak ? ($row['bayar_2']) : number_format_id($row['bayar_2']) }}
                        @endif
                    </td>
                @endforeach
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
                {{ $cetak ? $data->sum('total_tindakan') : number_format_id($data->sum('total_tindakan')) }}
            </th>
            <th class="text-end">{{ $cetak ? $data->sum('total_resep') : number_format_id($data->sum('total_resep')) }}
            </th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_barang') : number_format_id($data->sum('total_barang')) }}</th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_tindakan') + $data->sum('total_resep') + $data->sum('total_barang') : number_format_id($data->sum('total_tindakan') + $data->sum('total_resep') + $data->sum('total_barang')) }}
            </th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_diskon_barang') + $data->sum('total_diskon_tindakan') + $data->sum('diskon') : number_format_id($data->sum('total_diskon_barang') + $data->sum('total_diskon_tindakan') + $data->sum('diskon')) }}
            </th>
            <th class="text-end">
                {{ $cetak ? $data->sum('total_tagihan') : number_format_id($data->sum('total_tagihan')) }}
            </th>
            @foreach ($data->pluck('metode_bayar')->unique() as $item)
                <th class="text-end">
                    {{ $cetak ? $data->where('metode_bayar', $item)->sum(fn($row) => $row['bayar'] - $row['selisih']) + $data->where('metode_bayar_2', $item)->sum(fn($row) => $row['bayar_2']) : number_format_id($data->where('metode_bayar', $item)->sum(fn($row) => $row['bayar'] - $row['selisih']) + $data->where('metode_bayar_2', $item)->sum(fn($row) => $row['bayar_2'])) }}
                </th>
            @endforeach
            @role('administrator|supervisor')
                <th colspan="3"></th>
            @endrole
            @role('operator')
                <th colspan="2"></th>
            @endrole
        </tr>
    </tfoot>
</table>
