@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Penyusutan Aset</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $tahun }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white" rowspan="2">No.</th>
            <th class="bg-gray-300 text-white" rowspan="2">Nama</th>
            <th class="bg-gray-300 text-white" rowspan="2">Kategori</th>
            <th class="bg-gray-300 text-white" rowspan="2">Metode Penyusutan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Tanggal Perolehan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Masa Manfaat</th>
            <th class="bg-gray-300 text-white" rowspan="2">Tanggal Terminasi</th>
            <th class="bg-gray-300 text-white" rowspan="2">Harga Perolehan</th>
            <th class="bg-gray-300 text-white" colspan="12">Nilai Penyusutan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Nilai Buku Akhir Periode</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= 12; $i++)
                <th class="bg-gray-300 text-white">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['kategori'] }}</td>
                <td>{{ $row['metode_penyusutan'] }}</td>
                <td nowrap>{{ $row['tanggal_perolehan'] }}</td>
                <td class="text-end">{{ $cetak ? $row['masa_manfaat'] : number_format_id($row['masa_manfaat']) }}</td>
                <td nowrap>{{ $row['tanggal_terminasi'] }}</td>
                <td class="text-end">{{ $cetak ? $row['harga_perolehan'] : number_format_id($row['harga_perolehan']) }}</td>
                @php
                    $nilaiPenyusutan = 0;
                @endphp
                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $nilaiPenyusutan += $row['nilai_penyusutan_' . $i];
                    @endphp
                    <td class="text-end">{{ $cetak ? $row['nilai_penyusutan_' . $i] : number_format_id($row['nilai_penyusutan_' . $i], 2) }}</td>
                @endfor
                <td class="text-end">{{ $cetak ? $row['harga_perolehan'] - $nilaiPenyusutan : number_format_id($row['harga_perolehan'] - $nilaiPenyusutan) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="7">Total</th>
            <th class="text-end">{{ $cetak ? $data->sum('harga_perolehan') : number_format_id($data->sum('harga_perolehan')) }}</th>
            @php
                $nilaiPenyusutan = 0;
            @endphp
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $nilaiPenyusutan += $data->sum('nilai_penyusutan_' . $i);
                @endphp
                <th class="text-end">{{ $cetak ? $data->sum('nilai_penyusutan_' . $i) : number_format_id($data->sum('nilai_penyusutan_' . $i), 2) }}</th>
            @endfor
            <th class="text-end">
                {{ $cetak ? $data->sum('harga_perolehan') - $nilaiPenyusutan : number_format_id($data->sum('harga_perolehan') - $nilaiPenyusutan, 2) }}</th>
        </tr>
    </tbody>
</table>
