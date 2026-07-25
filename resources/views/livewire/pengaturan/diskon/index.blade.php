<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item active">Diskon</li>
    @endsection

    <h1 class="page-header">Diskon</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            @role('administrator|supervisor|operator')
                <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'"
                    class="btn btn-outline-secondary btn-block">Tambah</a>&nbsp;
            @endrole
            <div class="ms-auto d-flex align-items-center">
                <input id="cari" type="text" class="form-control w-auto" placeholder="Cari" autocomplete="off"
                    wire:model.lazy="cari">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-10px">No.</th>
                        <th>Uraian</th>
                        <th>Tarif Tindakan</th>
                        {{-- <th>Barang Dagang</th> --}}
                        <th>Harga Standar</th>
                        <th>Harga Diskon</th>
                        <th>Periode</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $row->uraian }}</td>
                            <td>{{ $row->tarifTindakan?->nama }}</td>
                            {{-- <td>
                                {{ $row->barangSatuan?->barang->nama }}
                            </td> --}}
                            <td>
                                @if ($row->tarifTindakan)
                                    {{ number_format_id($row->tarifTindakan?->tarif) }}
                                @else
                                    {{ number_format_id($row->barangSatuan?->harga_jual) }}
                                @endif
                            </td>
                            <td>{{ number_format_id($row->harga_diskon) }}</td>
                            <td>{{ $row->tanggal_mulai }} s/d {{ $row->tanggal_berakhir }}</td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    <x-action :row="$row" custom="" :detail="false" :edit="false"
                                        :print="false" :permanentdelete="false" :restore="false" :delete="true" />
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            {{ $data->links() }}
        </div>
    </div>

    <div wire:loading>
        <x-loading />
    </div>
</div>
