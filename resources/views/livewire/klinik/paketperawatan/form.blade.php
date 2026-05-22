<div x-data="tindakanForm()" x-init="init()" x-ref="alpineRoot">
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Paket Perawatan</li>
        <li class="breadcrumb-item active">Input</li>
    @endsection

    <h1 class="page-header">Paket Perawatan <small>Input</small></h1>

    @include('livewire.klinik.informasipasien', ['data' => $data])
    <div class="row">
        <div class="col-md-4 pl-3">
            @php
                $historyCount = 0;
            @endphp
            
            <div class="history-timeline">
                @foreach ($data->pasien->rekamMedis->where('id', '!=', $data->id)->take(20) as $row)
                    @if ($row->registrasiPaketPerawatan->count() > 0)
                        @php $historyCount++; @endphp
                        <div class="history-timeline-item">
                            <div class="history-card p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <span class="fw-bold text-dark fs-12px">
                                        <i class="fa fa-calendar-alt text-primary me-2"></i>
                                        {{ $row->registrasiPaketPerawatan->first()?->created_at?->format('d M Y') }}
                                    </span>
                                    <span class="badge bg-primary text-white px-2 py-1 rounded-pill fs-10px">
                                        {{ $row->registrasiPaketPerawatan->count() }} Paket Perawatan
                                    </span>
                                </div>
                                @foreach ($row->registrasiPaketPerawatan as $item)
                                    <div class="history-item {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="fw-semibold text-dark fs-12px">
                                                {{ $loop->iteration }}. {{ $item->nama }}
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
        </div>
        <div class="col-md-8">
            <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()">
                <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title">Form</h4>
                    </div>
                    <div class="panel-body">                
                        <table class="table table-borderless p-0">
                            <tr>
                                <td class="p-0">
                                    @php
                                        $i = 0;
                                    @endphp
                                    <template x-for="(row, index) in registrasi_paket_perawatan" :key="index">
                                        <div class="border p-3 position-relative" :class="index > 0 ? 'mt-3' : ''">
                                            <template x-if="index > 0">
                                                <button type="button" class="btn btn-danger btn-xs position-absolute"
                                                    style="top: 5px; right: 5px; z-index: 10;" @click="hapusTindakan(index)">
                                                    &nbsp;x&nbsp;
                                                </button>
                                            </template>
                                            <div class="mb-3">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-10" wire:ignore>
                                                        <div wire:ignore>
                                                            <label class="form-label" x-text="`Paket Perawatan ${index + 1}`"></label>
                                                            <select class="form-control" x-model="row.id" wire:ignore
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
                                                                <option value="" selected>-- Tidak Ada Paket Perawatan --</option>
                                                                <template x-for="item in dataPaketPerawatan" :key="item.id">
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
                                                            placeholder="Qty" x-model.number="row.qty">
                                                    </div>
                                                </div>
                                            </div>
                                            <template x-if="row.id && dataPaketPerawatan.find(t => t.id == row.id)">
                                                <div class="note alert-primary mb-3 border-0 shadow-sm" style="border-left: 4px solid var(--bs-primary) !important; border-radius: 8px;">
                                                    <div class="note-content w-100" x-data="{
                                                        get selectedPkg() {
                                                            return dataPaketPerawatan.find(t => t.id == row.id);
                                                        }
                                                    }">
                                                        <h5 class="mb-3 d-flex align-items-center text-primary fw-bold">
                                                            <i class="fa fa-box-open me-2 fs-16px text-primary"></i> Detail Paket Perawatan
                                                        </h5>

                                                        <div class="row g-2 mb-3">
                                                            <div class="col-4">
                                                                <div class="bg-white bg-opacity-70 p-2 rounded border h-100 text-center"
                                                                    style="border-color: rgba(var(--bs-primary-rgb), 0.15) !important;">
                                                                    <small class="text-muted d-block uppercase fw-bold mb-1"
                                                                        style="font-size: 10px; letter-spacing: 0.5px;">Tarif Paket</small>
                                                                    <span class="fs-13px fw-bold text-primary" x-text="'Rp. ' + new Intl.NumberFormat('id-ID').format(selectedPkg.tarif)"></span>
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
                                                                    style="font-size: 9px;" x-text="`${selectedPkg.detail ? selectedPkg.detail.length : 0} Item`"></span>
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
                                                                        <template x-for="(detailItem, detailIndex) in (selectedPkg.detail || [])" :key="detailIndex">
                                                                            <tr>
                                                                                <td class="ps-2 py-2 border-0 text-dark fw-medium" x-text="detailItem.nama"></td>
                                                                                <td class="text-end pe-2 py-2 border-0 fw-bold text-primary" x-text="`${detailItem.qty}x`"></td>
                                                                            </tr>
                                                                        </template>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan</label>
                                                <textarea class="form-control" x-model="row.catatan"></textarea>
                                            </div>
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <button type="button" wire:loading.attr="disabled" class="btn btn-primary btn-sm"
                                        @click="tambahTindakan()">
                                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                                        Tambah Paket Lainnya
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
                        @if (isset($data->registrasiPaketPerawatan) && $data->registrasiPaketPerawatan->count() > 0)
                            <button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                                onclick="window.location.href='/klinik/tindakan/form/{{ $data->id }}'">
                                <span wire:loading class="spinner-border spinner-border-sm"></span>
                                Lanjut Tindakan
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                            onclick="window.location.href='/klinik/paketperawatan'">
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
                registrasi_paket_perawatan: @js($registrasi_paket_perawatan),
                dataPaketPerawatan: @js($dataPaketPerawatan),

                tambahTindakan() {
                    this.registrasi_paket_perawatan.push({
                        id: null,
                        qty: 1,
                        biaya: null,
                        catatan: null,
                    });
                },

                hapusTindakan(index) {
                    this.registrasi_paket_perawatan.splice(index, 1);
                    this.$nextTick(() => {
                        this.refreshSelect2();
                    });
                },

                updateTindakan(index) {
                    let row = this.registrasi_paket_perawatan[index];
                    let selected = this.dataPaketPerawatan.find(t => t.id == row.id);
                    if (selected) {
                        row.biaya = selected.tarif;
                    } else {
                        row.biaya = null;
                    }
                },

                syncToLivewire() {
                    // Sinkronkan data ke Livewire
                    if (window.Livewire && window.Livewire.find) {
                        let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                        if (componentId) {
                            let $wire = window.Livewire.find(componentId);
                            if ($wire && typeof $wire.set === 'function') {
                                $wire.set('registrasi_paket_perawatan', JSON.parse(JSON.stringify(this.registrasi_paket_perawatan)), true);
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
