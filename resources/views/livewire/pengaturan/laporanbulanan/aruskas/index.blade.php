<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item">Laporan Bulanan</li>
        <li class="breadcrumb-item active">Arus Kas</li>
    @endsection

    <h1 class="page-header">Arus Kas</h1>
    <form wire:submit.prevent="simpan">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">Form</h4>
            </div>
            <div class="panel-body table-responsive">
                <x-alert />
                @if (collect($kodeAkunDouble)->count() > 0)
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> Ada kode akun yang double muncul di template arus kas.
                        <ul>
                            @foreach ($kodeAkunDouble as $item)
                                <li>{{ $item }} </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="w-10px">Urutan</th>
                            <th>Nomor</th>
                            <th>Uraian</th>
                            <th>Kode Akun/Rumus</th>
                            <th>Kategori</th>
                            <th class="w-10px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="w-100px">
                                    <input type="number" class="form-control w-70px"
                                        wire:model="data.{{ $key }}.urutan" wire:change="sortData">
                                </td>
                                <td class="w-100px">
                                    <input type="text" class="form-control w-70px"
                                        wire:model="data.{{ $key }}.nomor">
                                </td>
                                <td>
                                    <textarea type="text" class="form-control w-100" wire:model="data.{{ $key }}.uraian" rows="5"></textarea>
                                </td>
                                <td>
                                    <select class="form-control" wire:model.live="data.{{ $key }}.isi">
                                        <option value="Kode Akun">Kode Akun</option>
                                        <option value="Rumus">Rumus</option>
                                        <option value="Tidak Ada">Tidak Ada</option>
                                    </select>
                                    @if ($item['isi'] == 'Kode Akun')
                                        <select class="form-control" wire:model="data.{{ $key }}.kode_akun"
                                            multiple data-width="100%">
                                            @foreach (collect($dataKodeAkun) as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['id'] }} -
                                                    {{ $item['nama'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($item['isi'] == 'Rumus')
                                        <textarea type="text" class="form-control w-100" wire:model="data.{{ $key }}.rumus" rows="3"></textarea>
                                    @endif
                                </td>
                                <td>
                                    <select class="form-control" wire:model="data.{{ $key }}.kategori">
                                        <option value="">Tidak Ada</option>
                                        <option value="Mutasi">Mutasi</option>
                                        <option value="Mutasi (-)">Mutasi (-)</option>
                                        <option value="Debet">Debet</option>
                                        <option value="Kredit">Kredit</option>
                                        <option value="Saldo Awal">Saldo Awal</option>
                                        <option value="Saldo Akhir">Saldo Akhir</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        wire:click="deleteData({{ $key }})">
                                        x
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" wire:click="addData">Tambah
                                    Arus Kas</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Simpan
                </button>
            </div>
        </div>
    </form>
    <x-alert />
    <div wire:loading>
        <x-loading />
    </div>
</div>
