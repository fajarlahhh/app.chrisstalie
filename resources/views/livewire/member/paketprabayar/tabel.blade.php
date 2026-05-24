<table class="table table-hover">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>Pasien</th>
            <th>Nama</th>
            <th>Paket Prabayar</th>
            <th>Metode Bayar</th>
            <th>Harga Paket/Harga Satuan</th>
            <th>Qty</th>
            <th>Tanggal Beli</th>
            <th>Tanggal Berakhir</th>
            <th class="w-10px"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $row)
            <tr>
                <td>
                    {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                </td>
                <td>{{ $row->pasien_id }}</td>
                <td>{{ $row->pasien->nama }}</td>
                <td>{{ $row->paketPerawatan->nama }}<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<small>Tindakan :
                        {{ $row->paketPerawatan->paketPerawatanDetail->first()->tarifTindakan->nama }}</small>
                </td>
                <td>{{ $row->metode_bayar }}</td>
                <td>{{ $cetak ? $row->total : number_format_id($row->total) }}/{{ $cetak ? $row->total/$row->qty : number_format_id($row->total/$row->qty) }}</td>
                <td>{{ $row->qty_terpakai }}/{{ $row->qty }}</td>
                <td>{{ $row->tanggal_aktif }}</td>
                <td>{{ $row->tanggal_berakhir }}</td>
                <td class="with-btn-group text-end" nowrap>
                    @role('administrator|supervisor')
                        @if ($row->qty_terpakai > 0)
                            <x-action :row="$row" custom="" :detail="false" :edit="false" :print="false"
                            :permanentdelete="false" :restore="false" :delete="false" />
                        @else
                            <x-action :row="$row" custom="" :detail="false" :edit="false" :print="false"
                            :permanentdelete="false" :restore="false" :delete="true" />
                        @endif
                    @endrole
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
