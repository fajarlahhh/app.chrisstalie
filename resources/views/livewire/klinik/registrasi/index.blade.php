<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item active">Registrasi</li>
    @endsection

    <h1 class="page-header">Registrasi</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nav nav-tabs bg-gray-100">
                            <li class="nav-item">
                                <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active"
                                    wire:click="resetPasien" wire:ignore.self>
                                    <span class="d-sm-none">Pasien Baru</span>
                                    <span class="d-sm-block d-none">Pasien Baru</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link" wire:click="resetPasien"
                                    wire:ignore.self>
                                    <span class="d-sm-none">Pasien Lama</span>
                                    <span class="d-sm-block d-none">Pasien Lama</span>
                                </a>
                            </li>
                        </ul>
                        <!-- END nav-tabs -->
                        <!-- BEGIN tab-content -->
                        <div class="tab-content panel rounded-0 p-3 m-0 bg-gray-100">
                            <!-- BEGIN tab-pane -->
                            <div class="tab-pane fade active show" id="default-tab-1" wire:ignore.self>
                                <h4 class="mt-10px">Data Pasien</h4>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input id="nik" class="form-control" type="text" wire:model="nik" />
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input id="nama" class="form-control" type="text" wire:model="nama" />
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input id="alamat" class="form-control" type="text" wire:model="alamat" />
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input id="tanggal_lahir" class="form-control" type="date"
                                        wire:model="tanggal_lahir" />
                                    @error('tanggal_lahir')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" data-container="body" class="form-control "
                                        wire:model="jenis_kelamin" data-width="100%">
                                        <option selected hidden>-- Tidak Ada Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input id="no_hp" class="form-control" type="text" wire:model="no_hp" />
                                    @error('no_hp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- END tab-pane -->
                            <!-- BEGIN tab-pane -->
                            <div class="tab-pane fade" id="default-tab-2" wire:ignore.self>
                                <h4 class="mt-10px">Data Pasien</h4>
                                <hr>
                                @if (!$pasien_id)
                                    <div class="mb-3">
                                        <label class="form-label">Cari Pasien</label>
                                        <div wire:ignore>
                                            <select class="form-control" x-init="$($el).select2({
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                                                dropdownAutoWidth: true,
                                                templateResult: format,
                                                minimumInputLength: 3,
                                                dataType: 'json',
                                                ajax: {
                                                    url: '/cari/pasien',
                                                    data: function(params) {
                                                        var query = {
                                                            cari: params.term
                                                        }
                                                        return query;
                                                    },
                                                    processResults: function(data, params) {
                                                        return {
                                                            results: data,
                                                        };
                                                    },
                                                    cache: true
                                                }
                                            });
                                            
                                            $($el).on('change', function(element) {
                                                $wire.set('pasien_id', $($el).val());
                                            });
                                            
                                            function format(data) {
                                                if (!data.id) {
                                                    return data.text;
                                                }
                                                var $data = $('<table><tr><th>No. RM</th><th>:</th><th>' + data.rm + '</th></tr>' +
                                                    '<tr><th>No. KTP</th><th>:</th><th>' + data.nik + '</th></tr>' +
                                                    '<tr><th>Nama</th><th>:</th><th>' + data.nama + '</th></tr>' +
                                                    '<tr><th>Alamat</th><th>:</th><th>' + data.alamat + '</th></tr></table>');
                                                return $data;
                                            }">
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">No. RM</label>
                                    <input id="rm" class="form-control" type="text" wire:model="rm"
                                        @if ($nik) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('rm')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input id="nama-2" class="form-control" type="text" wire:model="nama"
                                        @if ($nama) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input id="nik-2" class="form-control" type="text" wire:model="nik"
                                        @if ($nik) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input id="alamat-2" class="form-control" type="text" wire:model="alamat"
                                        @if ($alamat) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input id="tanggal_lahir-2" class="form-control" type="date"
                                        wire:model="tanggal_lahir" @if ($tanggal_lahir) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('tanggal_lahir')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input id="jenis_kelamin-2" class="form-control" type="text"
                                        wire:model="jenis_kelamin" @if ($jenis_kelamin) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input id="no_hp-2" class="form-control" type="text" wire:model="no_hp"
                                        @if ($no_hp) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                </div>
                            </div>
                            <!-- END tab-pane -->
                        </div>
                        <!-- END tab-content -->
                    </div>
                    <div class="col-md-6">
                        @if ($pasien)
                            @if ($pasien->rekamMedis->count() > 0)
                                <div class="panel panel-inverse mb-3 border">
                                    <div class="panel-heading ui-sortable-handle">
                                        <h4 class="panel-title"><i class="fa fa-history me-2"></i>History Registrasi
                                        </h4>
                                    </div>
                                    <div class="panel-body overflow-auto p-1" style="max-height: 250px;">
                                        <div class="list-group list-group-flush">
                                            @foreach ($pasien->rekamMedis as $row)
                                                <div
                                                    class="list-group-item border-bottom mb-1 bg0 transition-all bg-gray-100">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="fw-bold text-dark fs-12px">
                                                            <i class="fa fa-calendar-alt text-muted me-1"></i>
                                                            {{ $row->created_at->format('d M Y') }}
                                                        </span>
                                                        <span class="badge bg-secondary text-white rounded-pill fs-10px">
                                                            #{{ $row->id }}
                                                        </span>
                                                    </div>
                                                    @if ($row->ketemu_dokter == 1)
                                                        <div class="fs-12px mb-1">
                                                            Dokter: <strong
                                                                class="text-dark">{{ $row->nakes?->nama ?? 'Tidak Ada Dokter' }}</strong>
                                                        </div>
                                                    @endif
                                                    <div
                                                        class="fs-12px text-muted bg-light p-2 rounded mt-2">
                                                        <strong>Keluhan Awal:</strong>
                                                        <div class="text-dark mt-1">{{ $row->keluhan_awal ?: '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input id="tanggal" class="form-control" type="date" wire:model="tanggal"
                                min="{{ date('Y-m-d') }}" />
                            @error('tanggal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div x-data="{ ketemuDokter: @entangle('ketemu_dokter').defer }">
                            <div class="mb-3">
                                <label class="form-label">Ketemu Dokter</label>
                                <select id="ketemu_dokter" data-container="body" class="form-control"
                                    x-model="ketemuDokter" wire:model="ketemu_dokter" data-width="100%">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                            <div class="mb-3" x-show="ketemuDokter == 1" x-cloak>
                                <label class="form-label">Dokter</label>
                                <select id="nakes_id" data-container="body" class="form-control"
                                    x-init="$($el).selectpicker({
                                        liveSearch: true,
                                        width: 'auto',
                                        size: 10,
                                        container: 'body',
                                        style: '',
                                        showSubtext: true,
                                        styleBase: 'form-control'
                                    })" wire:model="nakes_id" data-width="100%">
                                    <option selected value="">-- Pilih Dokter --</option>
                                    @foreach ($dataNakes as $row)
                                        <option value="{{ $row['id'] }}">
                                            {{ $row['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nakes_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keluhan Awal</label>
                            <textarea id="keluhan_awal" class="form-control" wire:model="keluhan_awal" rows="5"></textarea>
                            @error('keluhan_awal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <button type="button" x-init="$($el).on('click', function() {
                        $('#modal-konfirmasi').modal('show');
                    })" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Submit
                    </button>
                @endrole
                <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/registrasi/data'">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Data
                </button>
                <button type="button" class="btn btn-warning m-r-3"
                    onclick="window.location.href='/klinik/registrasi'" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Reset
                </button>

                <x-alert />
            </div>

            <x-modal.konfirmasi />
        </form>
    </div>

    <div wire:loading>
        <x-loading />
    </div>
</div>
