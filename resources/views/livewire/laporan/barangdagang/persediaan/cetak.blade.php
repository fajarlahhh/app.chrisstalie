@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Persediaan Barang Dagang</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $bulan }}</td>
        </tr>
        <tr>
            <th>Persediaan</th>
            <th class="w-10px">:</th>
            <td>{{ $persediaan ?? 'Semua Persediaan' }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <th class="w-10px">:</th>
            <td>{{ $kode_akun ?? 'Semua Kategori' }}</td>
        </tr>
        <tr>
            <th>Kata Kunci</th>
            <th class="w-10px">:</th>
            <td>{{ $cari }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="w-10px bg-gray-300 text-white">No.</th>
            <th class="bg-gray-300 text-white">Nama</th>
            <th class="bg-gray-300 text-white">Persediaan</th>
            <th class="bg-gray-300 text-white">Satuan</th>
            <th class="bg-gray-300 text-white">Kategori</th>
            <th class="bg-gray-300 text-white">{{ $jenis }}</th>
            @if ($jenis == 'Tanggal Masuk')
                <th class="bg-gray-300 text-white">Usia (hari)</th>
            @endif
            @role('administrator|supervisor')
                <th class="bg-gray-300 text-white">Harga Beli</th>
            @endrole
            <th class="bg-gray-300 text-white">Stok</th>
            @role('administrator|supervisor')
                <th class="bg-gray-300 text-white">Nilai Persediaan</th>
            @endrole
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($data as $item)
            @php
                $barangSatuanUtama = $item->barangSatuanUtama;
                $stok = $dataStok->where('barang_id', $item->id)->map(function ($q) use ($barangSatuanUtama) {
                    return [
                        'tanggal' => $q->tanggal,
                        'harga_beli' => $q->harga_beli * $barangSatuanUtama?->rasio_dari_terkecil,
                        'stok' => $q->stok / $barangSatuanUtama?->rasio_dari_terkecil,
                        'total' =>
                            (($q->harga_beli * $barangSatuanUtama?->rasio_dari_terkecil) /
                                $barangSatuanUtama?->rasio_dari_terkecil) *
                            $q->stok,
                    ];
                });

                $total += $stok->sum('total');
            @endphp
            @if ($stok->count() == 0)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td nowrap>{{ $item->nama }}</td>
                    <td nowrap>{{ $item->persediaan }}</td>
                    <td nowrap>
                        {{ $barangSatuanUtama?->nama }}
                        <small>{{ $barangSatuanUtama?->konversi_satuan }}</small>
                    </td>
                    <td nowrap>{{ $item->kode_akun_id }} - {{ $item->kodeAkun?->nama }}</td>
                    <td nowrap></td>
                    @if ($jenis == 'Tanggal Masuk')
                        <td nowrap></td>
                    @endif
                    @role('administrator|supervisor')
                        <td nowrap class="text-end">0</td>
                    @endrole
                    <td nowrap class="text-end">0</td>
                    @role('administrator|supervisor')
                        <td nowrap class="text-end">0</td>
                    @endrole
                </tr>
            @else
                @foreach ($stok->sortBy('tanggal') as $subItem)
                    <tr class="bg-green-100">
                        <td>{{ $loop->parent->iteration }}</td>
                        <td nowrap>{{ $item->nama }}</td>
                        <td nowrap>{{ $item->persediaan }}</td>
                        <td nowrap>
                            {{ $barangSatuanUtama?->nama }}
                            <small>{{ $barangSatuanUtama?->konversi_satuan }}</small>
                        </td>
                        <td nowrap>{{ $item->kode_akun_id }} - {{ $item->kodeAkun?->nama }}</td>

                        <td nowrap class="text-end">{{ $subItem['tanggal'] }}</td>

                        @if ($jenis == 'Tanggal Masuk')
                            <td nowrap class="text-end">
                                @php
                                    if ($bulan == date('Y-m')) {
                                        $tglBulan = \Carbon\Carbon::now()->startOfDay();
                                    } else {
                                        $tglBulan = \Carbon\Carbon::parse($bulan . '-01')
                                            ->endOfMonth()
                                            ->startOfDay();
                                    }
                                    $umur = \Carbon\Carbon::parse($subItem['tanggal'])
                                        ->startOfDay()
                                        ->diffInDays($tglBulan);
                                @endphp
                                {{ $umur }}
                            </td>
                        @endif

                        @role('administrator|supervisor')
                            <td nowrap class="text-end">{{ number_format_id($subItem['harga_beli'], 2) }}</td>
                        @endrole
                        <td nowrap class="text-end">
                            {{ fmod($subItem['stok'], 1) != 0 ? number_format_id($subItem['stok'], 3) : number_format_id($subItem['stok']) }}
                        </td>
                        @role('administrator|supervisor')
                            <td nowrap class="text-end">{{ number_format_id($subItem['total'], 2) }}</td>
                        @endrole
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
    @role('administrator|supervisor')
        <tfoot>
            <tr>
                <th colspan="8" class="text-end">Total Nilai Persediaan</th>
                <th class="text-end">{{ number_format_id($total, 2) }}</th>
        </tfoot>
    @endrole
</table>
