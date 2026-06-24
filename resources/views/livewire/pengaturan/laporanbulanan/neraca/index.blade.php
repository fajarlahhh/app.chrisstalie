<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item">Laporan Bulanan</li>
        <li class="breadcrumb-item active">Neraca</li>
    @endsection

    <h1 class="page-header">Neraca</h1>
    @if ($kodeAkunBelumMasuk->count() > 0)
        <div class="alert alert-warning">
            <strong>Warning:</strong> Ada kode akun yang belum masuk ke dalam neraca.
            <ul>
                @foreach ($kodeAkunBelumMasuk as $item)
                    <li>{{ $item['id'] }} - {{ $item['nama'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation" wire:ignore>
            <a href="#default-tab-0" data-bs-toggle="tab" class="nav-link active" aria-selected="true" role="tab">
                <span class="d-sm-none">Aktiva</span>
                <span class="d-sm-block d-none">Aktiva</span>
            </a>
        </li>
        <li class="nav-item" role="presentation" wire:ignore>
            <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link" aria-selected="true" role="tab">
                <span class="d-sm-none">Pasiva</span>
                <span class="d-sm-block d-none">Pasiva</span>
            </a>
        </li>
    </ul>
    <div class="tab-content panel rounded-0 p-3 m-0">
        <div class="tab-pane fade active show" id="default-tab-0" role="tabpanel" wire:ignore.self>
            <form wire:submit.prevent="simpanAktiva">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-10px">ID</th>
                                <th class="w-10px">Urutan</th>
                                <th>Nomor</th>
                                <th>Uraian</th>
                                <th>Kode Akun/Rumus</th>
                                <th>Kategori</th>
                                <th class="w-10px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aktiva as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="w-100px">
                                        <input type="number" class="form-control w-70px"
                                            wire:model="aktiva.{{ $key }}.urutan" wire:change="sortAktiva">
                                    </td>
                                    <td class="w-100px">
                                        <input type="text" class="form-control w-70px"
                                            wire:model="aktiva.{{ $key }}.nomor">
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control w-100" wire:model="aktiva.{{ $key }}.uraian" rows="5"></textarea>
                                    </td>
                                    <td>
                                        <select class="form-control" wire:model.live="aktiva.{{ $key }}.isi">
                                            <option value="Kode Akun">Kode Akun</option>
                                            <option value="Rumus">Rumus</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
                                        </select>
                                        @if ($item['isi'] == 'Kode Akun')
                                            <select class="form-control"
                                                wire:model="aktiva.{{ $key }}.kode_akun" multiple
                                                data-width="100%">
                                                @foreach (collect($dataKodeAkun)->whereIn('kategori', ['Aktiva']) as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['id'] }} -
                                                        {{ $item['nama'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($item['isi'] == 'Rumus')
                                            <textarea type="text" class="form-control w-100" wire:model="aktiva.{{ $key }}.rumus" rows="3"></textarea>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-control" wire:model="aktiva.{{ $key }}.kategori">
                                            <option value="Aktiva">Aktiva</option>
                                        </select>
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
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="addAktiva">Tambah
                                        Aktiva</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Simpan Aktiva
                </button>
                <x-modal.konfirmasi />
            </form>
        </div>
        <div class="tab-pane fade" id="default-tab-1" role="tabpanel" wire:ignore.self>
            <form wire:submit.prevent="simpanPasiva">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="w-10px">ID</th>
                                <th class="w-10px">Urutan</th>
                                <th>Nomor</th>
                                <th>Uraian</th>
                                <th>Kode Akun/Rumus</th>
                                <th>Kategori</th>
                                <th class="w-10px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pasiva as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration + collect($aktiva)->count() }}</td>
                                    <td class="w-100px">
                                        <input type="number" class="form-control w-70px"
                                            wire:model="pasiva.{{ $key }}.urutan" wire:change="sortPasiva">
                                    </td>
                                    <td class="w-100px">
                                        <input type="text" class="form-control w-70px"
                                            wire:model="pasiva.{{ $key }}.nomor">
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control w-100" wire:model="pasiva.{{ $key }}.uraian" rows="5"></textarea>
                                    </td>
                                    <td>
                                        <select class="form-control" wire:model.live="pasiva.{{ $key }}.isi">
                                            <option value="Kode Akun">Kode Akun</option>
                                            <option value="Rumus">Rumus</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
                                        </select>
                                        @if ($item['isi'] == 'Kode Akun')
                                            <select class="form-control"
                                                wire:model="pasiva.{{ $key }}.kode_akun" multiple
                                                data-width="100%">
                                                @foreach (collect($dataKodeAkun)->whereIn('kategori', ['Kewajiban', 'Ekuitas']) as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['id'] }} -
                                                        {{ $item['nama'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($item['isi'] == 'Rumus')
                                            <textarea type="text" class="form-control w-100" wire:model="pasiva.{{ $key }}.rumus" rows="3"></textarea>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-control"
                                            wire:model="pasiva.{{ $key }}.kategori">
                                            <option value="" hidden>Pilih Kategori</option>
                                            <option value="Kewajiban">Kewajiban</option>
                                            <option value="Ekuitas">Ekuitas</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            wire:click="deletePasiva({{ $key }})">
                                            x
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary"
                                        wire:click="addPasiva">Tambah
                                        Pasiva</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Simpan Pasiva
                </button>
            </form>
        </div>
    </div>
    <div wire:loading>
        <x-loading />
    </div>
    <x-alert />
</div>
