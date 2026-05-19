<div x-data="tarifTindakanForm()" x-init="init()" x-ref="alpineRoot">
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
        <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()">
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
                            <label class="form-label">Tarif</label>
                            <input id="tarif" class="form-control" type="number" step="1" min="0"
                                wire:model="tarif" x-model.number="tarif" @keyup="hitungKeuntungan()" />
                            @error('tarif')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select id="jenis" class="form-control" wire:model="jenis" x-model="jenis" @change="updatedJenis()"
                                @if ($data->exists) disabled @endif data-width="100%">
                                <option value="" selected>-- Pilih Jenis --</option>
                                <option value="Bundling">Bundling</option>
                                <option value="Non Bundling">Non Bundling</option>
                            </select>
                            @error('jenis')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <template x-if="jenis == 'Non Bundling'">
                            <div class="mb-3">
                                <label class="form-label">Masa Aktif <small>(Hari)</small></label>
                                <input id="masa_aktif" class="form-control" type="number" step="1" min="0"
                                    wire:model="masa_aktif" x-model.number="masa_aktif" />
                                @error('masa_aktif')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </template>
                    </div>
                    <div class="col-lg-8">
                        <!-- TABEL ALAT -->
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
                                    } else {
                                        row.tarif = 0;
                                    }
                                    this.calculateTindakan(index);
                                    this.hitungKeuntungan();
                                },
                                calculateTindakan(index) {
                                    let row = this.tindakan[index];
                                    row.subtotal = (parseFloat(row.qty) || 0) * (parseFloat(row.tarif) || 0) || 0;
                                    this.total_biaya_tindakan = this.tindakan.reduce((total, row) => total + (parseFloat(row.subtotal) || 0), 0);
                                    this.hitungKeuntungan();
                                },
                        }">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tindakan</th>
                                        <th class="w-100px">Qty</th>
                                        <th class="w-150px">Sub Total</th>
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
                                                <input type="number" class="form-control w-100px" min="1"
                                                    step="any" x-model.number="row.qty"
                                                    @input="calculateTindakan(index)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control text-end w-150px"
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
                                        <th colspan="2" class="text-end align-middle">Total Biaya Tindakan
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
        function tarifTindakanForm() {
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
                masa_aktif: @js($masa_aktif),
                formatNumber(val) {
                    if (val === null || val === undefined || isNaN(val)) return '0';
                    return `${new Intl.NumberFormat('id-ID').format(val)}`;
                },
                hitungKeuntungan() {
                    this.total_biaya_tindakan = this.tindakan.reduce((total, row) => total + (parseFloat(row.subtotal) ||
                        0), 0);
                    this.keuntungan =
                        (parseFloat(this.tarif) || 0) -
                        (parseFloat(this.total_biaya_tindakan) || 0);
                },
                updatedJenis() {
                    this.tindakan = [];
                    if (this.jenis == 'Non Bundling') {
                        this.tindakan.push({
                            id: null,
                            qty: 1,
                            tarif: 0,
                            subtotal: 0,
                        });
                    }
                },
                syncToLivewire() {
                    if (window.Livewire && window.Livewire.find) {
                        let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                        if (componentId) {
                            let $wire = window.Livewire.find(componentId);
                            if ($wire && typeof $wire.set === 'function') {
                                $wire.set('tindakan', JSON.parse(JSON.stringify(this.tindakan)), false);
                                $wire.set('nama', this.nama, false);
                                $wire.set('uraian', this.uraian, false);
                                $wire.set('tarif', this.tarif, false);
                            }
                        }
                    }
                },
                refreshSelect2() {
                    let root = this.$root ?? document;
                    $(root).find('select.form-control').each(function(i, el) {
                        if ($(el).hasClass('select2-hidden-accessible')) {
                            $(el).select2('destroy');
                        }
                        $(el).select2({
                            width: '100%'
                        });
                        el.dispatchEvent(new CustomEvent('updateSelect2Value', {
                            bubbles: true
                        }));
                    });
                },
                init() {
                    this.total_biaya_tindakan = this.tindakan.reduce((total, row) => total + (parseFloat(row.subtotal) ||
                        0), 0);
                    this.hitungKeuntungan();

                    this.$watch('tarif', () => {
                        this.hitungKeuntungan();
                    });
                    this.$watch('tindakan', () => {
                        this.hitungKeuntungan();
                    });
                }
            }
        }
    </script>
@endpush
