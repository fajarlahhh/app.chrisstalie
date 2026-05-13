<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item">Laporan Bulanan</li>
        <li class="breadcrumb-item active">Neraca</li>
    @endsection

    <h1 class="page-header">Laporan Bulanan Neraca</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @if ($kodeAkunBelumMasukAktiva->count() > 0)
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Ada kode akun yang belum masuk ke dalam neraca.
                    <ul>
                        @foreach ($kodeAkunBelumMasukAktiva as $item)
                            <li>{{ $item['id'] }} - {{ $item['nama'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-10px">Urutan</th>
                        <th>Nomor</th>
                        <th>Uraian</th>
                        <th>Kode Akun</th>
                        <th>Rumus <small>(Gunakan urutannya)</small></th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aktiva as $key => $item)
                        <tr>
                            <td class="w-100px">
                                <input type="number" class="form-control w-70px"
                                    wire:model="aktiva.{{ $key }}.urutan" wire:change="sortAktiva">
                            </td>
                            <td class="w-100px">
                                <input type="text" class="form-control w-70px"
                                    wire:model="aktiva.{{ $key }}.nomor">
                            </td>
                            <td>
                                <textarea type="text" class="form-control w-100" wire:model="aktiva.{{ $key }}.uraian" rows="3"></textarea>
                            </td>
                            <td>
                                <select class="form-control" wire:model="aktiva.{{ $key }}.kode_akun" multiple
                                    data-width="100%">
                                    <option value="">Tidak Ada Kode Akun</option>
                                    @foreach ($dataKodeAkun as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['id'] }} - {{ $item['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <textarea type="text" class="form-control w-100" wire:model="aktiva.{{ $key }}.rumus" rows="3"></textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger"
                                    wire:click="deleteAktiva({{ $key }})">
                                    x
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div wire:loading>
        <x-loading />
    </div>
</div>
