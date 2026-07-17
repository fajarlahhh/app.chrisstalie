@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Kunjungan Pasien</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $tanggal1 }} s/d {{ $tanggal2 }}</td>
        </tr>
        @if ($jenis == 'perpasien')
            <tr>
                <th class="w-100px">Jenis</th>
                <th class="w-10px">:</th>
                <td>Per Pasien</td>
            </tr>
        @else
            <tr>
                <th class="w-100px">Jenis</th>
                <th class="w-10px">:</th>
                <td>Per Tanggal</td>
            </tr>
        @endif
    </table>
@endif
@if ($jenis == 'perpasien')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="bg-gray-300 text-white">No.</th>
                <th class="bg-gray-300 text-white">No. RM</th>
                <th class="bg-gray-300 text-white">Nama Pasien</th>
                <th class="bg-gray-300 text-white">Alamat</th>
                <th class="bg-gray-300 text-white">Jenis Kelamin</th>
                <th class="bg-gray-300 text-white">Qty</th>
                <th class="bg-gray-300 text-white">Total Biaya</th>
                <th class="bg-gray-300 text-white">Tindakan</th>
                <th class="bg-gray-300 text-white">Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['alamat'] }}</td>
                    <td>{{ $row['jenis_kelamin'] }}</td>
                    <td class="text-end">{{ $cetak ? $row['qty'] : number_format_id($row['qty']) }}</td>
                    <td class="text-end">{{ $cetak ? $row['biaya'] : number_format_id($row['biaya'], 2) }}</td>
                    <td nowrap>{!! $row['tindakan'] !!}</td>
                    <td nowrap>{!! $row['barang'] !!}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="5">TOTAL</th>
                <th class="text-end">
                    {{ $cetak ? collect($data)->sum('qty') : number_format_id(collect($data)->sum('qty')) }}</th>
                <th class="text-end">
                    {{ $cetak ? collect($data)->sum('biaya') : number_format_id(collect($data)->sum('biaya'), 2) }}
                </th>
                <th colspan="2"></th>
            </tr>
        </tbody>
    </table>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="bg-gray-300 text-white">No.</th>
                <th class="bg-gray-300 text-white">Tanggal</th>
                <th class="bg-gray-300 text-white">Jumlah Pasien</th>
                <th class="bg-gray-300 text-white">Pendapatan</th>
                {{-- <th class="bg-gray-300 text-white">Tindakan</th>
                <th class="bg-gray-300 text-white">Barang</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['tanggal'] }}</td>
                    <td class="text-end">{{ $cetak ? $row['qty'] : number_format_id($row['qty']) }}</td>
                    <td class="text-end">{{ $cetak ? $row['biaya'] : number_format_id($row['biaya'], 2) }}</td>
                    {{-- <td nowrap>{!! $row['tindakan'] !!}</td>
                    <td nowrap>{!! $row['barang'] !!}</td> --}}
                </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTAL</th>
                <th class="text-end">
                    {{ $cetak ? collect($data)->sum('qty') : number_format_id(collect($data)->sum('qty')) }}</th>
                <th class="text-end">
                    {{ $cetak ? collect($data)->sum('biaya') : number_format_id(collect($data)->sum('biaya'), 2) }}
                </th>
                {{-- <th colspan="2"></th> --}}
            </tr>
        </tbody>
    </table>
@endif
