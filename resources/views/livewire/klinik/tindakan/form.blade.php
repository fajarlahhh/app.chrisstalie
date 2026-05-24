<div x-data="tindakanForm()" x-init="init()" x-ref="alpineRoot">
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Tindakan</li>
        <li class="breadcrumb-item active">Input</li>
    @endsection

    <h1 class="page-header">Tindakan <small>Input</small></h1>
    @include('livewire.klinik.informasipasien', ['data' => $data])

    <div class="row">
        <div class="col-md-4 ps-5">
            @php
                $historyCount = 0;
            @endphp
            
            @foreach ($data->pasien->rekamMedis->where('id', '!=', $data->id) as $row)
                @if ($row->tindakan->count() > 0)
                    @php $historyCount++; @endphp
                    <div class="history-timeline-item">
                        <div class="history-card p-3">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                <span class="fw-bold text-dark fs-12px">
                                    <i class="fa fa-calendar-alt text-primary me-2"></i>
                                    {{ $row->tindakan->first()?->created_at?->format('d M Y') }}
                                </span>
                                <span class="badge bg-primary text-white px-2 py-1 rounded-pill fs-10px">
                                    {{ $row->tindakan->count() }} Tindakan
                                </span>
                            </div>
                                @foreach ($row->tindakan as $item)
                                    <div class="history-item {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="fw-semibold text-dark fs-12px">
                                                {{ $loop->iteration }}. {{ $item->tarifTindakan->nama }}
                                            </span>
                                            <span class="badge bg-secondary text-white fs-10px">
                                                {{ $item->qty }}x
                                            </span>
                                        </div>
                                        
                                        <div class="mt-2 ps-3 text-muted fs-11px">
                                            @if ($item->dokter?->nama)
                                                <div class="d-flex align-items-start mb-1">
                                                    <i class="fa fa-user-md me-2 text-info w-15px text-center mt-0.5"></i>
                                                    <span>Dokter: <strong class="text-dark">{{ $item->dokter->nama }}</strong></span>
                                                </div>
                                            @endif
                                            @if ($item->perawat?->nama)
                                                <div class="d-flex align-items-start">
                                                    <i class="fa fa-user-nurse me-2 text-success w-15px text-center mt-0.5"></i>
                                                    <span>Perawat: <strong class="text-dark">{{ $item->perawat->nama }}</strong></span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if ($historyCount === 0)
                <div class="text-center text-muted my-5">
                    <i class="fa fa-folder-open fa-3x mb-3 text-gray-300"></i>
                    <p class="mb-0 fs-13px">Belum ada history tindakan sebelumnya</p>
                </div>
            @endif
        </div>
        <div class="col-md-8">
            <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()">
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title">Form</h4>
                    </div>
                    <div class="panel-body">
                        <template x-if="Object.keys(tindakan_paket).length > 0">
                            <table class="table table-borderless p-0">
                                <tr>
                                    <td class="p-0">
                                        <template x-for="(entry, index) in Object.entries(tindakan_paket)" :key="entry[0]">
                                            <div class="border p-3 position-relative bg-light-200 mb-2" :class="index > 0 ? 'mt-3' : ''">
                                                <h5>Paket Perawatan <span x-text="entry[0]"></span></h5>
                                                <template x-for="(row, idx) in entry[1]" :key="idx">
                                                    <div class="border p-2 bg-light-500 position-relative" :class="idx > 0 ? 'mt-3' : ''">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="flex-1">
                                                                <strong x-text="row.tindakan_nama"></strong>
                                                                <p class="mb-0 text-muted" x-text="`${row.qty}x`"></p>
                                                            </div>
                                                            <div class="flex-1">
                                                                <template x-if="row.biaya_jasa_dokter > 0">
                                                                    <select class="form-control form-control-sm mb-2" x-model="row.dokter_id">
                                                                        <option value="">-- Pilih Dokter --</option>
                                                                        <template x-for="nakes in dataNakes.filter(n => n.dokter == 1)" :key="nakes.id">
                                                                            <option :value="nakes.id" x-text="nakes.nama" :selected="row.dokter_id == nakes.id"></option>
                                                                        </template>
                                                                    </select>
                                                                </template>
                                                                <template x-if="row.biaya_jasa_perawat > 0">
                                                                    <select class="form-control form-control-sm" x-model="row.perawat_id">
                                                                        <option value="">-- Pilih Perawat --</option>
                                                                        <template x-for="nakes in dataNakes" :key="nakes.id">
                                                                            <option :value="nakes.id" x-text="nakes.nama" :selected="row.perawat_id == nakes.id"></option>
                                                                        </template>
                                                                    </select>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            </table>
                        </template>
                        <table class="table table-borderless p-0">
                            <tr>
                                <td class="p-0">
                                    <template x-for="(row, index) in tindakan" :key="index">
                                        <div class="border p-3 position-relative" :class="index > 0 ? 'mt-3' : ''">
                                            <template x-if="Object.keys(tindakan_paket).length > 0 || index > 0">
                                                <button type="button" class="btn btn-danger btn-xs position-absolute"
                                                    style="top: 5px; right: 5px; z-index: 10;" @click="hapusTindakan(index)">
                                                    &nbsp;x&nbsp;
                                                </button>
                                            </template>
                                            <div class="mb-3">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-10" wire:ignore>
                                                        <div wire:ignore>
                                                            <label class="form-label" x-text="`Tindakan ${index + 1}`"></label>
                                                            <select class="form-control" x-model="row.id"wire:ignore
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
                                                                <option value="" selected>-- Tidak Ada Tindakan --</option>
                                                                <template x-for="item in dataTindakan" :key="item.id">
                                                                    <option :value="item.id" :selected="row.id == item.id"
                                                                        x-text="`${item.nama} (Rp. ${new Intl.NumberFormat('id-ID').format(item.tarif)})`">
                                                                    </option>
                                                                </template>
                                                            </select>
                                                        </div>                                                        
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Qty</label>
                                                        <input type="number" min="1" class="form-control"
                                                            placeholder="Qty" x-model.number="row.qty" :disabled="row.paket_perawatan_id != null">
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-6">
                                                        <template x-if="row.biaya_jasa_dokter > 0">
                                                            <div class="mb-3">
                                                                <label class="form-label">Dokter</label>
                                                                <select class="form-control" x-model="row.dokter_id">
                                                                    <option value="">-- Tidak Ada Dokter --</option>
                                                                    <template x-for="nakes in dataNakes.filter(n => n.dokter == 1)"
                                                            :key="nakes.id">
                                                            <option :value="nakes.id" :selected="row.dokter_id == nakes.id"
                                                                x-text="nakes.nama"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </template>
                                                </div>
                                                    <div class="col-md-6">
                                            <template x-if="row.biaya_jasa_perawat > 0">
                                                <div class="mb-3">
                                                    <label class="form-label">Perawat</label>
                                                    <select class="form-control" x-model="row.perawat_id" >
                                                        <option value="">-- Tidak Ada Perawat --</option>
                                                        <template x-for="nakes in dataNakes" :key="nakes.id">
                                                            <option :value="nakes.id"
                                                                :selected="row.perawat_id == nakes.id" x-text="nakes.nama">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </template>
                                                </div>
                                                </div>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan</label>
                                                <textarea class="form-control" x-model="row.catatan"></textarea>
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    :id="`membutuhkan_inform_consent${index}`"
                                                    x-model="row.membutuhkan_inform_consent">
                                                <label class="form-check-label" :for="`membutuhkan_inform_consent${index}`">
                                                    Butuh Informed Consent</label>
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    :id="`membutuhkan_sitemarking${index}`"
                                                    x-model="row.membutuhkan_sitemarking">
                                                <label class="form-check-label" :for="`membutuhkan_sitemarking${index}`">
                                                    Butuh Sitemarking</label>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <button type="button" wire:loading.attr="disabled" class="btn btn-primary btn-sm"
                                        @click="tambahTindakan()">
                                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                                        Tambah Tindakan Lainnya
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="panel-footer">
                        @role('administrator|supervisor|operator')
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading class="spinner-border spinner-border-sm"></span>
                                Submit
                            </button>
                        @endrole
                        @if (isset($data->tindakan) && $data->tindakan->count() > 0)
                            <button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                                onclick="window.location.href='/klinik/resepobat/form/{{ $data->id }}'">
                                <span wire:loading class="spinner-border spinner-border-sm"></span>
                                Lanjut Resep Obat
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                            onclick="window.location.href='/klinik/tindakan'">
                            <span wire:loading class="spinner-border spinner-border-sm"></span>
                            Data
                        </button>
                        <x-alert />
                    </div>
                </div>
            
        <x-modal.konfirmasi />
    </form>
        </div>
    </div>
    
    <div wire:loading>
        <x-loading />
    </div>
</div>

@push('scripts')
    <script>
        function tindakanForm() {
            return {
                tindakan_paket: @js($tindakan_paket),
                tindakan: @js($tindakan),
                dataTindakan: @js($dataTindakan),
                dataNakes: @js($dataNakes),
                nakes_id: @js($nakes_id),


                tambahTindakan() {
                    this.tindakan.push({
                        id: null,
                        qty: 1,
                        harga: null,
                        catatan: null,
                        membutuhkan_inform_consent: false,
                        membutuhkan_sitemarking: false,
                        dokter_id: this.nakes_id,
                        perawat_id: null,
                        tindakan_nama: null,
                        biaya_jasa_dokter: 0,
                        biaya_jasa_perawat: 0,
                        biaya_alat_barang: 0,
                        biaya: 0,
                        registrasi_paket_perawatan_id: null,
                        paket_perawatan_id: null,
                        paket_perawatan_nama: null,
                    });
                },

                hapusTindakan(index) {
                    this.tindakan.splice(index, 1);
                    this.$nextTick(() => {
                        this.refreshSelect2();
                    });
                },

                updateTindakan(index) {
                    let row = this.tindakan[index];
                    let selected = this.dataTindakan.find(t => t.id == row.id);
                    row.perawat_id = null;
                    if (selected) {
                        row.harga = selected.tarif;
                        row.biaya_jasa_dokter = selected.biaya_jasa_dokter;
                        row.biaya_jasa_perawat = selected.biaya_jasa_perawat;
                        row.biaya_alat_barang = selected.biaya_alat_barang;
                        row.biaya = selected.tarif;
                    } else {
                        row.harga = null;
                        row.biaya_jasa_dokter = 0;
                        row.biaya_jasa_perawat = 0;
                        row.biaya_alat_barang = 0;
                        row.biaya = 0;
                    }
                },

                syncToLivewire() {
                    // Sinkronkan data ke Livewire
                    if (window.Livewire && window.Livewire.find) {
                        let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                        if (componentId) {
                            let $wire = window.Livewire.find(componentId);
                            if ($wire && typeof $wire.set === 'function') {
                                $wire.set('tindakan', JSON.parse(JSON.stringify(this.tindakan)), true);
                                $wire.set('tindakan_paket', JSON.parse(JSON.stringify(this.tindakan_paket)), false);
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
                },
            }
        }
    </script>
@endpush
