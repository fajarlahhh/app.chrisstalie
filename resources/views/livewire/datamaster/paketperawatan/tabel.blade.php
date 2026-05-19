<table class="table table-hover">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>Nama</th>
            <th>Uraian</th>
            <th>Jenis</th>
            <th>Tindakan</th>
            <th>Harga Standar</th>
            <th>Harga Paket</th>
            @if ($cetak == false)
                <th></th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $cetak == false ? ($data->currentPage() - 1) * $data->perPage() + $loop->iteration : $loop->iteration }}
                </td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->uraian }}</td>
                <td>{{ $item->jenis }}</td>
                <td>{!! $item->paketPerawatanDetail->map(fn($q) => $q->tarifTindakan->nama . ' (' . $q->qty . ')')->implode('<br> ') !!}</td>
                <td class="text-end">{{ number_format_id($item->paketPerawatanDetail->sum(fn($q) => $q->tarifTindakan->tarif * $q->qty)) }}</td>
                <td class="text-end">{{ number_format_id($item->tarif) }}</td>
                @if ($cetak == false)
                    <td class="with-btn-group text-end" nowrap>
                        <x-action :row="$item" custom="" :detail="false" :edit="true" :print="false"
                            :permanentdelete="false" :restore="false" :delete="true" />
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
