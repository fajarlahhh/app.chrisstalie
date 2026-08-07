@php
    $metode_bayar_list = collect(array_merge(
        $data->whereNotNull('metode_bayar')->pluck('metode_bayar')->unique()->toArray(),
        $data->whereNotNull('metode_bayar_2')->pluck('metode_bayar_2')->unique()->toArray(),
        $data->whereNotNull('metode_bayar_3')->pluck('metode_bayar_3')->unique()->toArray(),
    ))->unique()->toArray();
    $metode_bayar_count = count($metode_bayar_list);

    $sum_registrasi = $data->sum('total_registrasi_paket_perawatan');
    $sum_tindakan = $data->sum('total_tindakan');
    $sum_resep = $data->sum('total_resep');
    $sum_barang = $data->sum('total_barang');
    $sum_total_sblm = $sum_registrasi + $sum_tindakan + $sum_resep + $sum_barang;
    $sum_diskon = $data->sum('total_diskon_barang') + $data->sum('total_diskon_tindakan') + $data->sum('total_diskon_resep') + $data->sum('diskon');
    $sum_total_setelah = $sum_total_sblm - $sum_diskon;
@endphp
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
            <th class="bg-gray-300 text-white" rowspan="2">Tanggal</th>
            <th class="bg-gray-300 text-white" rowspan="2">Nama</th>
            <th class="bg-gray-300 text-white" rowspan="2">Alamat</th>
            <th class="bg-gray-300 text-white" rowspan="2">Jenis Kelamin</th>
            <th class="bg-gray-300 text-white" rowspan="2">Paket</th>
            <th class="bg-gray-300 text-white" rowspan="2">Tindakan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Resep</th>
            <th class="bg-gray-300 text-white" rowspan="2">Penjualan Barang</th>
            <th class="bg-gray-300 text-white" rowspan="2">Total Sebelum Diskon</th>
            <th class="bg-gray-300 text-white" rowspan="2">Diskon</th>
            <th class="bg-gray-300 text-white" rowspan="2">Total Setelah Diskon</th>
            <th class="bg-gray-300 text-white" colspan="{{ $metode_bayar_count > 0 ? $metode_bayar_count : 1 }}">
                Metode Bayar</th>
            @role('administrator|supervisor')
                <th class="bg-gray-300 text-white" rowspan="2">Kasir</th>
            @endrole
            <th class="bg-gray-300 text-white" rowspan="2">Keterangan</th>
            <th class="bg-gray-300 text-white" colspan="3">Waktu Pelayanan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Tindakan</th>
            <th class="bg-gray-300 text-white" rowspan="2">Barang</th>
        </tr>
        <tr>
            @if ($metode_bayar_count > 0)
                @foreach ($metode_bayar_list as $item)
                    <th class="bg-gray-300 text-white">{{ $item }}</th>
                @endforeach
            @else
                <th class="bg-gray-300 text-white"></th>
            @endif
            <th class="bg-gray-300 text-white">Jam Masuk</th>
            <th class="bg-gray-300 text-white">Jam Pulang</th>
            <th class="bg-gray-300 text-white">Total Durasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            @php
                $diskon = $row['total_diskon_barang'] + $row['total_diskon_resep'] + $row['total_diskon_tindakan'] + $row['diskon'];
                $total_sblm = $row['total_tindakan'] + $row['total_registrasi_paket_perawatan'] + $row['total_resep'] + $row['total_barang'];
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row['id'] }}</td>
                <td nowrap>{{ $row['tanggal'] }}</td>
                <td nowrap>
                    {{ isset($row['registrasi']) && isset($row['registrasi']['pasien']) && isset($row['registrasi']['pasien']['nama']) ? $row['registrasi']['pasien']['nama'] : '' }}
                </td>
                <td nowrap>
                    {{ isset($row['registrasi']) && isset($row['registrasi']['pasien']) && isset($row['registrasi']['pasien']['alamat']) ? $row['registrasi']['pasien']['alamat'] : '' }}
                </td>
                <td nowrap>
                    {{ isset($row['registrasi']) && isset($row['registrasi']['pasien']) && isset($row['registrasi']['pasien']['jenis_kelamin']) ? $row['registrasi']['pasien']['jenis_kelamin'] : '' }}
                </td>
                <td class="text-end">
                    {{ $cetak ? $row['total_registrasi_paket_perawatan'] : number_format_id($row['total_registrasi_paket_perawatan']) }}
                </td>
                <td class="text-end">{{ $cetak ? $row['total_tindakan'] : number_format_id($row['total_tindakan']) }}
                </td>
                <td class="text-end">{{ $cetak ? $row['total_resep'] : number_format_id($row['total_resep']) }}</td>
                <td class="text-end">
                    {{ $cetak ? $row['total_barang'] : number_format_id($row['total_barang']) }}</td>
                <td class="text-end">
                    {{ $cetak ? $total_sblm : number_format_id($total_sblm) }}
                </td>
                <td class="text-end">{{ $cetak ? $diskon : number_format_id($diskon) }}</td>
                <td class="text-end">
                    {{ $cetak ? $total_sblm - $diskon : number_format_id($total_sblm - $diskon) }}
                </td>
                @foreach ($metode_bayar_list as $item)
                    <td class="text-end" nowrap>
                        @if ($row['metode_bayar'] == $item && $row['bayar'] > 0)
                            {{ $cetak ? $row['bayar'] - $row['selisih'] : number_format_id($row['bayar'] - $row['selisih']) }}
                        @endif
                        @if ($row['metode_bayar_2'] == $item && $row['bayar_2'] > 0)
                            {{ $cetak ? $row['bayar_2'] : number_format_id($row['bayar_2']) }}
                        @endif
                        @if ($row['metode_bayar_3'] == $item && $row['bayar_3'] > 0)
                            {{ $cetak ? $row['bayar_3'] : number_format_id($row['bayar_3']) }}
                        @endif
                    </td>
                @endforeach
                @role('administrator|supervisor')
                    <td nowrap>{{ $row['pengguna']['nama'] ?? '' }}</td>
                @endrole
                <td nowrap>{{ $row['keterangan'] }}</td>
                <td nowrap>{{ isset($row['registrasi']) ? $row['registrasi']['created_at'] : '' }}</td>
                <td nowrap>{{ isset($row['registrasi']) ? $row['created_at'] : '' }}</td>
                <td nowrap>
                    @if (isset($row['registrasi']['created_at']) && isset($row['created_at']))
                        {{ \Carbon\Carbon::parse($row['registrasi']['created_at'])->diff(\Carbon\Carbon::parse($row['created_at']))->format('%h Jam %i Menit %s Detik') }}
                    @endif
                </td>
                <td nowrap>
                    @php
                        $tindakans = [];
                        $tindakanList = data_get($row, 'registrasi.tindakan', []);
                        foreach ($tindakanList as $t) {
                            $nama = data_get($t, 'tarifTindakan.nama') ?: data_get($t, 'tarif_tindakan.nama');
                            if ($nama) {
                                $tindakans[] = $nama;
                            }
                        }
                        echo implode('<br>', $tindakans);
                    @endphp
                </td>
                <td nowrap>
                    @php
                        $barangs = [];
                        $resepObatList = data_get($row, 'registrasi.resepObat', data_get($row, 'registrasi.resep_obat', []));
                        foreach ($resepObatList as $r) {
                            $nama = data_get($r, 'barang.nama');
                            if ($nama) {
                                $barangs[] = $nama;
                            }
                        }
                        $stokKeluarList = data_get($row, 'stokKeluarPenjualan', data_get($row, 'stok_keluar_penjualan', []));
                        foreach ($stokKeluarList as $s) {
                            $nama = data_get($s, 'barang.nama');
                            if ($nama) {
                                $barangs[] = $nama;
                            }
                        }
                        // remove duplicates if necessary
                        echo implode('<br>', array_unique($barangs));
                    @endphp
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">Total</th>
            <th class="text-end">{{ $cetak ? $sum_registrasi : number_format_id($sum_registrasi) }}</th>
            <th class="text-end">{{ $cetak ? $sum_tindakan : number_format_id($sum_tindakan) }}</th>
            <th class="text-end">{{ $cetak ? $sum_resep : number_format_id($sum_resep) }}</th>
            <th class="text-end">{{ $cetak ? $sum_barang : number_format_id($sum_barang) }}</th>
            <th class="text-end">{{ $cetak ? $sum_total_sblm : number_format_id($sum_total_sblm) }}</th>
            <th class="text-end">{{ $cetak ? $sum_diskon : number_format_id($sum_diskon) }}</th>
            <th class="text-end">{{ $cetak ? $sum_total_setelah : number_format_id($sum_total_setelah) }}</th>
            
            @foreach ($metode_bayar_list as $item)
                @php
                    $sum_metode = $data->where('metode_bayar', $item)->sum(fn($row) => $row['bayar'] - $row['selisih']) +
                                $data->where('metode_bayar_2', $item)->sum(fn($row) => $row['bayar_2']) +
                                $data->where('metode_bayar_3', $item)->sum(fn($row) => $row['bayar_3']);
                @endphp
                <th class="text-end">
                    {{ $cetak ? $sum_metode : number_format_id($sum_metode) }}
                </th>
            @endforeach
            
            @role('administrator|supervisor')
                <th colspan="3"></th>
            @endrole
            @role('operator')
                <th colspan="2"></th>
            @endrole
            <th colspan="5"></th>
        </tr>

        <tr>
            <th colspan="6"></th>
            <th>Paket</th>
            <th>Tindakan</th>
            <th>Resep</th>
            <th>Penjualan Barang</th>
            <th>Total Sebelum Diskon</th>
            <th>Diskon</th>
            <th>Total Setelah Diskon</th>
            @if ($metode_bayar_count > 0)
                @foreach ($metode_bayar_list as $item)
                    <th>{{ $item }}</th>
                @endforeach
            @else
                <th>Metode Bayar</th>
            @endif
            
            @role('administrator|supervisor')
                <th>Kasir</th>
            @endrole
            <th>Keterangan</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Total Durasi</th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>
