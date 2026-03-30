<table class="table table-hover">
    <thead>
        <tr>
            <th rowspan="2" class="w-10px">No.</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Satuan</th>
            <th rowspan="2">Utama</th>
            <th rowspan="2">Konversi Satuan</th>
            <th rowspan="2" class="text-end">Harga Jual</th>
            <th rowspan="2" class="text-end">Harga Beli Tertinggi</th>
            <th colspan="2">Keuntungan</th>
            <th rowspan="2" class="w-5px"></th>
        </tr>
        <tr>
            <th class="text-end">Rp.</th>
            <th class="text-end">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>
                    {{ $cetak == false ? ($data->currentPage() - 1) * $data->perPage() + $loop->iteration : $loop->iteration }}
                </td>
                <td>{{ $item->barang_nama }}</td>
                <td>{{ $item->nama }}</td>
                <td>{!! $item->utama == 1 ? '<span class="badge bg-success">Ya</span>' : '' !!}</td>
                <td>{!! $item->rasio_dari_terkecil == 1
                    ? '<span class="badge bg-success">Satuan Terkecil</span>'
                    : $item->konversi_satuan !!}</td>
                <td class="text-end">{{ $cetak == false ? number_format_id($item->harga_jual) : $item->harga_jual }}</td>
                <td class="text-end">
                    {{ $cetak == false ? number_format_id($item->barang->harga_beli_tertinggi * $item->rasio_dari_terkecil) : $item->barang->harga_beli_tertinggi * $item->rasio_dari_terkecil }}
                </td>
                @php
                    $keuntungan = $item->harga_jual - $item->barang->harga_beli_tertinggi * $item->rasio_dari_terkecil;
                @endphp
                <td class="text-end">{{ $cetak == false ? number_format_id($keuntungan) : $keuntungan }}</td>
                <td class="text-end">
                    @if ($item->harga_jual != 0)
                        {{ $cetak == false ? number_format_id(($keuntungan / $item->harga_jual) * 100) : ($keuntungan / $item->harga_jual) * 100 }}
                    @else
                        0
                    @endif
                </td>
                @if ($cetak == false)
                    <td class="with-btn-group text-end" nowrap>
                        @role('administrator|supervisor')
                            @if ($item->rasio_dari_terkecil == 1)
                                <x-action :row="$item" custom="" :detail="false" :edit="true"
                                    :print="false" :permanentdelete="false" :restore="false" :delete="false" />
                            @else
                                <x-action :row="$item" custom="" :detail="false" :edit="true"
                                    :print="false" :permanentdelete="false" :restore="false" :delete="true" />
                            @endif
                        @endrole
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
