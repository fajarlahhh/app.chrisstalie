<div x-data="form()" x-init="init()" x-ref="alpineRoot">
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Member</li>
        <li class="breadcrumb-item">Paket Prabayar</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Paket Prabayar <small>Tambah</small></h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()">
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
                        @role('administrator|supervisor')
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input id="tanggal" class="form-control" type="date" wire:model="tanggal"
                                    x-model="tanggal" />
                                @error('tanggal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endrole
                        <div class="mb-3">
                            <label class="form-label">Paket Perawatan</label>
                            <select id="paket_perawatan_id" data-container="body" class="form-control"
                                x-init="$($el).selectpicker({
                                    liveSearch: true,
                                    width: 'auto',
                                    size: 10,
                                    container: 'body',
                                    style: '',
                                    showSubtext: true,
                                    styleBase: 'form-control'
                                });
                                $($el).on('change', function(e) {
                                    updatePaketPerawatan(this.value);
                                });" wire:model.live="paket_perawatan_id"
                                x-model="paket_perawatan_id" data-width="100%">
                                <option selected value="">-- Pilih Paket Perawatan --</option>
                                @foreach ($dataPaketPerawatan as $row)
                                    <option value="{{ $row['id'] }}">
                                        {{ $row['nama'] }}, Rp. {{ number_format_id($row['tarif']) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('paket_perawatan_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($paket_perawatan_id)
                            <div class="note alert-primary mb-3 border-0 shadow-sm"
                                style="border-left: 4px solid var(--bs-primary) !important; border-radius: 8px;">
                                <div class="note-content w-100">
                                    <h5 class="mb-3 d-flex align-items-center text-primary fw-bold">
                                        <i class="fa fa-box-open me-2 fs-16px text-primary"></i> Detail Paket Perawatan
                                    </h5>

                                    <div class="row g-2 mb-3">
                                        <div class="col-4">
                                            <div class="bg-white bg-opacity-70 p-2 rounded border h-100 text-center"
                                                style="border-color: rgba(var(--bs-primary-rgb), 0.15) !important;">
                                                <small class="text-muted d-block uppercase fw-bold mb-1"
                                                    style="font-size: 10px; letter-spacing: 0.5px;">Masa
                                                    Berlaku</small>
                                                <span
                                                    class="fs-13px fw-bold text-dark">{{ $paketPerawatan->masa_aktif }}
                                                    Hari</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-white bg-opacity-70 p-2 rounded border h-100 text-center"
                                                style="border-color: rgba(var(--bs-primary-rgb), 0.15) !important;">
                                                <small class="text-muted d-block uppercase fw-bold mb-1"
                                                    style="font-size: 10px; letter-spacing: 0.5px;">Sampai
                                                    Dengan</small>
                                                <span
                                                    class="fs-13px fw-bold text-dark">{{ date('Y-m-d', strtotime($tanggal . ' + ' . $paketPerawatan->masa_aktif . ' days')) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="bg-white bg-opacity-70 p-2 rounded border h-100 text-center"
                                                style="border-color: rgba(var(--bs-primary-rgb), 0.15) !important;">
                                                <small class="text-muted d-block uppercase fw-bold mb-1"
                                                    style="font-size: 10px; letter-spacing: 0.5px;">Tarif Paket</small>
                                                <span class="fs-13px fw-bold text-primary">Rp.
                                                    {{ number_format_id($paketPerawatan->tarif) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white bg-opacity-80 rounded border p-2"
                                        style="border-color: rgba(var(--bs-primary-rgb), 0.15) !important;">
                                        <div
                                            class="px-2 py-1 bg-light rounded mb-2 d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-muted uppercase"
                                                style="font-size: 10px; letter-spacing: 0.5px;"><i
                                                    class="fa fa-list-ul me-1"></i> Tindakan Tercover</span>
                                            <span class="badge bg-primary rounded-pill"
                                                style="font-size: 9px;">{{ count($paketPerawatan->paketPerawatanDetail) }}
                                                Item</span>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0" style="font-size: 12px;">
                                                <thead>
                                                    <tr class="text-muted border-bottom">
                                                        <th class="ps-2 py-1 border-0 fw-semibold">Nama Tindakan</th>
                                                        <th class="text-end pe-2 py-1 border-0 fw-semibold w-80px">Qty
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($paketPerawatan->paketPerawatanDetail as $row)
                                                        <tr>
                                                            <td class="ps-2 py-2 border-0 text-dark fw-medium">
                                                                {{ $row->tarifTindakan->nama }}</td>
                                                            <td
                                                                class="text-end pe-2 py-2 border-0 fw-bold text-primary">
                                                                {{ $row->qty }}x</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Total Tagihan</label>
                            <input type="text" class="form-control text-end fs-16px text-bold"
                                :value="formatNumber(total_tagihan)" disabled>
                        </div>
                        <div class="note alert-success mb-2">
                            <div class="note-content">
                                <h5>Pembayaran</h5>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Metode Bayar</label>
                                    <div class="input-group">
                                        <select id="metode_bayar" class="form-control" wire:model="metode_bayar"
                                            x-model="metode_bayar" data-width="100%">
                                            <option hidden>-- Pilih Metode Bayar --</option>
                                            <template x-for="item in dataMetodeBayar" :key="item.id">
                                                <option :value="item.id" x-text="item.nama"
                                                    :selected="metode_bayar == item.id"></option>
                                            </template>
                                        </select>
                                        <input id="total_bayar" class="form-control text-end fs-16px text-bold"
                                            type="number" wire:model="total_bayar" x-model.number="total_bayar" />
                                    </div>
                                    @error('total_bayar')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Uang Kembali</label>
                                    <input class="form-control text-end" type="text" disabled
                                        :value="formatNumber((total_bayar > parseInt(total_tagihan || 0)) ? total_bayar -
                                            parseInt(total_tagihan || 0) : 0)" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea id="keterangan" class="form-control" type="text" wire:model="keterangan" x-model="keterangan"></textarea>
                                    @error('keterangan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
                    onclick="window.location.href='/member/paketprabayar'" wire:loading.attr="disabled">
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
@push('scripts')
    <script>
        function form() {
            return {
                dataMetodeBayar: @js($dataMetodeBayar ?? []),
                dataPaketPerawatan: @js($dataPaketPerawatan ?? []),
                total_bayar: @js($total_bayar),
                paket_perawatan_id: @js($paket_perawatan_id),
                metode_bayar: @js($metode_bayar),
                tanggal: @js($tanggal),
                total_tagihan: @js($total_tagihan),
                keterangan: @js($keterangan),
                formatNumber(val) {
                    if (val === null || val === undefined || isNaN(val)) return '0';
                    return `${new Intl.NumberFormat('id-ID').format(val)}`;
                },
                updatePaketPerawatan(id) {
                    let selected = this.dataPaketPerawatan.find(b => b.id == id);
                    if (selected) {
                        this.total_tagihan = selected.tarif;
                    }
                },
                syncToLivewire() {
                    // sinkronkan data ke livewire
                    if (window.Livewire && window.Livewire.find) {
                        let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                        if (componentId) {
                            let $wire = window.Livewire.find(componentId);
                            if ($wire && typeof $wire.set === 'function') {
                                $wire.set('total_tagihan', this.total_tagihan, true);
                                $wire.set('paket_perawatan_id', this.paket_perawatan_id, true);
                                $wire.set('tanggal', this.tanggal, true);
                                $wire.set('cash', this.cash, true);
                                $wire.set('metode_bayar', this.metode_bayar, true);
                                $wire.set('keterangan', this.keterangan, true);
                                $wire.set('total_bayar', this.total_bayar, true);
                            }
                        }
                    }
                },
                init() {}


            }
        }
    </script>
@endpush
