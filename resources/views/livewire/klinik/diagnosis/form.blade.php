<div x-data="diagnosisForm()" x-ref="alpineRoot">
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Diagnosis</li>
        <li class="breadcrumb-item active">Input</li>
    @endsection

    @push('css')
        <style>
            .history-pa-timeline {
                position: relative;
                padding-left: 20px;
                margin-left: 10px;
                border-left: 2px dashed #cbd5e1;
            }

            .history-pa-item {
                position: relative;
                margin-bottom: 24px;
            }

            .history-pa-item::before {
                content: "";
                position: absolute;
                left: -27px;
                top: 12px;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: #00acac;
                border: 3px solid #fff;
                box-shadow: 0 0 0 2px rgba(0, 172, 172, 0.2);
                z-index: 2;
                transition: all 0.2s ease-in-out;
            }

            .history-pa-item:hover::before {
                background-color: #ff5b57;
                box-shadow: 0 0 0 4px rgba(255, 91, 87, 0.2);
                transform: scale(1.2);
            }

            .history-pa-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                transition: all 0.2s ease-in-out;
            }

            .history-pa-card:hover {
                border-color: #cbd5e1;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .section-title-pa {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                color: #475569;
                letter-spacing: 0.05em;
                margin-bottom: 12px;
                border-bottom: 1px solid #f1f5f9;
                padding-bottom: 6px;
            }

            @media (min-width: 768px) {
                .border-end-md {
                    border-right: 1px solid #f1f5f9 !important;
                    padding-right: 15px;
                }
            }
        </style>
    @endpush

    <h1 class="page-header">Diagnosis <small>Input</small></h1>

    @include('livewire.klinik.informasipasien', ['data' => $data])
    <div class="row">
        <div class="col-md-5">

            <div class="panel panel-inverse mb-4" style="height: 400px; display: flex; flex-direction: column;">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-history me-2"></i>History Diagnosis</h4>
                </div>
                <div class="panel-body overflow-auto p-3" style="flex: 1; background-color: #f8fafc; min-height: 0;">
                    @php
                        $historyDiagnosisCount = 0;
                    @endphp

                    <div class="history-pa-timeline">
                        @foreach ($data->pasien->rekamMedis->where('id', '!=', $data->id) as $row)
@if ($row->diagnosis)
@php $historyDiagnosisCount++; @endphp
                                <div class="history-pa-item">
                                    <div class="history-pa-card p-3 shadow-sm border border-1 border-gray-200">
                                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                            <span class="fw-bold text-dark fs-13px">
                                                <i class="fa fa-calendar-alt text-primary me-2"></i>
                                                {{ $row->diagnosis->created_at->format('d M Y') }}
                                            </span>
                                            <span class="badge bg-primary text-white px-2 py-1 rounded-pill fs-10px">
                                                Diagnosis / Assessment
                                            </span>
                                        </div>
                                        
                                        <div class="row">
                                            <!-- Col 1: Diagnosis (ICD-10) -->
                                            <div class="col-lg-4 col-md-6 border-end-md pb-3 pb-md-0">
                                                <div class="section-title-pa"><i class="fa fa-stethoscope me-2 text-danger"></i>Diagnosis (ICD-10)</div>
                                                <ul class="list-unstyled ps-0 mb-0">
                                                    @forelse ($row->diagnosis->icd10_uraian as $item)
<li class="fs-12px text-dark mb-2 d-flex align-items-start">
                                                            <span class="badge bg-danger-transparent text-danger border border-danger me-2 px-2 py-1 rounded font-monospace fs-11px" style="background: rgba(255, 91, 87, 0.1); border-color: rgba(255, 91, 87, 0.2) !important;">{{ $item->id }}</span>
                                                            <span class="align-self-center">{{ $item->uraian }}</span>
                                                        </li>
                                                    @empty
                                                        <li class="fs-11px text-muted italic">Tidak ada ICD-10</li>
@endforelse
                                                </ul>
                                            </div>
                                            
                                            <!-- Col 2: Diagnosis Banding -->
                                            <div class="col-lg-4 col-md-6 border-end-md pb-3 pb-md-0">
                                                <div class="section-title-pa"><i class="fa fa-balance-scale me-2 text-warning"></i>Diagnosis Banding</div>
                                                <div class="fs-12px text-dark bg-light p-2 rounded" style="min-height: 60px;">
                                                    {{ $row->diagnosis->diagnosis_banding ?: '-' }}
                                                </div>
                                            </div>
                                            
                                            <!-- Col 3: Dokumentasi -->
                                            <div class="col-lg-4 col-md-12 pt-3 pt-lg-0">
                                                <div class="section-title-pa"><i class="fa fa-file-alt me-2 text-success"></i>Dokumentasi</div>
                                                <div class="row g-2">
                                                    @forelse ($row->diagnosis->file as $item)
<div class="col-6 col-sm-4 col-lg-6">
                                                            <div class="card h-100 border-1 border-gray-200">
                                                                <a href="{{ Storage::url($item->link) }}" target="_blank" class="d-block text-center bg-gray-100 p-1">
                                                                    <img src="{{ Storage::url($item->link) }}" class="img-fluid rounded" style="max-height: 80px; object-fit: contain;">
                                                                </a>
                                                                <div class="p-2 border-top">
                                                                    <div class="fw-bold fs-10px text-truncate" title="{{ $item->judul }}">{{ $item->judul }}</div>
                                                                    <div class="text-muted fs-9px text-truncate" title="{{ $item->keterangan }}">{{ $item->keterangan }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-12">
                                                            <span class="fs-11px text-muted italic">Tidak ada file dokumentasi</span>
                                                        </div>
@endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
@endif
@endforeach
                        
                        @if ($historyDiagnosisCount === 0)
<div class="text-center text-muted my-4">
                                <i class="fa fa-notes-medical fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0 fs-13px">Belum ada history diagnosis sebelumnya</p>
                            </div>
@endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()" @keydown.enter="if ($event.target.tagName !== 'TEXTAREA') $event.preventDefault()">
                <div class="panel panel-inverse">
                    <div class="panel-heading overflow-auto d-flex">
                        <h4 class="panel-title">Assessment (Penilaian)</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-borderless p-0">
                            <thead>
                                <tr>
                                    <th class="p-0">ICD 10</th>
                                    <th class="w-5px p-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, index) in icd10" :key="index">
                                    <tr>
                                        <th class="p-0" wire:ignore>
                                            <select class="form-control" x-model="row.id" x-init="$($el).select2({
                                                width: '100%',
                                                dropdownAutoWidth: true
                                            });
                                            $($el).on('change', function(e) {
                                                row.id = e.target.value;
                                                updateRow(index);
                                            });
                                            $watch('row.id', (value) => {
                                                if (value !== $($el).val()) {
                                                    $($el).val(value).trigger('change');
                                                }
                                            });">
                                        
                                                <option value="" selected>-- Pilih ICD 10 --</option>
                                                <template x-for="item in dataIcd10" :key="item.id">
                                                    <option :value="item.id" :selected="row.id == item.id"
                                                        x-text="`${item.id} - ${item.uraian}`">
                                                    </option>
                                                </template>
                                            </select>
                                        </th>
                                        <th class="align-middle w-5px pt-0 pb-0 pr-0">
                                            <template x-if="index > 0">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    @click="hapusDiagnosis(index)">
                                                    <span x-show="$wire.__instance.loading"
                                                        class="spinner-border spinner-border-sm"></span>
                                                    <span x-show="!$wire.__instance.loading">x</span>
                                                </button>
                                            </template>
                                        </th>
                                    </tr>
                                </template>
                            </tbody>
                            <tr class="p-0">
                                <td colspan="3" class="p-0 pt-1 pb-0 pr-0">
                                    <button type="button" class="btn btn-primary btn-sm" @click="addDiagnosis"
                                        wire:loading.attr="disabled">
                                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                                        Tambah ICD 10
                                    </button>
                                </td>
                            </tr>
                        </table>
                        <div class="form-group mb-3">
                            <label for="diagnosis_banding">Diagnosis Banding (Differential Diagnosis)</label>
                            <textarea id="diagnosis_banding" class="form-control" wire:model="diagnosis_banding"
                                placeholder="Tuliskan kemungkinan diagnosis lain yang perlu dipertimbangkan..."></textarea>
                            @error('diagnosis_banding')
<div class="text-danger">{{ $message }}</div>
@enderror
                    </div>
                    <div class="p-3 bg-light border rounded">
                        Dokumentasi :
                        <x-upload :fileDiupload="$fileDiupload" :fileDihapus="$fileDihapus" />
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
                    @if (isset($data->diagnosis) && $data->diagnosis->count() > 0)
<button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                            onclick="window.location.href='/klinik/tindakan/form/{{ $data->id }}'">
                            <span wire:loading class="spinner-border spinner-border-sm"></span>
                            Lanjut Tindakan
                        </button>
@endif
                        <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                        onclick="window.location.href='/klinik/diagnosis'">
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
                                function diagnosisForm() {
                                    return {
                                        icd10: @js($icd10).map(row => ({
                                            ...row,
                                        })),
                                        dataIcd10: @js($dataIcd10),
                                        diagnosis_banding: @js($diagnosis_banding),
                                        fileDiupload: @js($fileDiupload),
                                        addDiagnosis() {
                                            this.icd10.push({
                                                id: '',
                                            });
                                            this.$nextTick(() => {
                                                this.refreshSelect2();
                                            });
                                        },
                                        hapusDiagnosis(index) {
                                            this.icd10.splice(index, 1);
                                            this.$nextTick(() => {
                                                this.refreshSelect2();
                                            });
                                        },
                                        updateRow(index) {
                                            let row = this.icd10[index];
                                            let selectedIcd10 = this.dataIcd10.find(i => i.id == row.id);
                                            if (selectedIcd10) {
                                                row.uraian = selectedIcd10.uraian;
                                            } else {
                                                row.uraian = '';
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
                                        syncToLivewire() {
                                            if (window.Livewire && window.Livewire.find) {
                                                let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                                                if (componentId) {
                                                    let $wire = window.Livewire.find(componentId);
                                                    if ($wire && typeof $wire.set === 'function') {
                                                        $wire.set('icd10', JSON.parse(JSON.stringify(this.icd10)), true);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            </script>
                        @endpush)
