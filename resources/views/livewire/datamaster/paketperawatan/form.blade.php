<div x-data="paketPerawatan()" x-init="init()" x-ref="alpineRoot">
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">Paket Perawatan</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">Paket Perawatan <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small></h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()" @keydown.enter="if ($event.target.tagName !== 'TEXTAREA') $event.preventDefault()">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input id="nama" class="form-control" type="text" wire:model="nama"
                                x-model="nama" />
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Uraian</label>
                            <input id="uraian" class="form-control" type="text" wire:model="uraian"
                                x-model="uraian" />
                            @error('uraian')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select id="jenis" class="form-control" wire:model="jenis" x-model="jenis"
                                @change="updatedJenis()" @if ($data->exists) disabled @endif
                                data-width="100%">
                                <option value="" selected>-- Pilih Jenis --</option>
                                <option value="Bundling">Bundling</option>
                                <option value="Prabayar">Prabayar</option>
                            </select>
                            @error('jenis')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <template x-if="jenis == 'Prabayar'">
                            <div class="mb-3">
                                <label class="form-label">Jenis Prabayar</label>
                                <select id="jenis_prabayar" class="form-control" wire:model="jenis_prabayar" x-model="jenis_prabayar"
                                    @change="updatedJenisPrabayar()" @if ($data->exists) disabled @endif
                                    data-width="100%">
                                    <option value="" selected>-- Pilih Jenis Prabayar --</option>
                                    <option value="Masa Aktif">Masa Aktif</option>
                                    <option value="Periode Tanggal">Periode Tanggal</option>
                                </select>
                                @error('jenis_prabayar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </template>
                        <template x-if="jenis == 'Prabayar'">
                            <div class="mb-3">
                                <label class="form-label">Kode Akun Kewajiban</label>
                                <select id="kode_akun_kewajiban_id" class="form-control" wire:model="kode_akun_kewajiban_id"
                                    x-init="$($el).selectpicker({
                                        liveSearch: true,
                                        width: 'auto',
                                        size: 10,
                                        container: 'body',
                                        style: '',
                                        showSubtext: true,
                                        styleBase: 'form-control'
                                    })" data-width="100%">
                                    <option hidden selected>-- Tidak Ada Kode Akun --</option>
                                    @foreach ($dataKodeAkun as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['id'] }} - {{ $item['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_akun_kewajiban_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </template>
                        <template x-if="jenis_prabayar == 'Masa Aktif'">
                            <div class="mb-3">
                                <label class="form-label">Masa Aktif <small>(Hari)</small></label>
                                <input id="masa_aktif" class="form-control" type="number" step="1" min="0"
                                    wire:model="masa_aktif" x-model.number="masa_aktif" />
                                @error('masa_aktif')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </template>
                        <template x-if="jenis_prabayar == 'Periode Tanggal'">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Pendaftaran</label>
                                <div class="input-group">
                                    <input id="tanggal_mulai_daftar" class="form-control" type="date" step="1" min="0"
                                        wire:model="tanggal_mulai_daftar" x-model="tanggal_mulai_daftar" />
                                    <span class="input-group-text">s/d</span>
                                    <input id="tanggal_selesai_daftar" class="form-control" type="date" step="1" min="0"
                                        wire:model="tanggal_selesai_daftar" x-model="tanggal_selesai_daftar" />
                                </div>
                                @error('tanggal_mulai_daftar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('tanggal_selesai_daftar')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </template>
                        <template x-if="jenis_prabayar == 'Periode Tanggal'">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Berlaku</label>
                                <div class="input-group">
                                    <input id="tanggal_mulai_berlaku" class="form-control" type="date" step="1" min="0"
                                        wire:model="tanggal_mulai_berlaku" x-model="tanggal_mulai_berlaku" />
                                    <span class="input-group-text">s/d</span>
                                    <input id="tanggal_selesai_berlaku" class="form-control" type="date" step="1" min="0"
                                        wire:model="tanggal_selesai_berlaku" x-model="tanggal_selesai_berlaku" />
                                </div>
                                @error('tanggal_mulai_berlaku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('tanggal_selesai_berlaku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </template>
                    </div>
                    <div class="col-lg-8">
                        <div class="alert alert-secondary table-responsive" x-data="{
                            addTindakan() {
                                    this.tindakan.push({
                                        id: '',
                                        qty: 1,
                                        biaya: 0,
                                        subtotal: 0,
                                    });
                                    this.hitungKeuntungan();
                                },
                                hapusTindakan(index) {
                                    this.tindakan.splice(index, 1);
                                    this.hitungKeuntungan();
                                },
                                updateTindakan(index) {
                                    let row = this.tindakan[index];
                                    let selectedTindakan = this.dataTindakan.find(g => g.id == row.id);
                                    if (selectedTindakan) {
                                        row.tarif = selectedTindakan.tarif;
                                        row.harga_jual = selectedTindakan.tarif;
                                    } else {
                                        row.tarif = 0;
                                        row.harga_jual = 0;
                                    }
                                    this.calculateTindakan(index);
                                    this.hitungKeuntungan();
                                },
                                calculateTindakan(index) {
                                    let row = this.tindakan[index];
                                    row.subtotal = (parseFloat(row.qty) || 0) * (parseFloat(row.harga_jual) || 0) || 0;
                                    this.total_biaya_tindakan = this.tindakan.reduce((total, row) => total + (parseFloat(row.subtotal) || 0), 0);
                                    this.hitungKeuntungan();
                                },
                        }">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tindakan</th>
                                        <th class="w-150px">Harga Jual</th>
                                        <th class="w-100px">Qty</th>
                                        <th class="w-100px">Sub Total</th>
                                        <th class="w-5px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, index) in tindakan" :key="index">
                                        <tr>
                                            <td>
                                                <div wire:ignore>
                                                    <select class="form-control" x-model="row.id"
                                                        x-init="$($el).select2({
                                                            width: '100%',
                                                            dropdownAutoWidth: true
                                                        });
                                                        $($el).on('change', function(e) {
                                                            row.id = e.target.value;
                                                            updateTindakan(index);
                                                        });
                                                        $watch('row.id', (value) => {
                                                            if (value !== $($el).val()) {
                                                                $($el).val(value).trigger('change');
                                                            }
                                                        });">
                                                        <option value="" selected>-- Tidak Ada Tindakan --
                                                        </option>
                                                        <template x-for="tindakan in dataTindakan"
                                                            :key="tindakan.id">
                                                            <option :value="tindakan.id"
                                                                :selected="row.id == tindakan.id"
                                                                x-text="`${tindakan.nama} (Rp. ${new Intl.NumberFormat('id-ID').format(tindakan.tarif)})`">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control w-150px" min="1"
                                                    step="any" x-model.number="row.harga_jual"
                                                    @input="calculateTindakan(index)">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control w-100px" min="1"
                                                    step="any" x-model.number="row.qty"
                                                    @input="calculateTindakan(index)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control text-end w-100px"
                                                    :value="formatNumber(row.subtotal)" disabled>
                                            </td>
                                            <td>
                                                <template x-if="jenis == 'Bundling'">
                                                    <button type="button" class="btn btn-danger"
                                                        @click="hapusTindakan(index)">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr>
                                        <th colspan="3" class="text-end align-middle">Biaya Paket
                                        </th>
                                        <th>
                                            <input type="text" class="form-control text-end"
                                                :value="formatNumber(total_biaya_tindakan)" disabled>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tbody>
                                <template x-if="jenis == 'Bundling'">
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-secondary"
                                                        @click="addTindakan">
                                                        Tambah Tindakan
                                                    </button>
                                                    <template x-if="$store.wireErrors?.tindakan">
                                                        <span class="text-danger"
                                                            x-text="$store.wireErrors.tindakan"></span>
                                                    </template>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </template>
                            </table>
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
                <button type="button" onclick="window.location.href='/datamaster/paketperawatan'"
                    class="btn btn-danger" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Batal
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
        function paketPerawatan() {
            return {
                tindakan: @js($tindakan).map(row => ({
                    ...row,
                    subtotal: row.subtotal ?? ((parseFloat(row.qty) || 0) * (parseFloat(row.tarif) || 0) || 0)
                })),
                dataTindakan: @js($dataTindakan),
                tarif: @js($tarif),
                uraian: @js($uraian),
                nama: @js($nama),
                jenis: @js($jenis),
                jenis_prabayar: @js($jenis_prabayar),
                tanggal_mulai_daftar: @js($tanggal_mulai_daftar),
                tanggal_selesai_daftar: @js($tanggal_selesai_daftar),
                tanggal_mulai_berlaku: @js($tanggal_mulai_berlaku),
                tanggal_selesai_berlaku: @js($tanggal_selesai_berlaku),
                masa_aktif: @js($masa_aktif),
                formatNumber(val) {
                    if (val === null || val === undefined || isNaN(val)) return '0';
                    return new Intl.NumberFormat('id-ID').format(val);
                },

                hitungKeuntungan() {
                    this.total_biaya_tindakan = this.tindakan.reduce((total, row) => total + (parseFloat(row.subtotal) ||
                        0), 0);
                    this.keuntungan = (parseFloat(this.tarif) || 0) - this.total_biaya_tindakan;
                },

                updatedJenis() {
                    this.tindakan = [];
                    if (this.jenis === 'Prabayar') {
                        this.tindakan.push({
                            id: null,
                            qty: 1,
                            tarif: 0,
                            subtotal: 0,
                        });
                    }
                },

                syncToLivewire() {
                    if (window.Livewire) {
                        let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                        let $wire = componentId ? window.Livewire.find(componentId) : null;

                        if ($wire && typeof $wire.set === 'function') {
                            $wire.set('tindakan', JSON.parse(JSON.stringify(this.tindakan)), false);
                            $wire.set('nama', this.nama, false);
                            $wire.set('uraian', this.uraian, false);
                            $wire.set('tarif', this.tarif, false);
                        }
                    }
                },

                refreshSelect2() {
                    let root = this.$root ?? document;
                    $(root).find('select.form-control').each(function(i, el) {
                        let $el = $(el);
                        if ($el.hasClass('select2-hidden-accessible')) {
                            $el.select2('destroy');
                        }
                        $el.select2({
                            width: '100%'
                        });
                        el.dispatchEvent(new CustomEvent('updateSelect2Value', {
                            bubbles: true
                        }));
                    });
                },

                init() {
                    this.hitungKeuntungan();

                    this.$watch('tarif', () => this.hitungKeuntungan());
                    this.$watch('tindakan', () => this.hitungKeuntungan());
                }
            }
        }
    </script>
@endpush
