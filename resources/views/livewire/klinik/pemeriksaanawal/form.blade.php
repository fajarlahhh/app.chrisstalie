<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Pemeriksaan Awal</li>
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

            .vital-badge {
                background: #f8fafc;
                color: #334155;
                border-radius: 6px;
                padding: 6px 10px;
                font-size: 11px;
                font-weight: 600;
                border: 1px solid #e2e8f0;
                display: inline-block;
                margin-bottom: 6px;
                margin-right: 6px;
                transition: all 0.15s ease;
            }

            .vital-badge:hover {
                background: #f1f5f9;
                border-color: #cbd5e1;
            }

            .vital-badge i {
                width: 14px;
                text-align: center;
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

            @media (min-width: 992px) {
                .border-end-lg {
                    border-right: 1px solid #f1f5f9 !important;
                    padding-right: 15px;
                }
            }
        </style>
    @endpush

    <h1 class="page-header">Pemeriksaan Awal <small>Input</small></h1>

    @include('livewire.klinik.informasipasien', ['data' => $data])
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation" wire:ignore>
            <a href="#default-tab-0" data-bs-toggle="tab" class="nav-link active" aria-selected="true" role="tab">
                <span class="d-sm-none">Pemeriksaan Awal</span>
                <span class="d-sm-block d-none">Pemeriksaan Awal</span>
            </a>
        </li>
        <li class="nav-item" role="presentation" wire:ignore>
            <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link" aria-selected="true" role="tab">
                <span class="d-sm-none">TUG</span>
                <span class="d-sm-block d-none">Tes Up and Go</span>
            </a>
        </li>
    </ul>
    <div class="tab-content panel rounded-0 p-3 m-0">
        <div class="tab-pane fade active show" id="default-tab-0" role="tabpanel" wire:ignore.self>
            <div class="panel panel-inverse mb-4" style="height: 500px; display: flex; flex-direction: column;">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-history me-2"></i>History Pemeriksaan Awal</h4>
                </div>
                <div class="panel-body overflow-auto p-3" style="flex: 1; background-color: #f8fafc; min-height: 0;">
                    @php
                        $historyCount = 0;
                    @endphp

                    <div class="history-pa-timeline">
                        @foreach ($data->pasien->rekamMedis->where('id', '!=', $data->id) as $row)
@if ($row->pemeriksaanAwal)
@php $historyCount++; @endphp
                                <div class="history-pa-item">
                                    <div class="history-pa-card p-3 shadow-sm border border-1 border-gray-200">
                                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                            <span class="fw-bold text-dark fs-13px">
                                                <i class="fa fa-calendar-alt text-primary me-2"></i>
                                                {{ $row->pemeriksaanAwal->created_at->format('d M Y') }}
                                            </span>
                                            <span class="badge bg-primary text-white px-2 py-1 rounded-pill fs-10px">
                                                Pemeriksaan Awal
                                            </span>
                                        </div>
                                        
                                        <div class="row">
                                            <!-- Col 1: Anamnesis -->
                                            <div class="col-lg-4 col-md-6 border-end-md pb-3 pb-md-0">
                                                <div class="section-title-pa"><i class="fa fa-comments me-2 text-primary"></i>Anamnesis (Subjective)</div>
                                                <div class="fs-12px mb-2"><strong>Keluhan Utama:</strong><br><span class="text-dark">{{ $row->pemeriksaanAwal->keluhan_utama }}</span></div>
                                                <div class="fs-12px mb-2"><strong>Penyakit Sekarang:</strong><br><span class="text-dark">{{ $row->pemeriksaanAwal->riwayat_sekarang }}</span></div>
                                                <div class="fs-12px mb-2"><strong>Riwayat Dahulu & Keluarga:</strong><br><span class="text-dark">{{ $row->pemeriksaanAwal->riwayat_dahulu }}</span></div>
                                                <div class="fs-12px"><strong>Riwayat Alergi:</strong><br><span class="text-danger"><strong>{{ $row->pemeriksaanAwal->riwayat_alergi ?: '-' }}</strong></span></div>
                                            </div>
                                            
                                            <!-- Col 2: Pemeriksaan Fisik -->
                                            <div class="col-lg-4 col-md-6 border-end-lg pb-3 pb-lg-0">
                                                <div class="section-title-pa"><i class="fa fa-user-md me-2 text-success"></i>Pemeriksaan Fisik (Objective)</div>
                                                
                                                <div class="mb-2">
                                                    <span class="fs-11px text-muted fw-bold d-block mb-1">Tanda-Tanda Vital:</span>
                                                    <div class="d-flex flex-wrap">
                                                        <div class="vital-badge"><i class="fa fa-heartbeat text-danger me-1"></i> {{ $row->pemeriksaanAwal->tekanan_darah }}</div>
                                                        <div class="vital-badge"><i class="fa fa-heart text-danger me-1"></i> {{ $row->pemeriksaanAwal->nadi }}x/m</div>
                                                        <div class="vital-badge"><i class="fa fa-lungs text-info me-1"></i> {{ $row->pemeriksaanAwal->pernapasan }}x/m</div>
                                                        <div class="vital-badge"><i class="fa fa-thermometer-half text-warning me-1"></i> {{ $row->pemeriksaanAwal->suhu }}°C</div>
                                                        <div class="vital-badge"><i class="fa fa-wind text-primary me-1"></i> SpO2: {{ $row->pemeriksaanAwal->saturasi_o2 }}%</div>
                                                        <div class="vital-badge"><i class="fa fa-weight text-secondary me-1"></i> {{ $row->pemeriksaanAwal->berat_badan }}kg</div>
                                                        <div class="vital-badge"><i class="fa fa-ruler-vertical text-secondary me-1"></i> {{ $row->pemeriksaanAwal->tinggi_badan }}cm</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <span class="fs-10px text-muted d-block">Kesadaran:</span>
                                                        <span class="fs-12px fw-bold text-dark">{{ $row->pemeriksaanAwal->kesadaran }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="fs-10px text-muted d-block">Kesan Sakit:</span>
                                                        <span class="fs-12px fw-bold text-dark">{{ $row->pemeriksaanAwal->kesan_sakit }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="fs-10px text-muted d-block">Status Gizi:</span>
                                                        <span class="fs-12px fw-bold text-dark">{{ $row->pemeriksaanAwal->status_gizi }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <span class="fs-11px text-muted fw-bold d-block mb-1">Head to Toe:</span>
                                                    <div class="fs-11px text-dark bg-light p-2 rounded">
                                                        <div class="mb-1"><strong>Kepala/THT/Leher:</strong> {{ $row->pemeriksaanAwal->kepala_normal == 1 ? 'Normal' : $row->pemeriksaanAwal->kepala_temuan }}</div>
                                                        <div class="mb-1"><strong>Jantung:</strong> {{ $row->pemeriksaanAwal->jantung_normal == 1 ? 'Normal' : $row->pemeriksaanAwal->jantung_temuan }}</div>
                                                        <div class="mb-1"><strong>Paru:</strong> {{ $row->pemeriksaanAwal->paru_normal == 1 ? 'Normal' : $row->pemeriksaanAwal->paru_temuan }}</div>
                                                        <div class="mb-1"><strong>Abdomen:</strong> {{ $row->pemeriksaanAwal->abdomen_normal == 1 ? 'Normal' : $row->pemeriksaanAwal->abdomen_temuan }}</div>
                                                        <div><strong>Ekstremitas:</strong> {{ $row->pemeriksaanAwal->ekstremitas_normal == 1 ? 'Normal' : $row->pemeriksaanAwal->ekstremitas_temuan }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Col 3: Diagnostik & Perencanaan -->
                                            <div class="col-lg-4 col-md-12 pt-3 pt-lg-0">
                                                <div class="section-title-pa"><i class="fa fa-stethoscope me-2 text-warning"></i>Diagnostik & Rencana</div>
                                                <div class="fs-12px mb-3">
                                                    <strong>Diagnosis Kerja:</strong>
                                                    <div class="text-dark bg-light p-2 rounded border-start border-warning border-3 mt-1 fw-bold">
                                                        {{ $row->pemeriksaanAwal->diagnosis_kerja }}
                                                    </div>
                                                </div>
                                                <div class="fs-12px">
                                                    <strong>Rencana Awal:</strong>
                                                    <div class="text-dark bg-light p-2 rounded mt-1">
                                                        {!! nl2br(e($row->pemeriksaanAwal->rencana_awal)) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
@endif
@endforeach
                        
                        @if ($historyCount === 0)
<div class="text-center text-muted my-5">
                                <i class="fa fa-notes-medical fa-3x mb-3 text-gray-300"></i>
                                <p class="mb-0 fs-13px">Belum ada history pemeriksaan awal sebelumnya</p>
                            </div>
@endif
                    </div>
                </div>
            </div>
            <form wire:submit.prevent="submitPemeriksaanAwal">
                <div class="panel panel-inverse bg-gray-100">
                    <div class="panel-heading overflow-auto d-flex">
                        <h4 class="panel-title">Anamnesis (Subjective)</h4>
                    </div>
                    <div class="panel-body">
                        <div class="mb-3">
                            <label for="keluhan_utama" class="form-label">Keluhan Utama</label>
                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama"
                                placeholder="Apa keluhan utama yang membawa pasien datang?" wire:model="keluhan_utama"></textarea>
                            @error('keluhan_utama')
<span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                    <div class="mb-3">
                        <label for="riwayat_sekarang" class="form-label">Riwayat Penyakit Sekarang</label>
                        <textarea class="form-control" id="riwayat_sekarang" name="riwayat_sekarang"
                            placeholder="Jelaskan detail keluhan sejak kapan, lokasi, kronologi, kualitas, kuantitas, faktor yang memperberat dan memperingan, serta gejala penyerta."
                            wire:model="riwayat_sekarang"></textarea>
                        @error('riwayat_sekarang')
<span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                    <div class="mb-3">
                        <label for="riwayat_dahulu" class="form-label">Riwayat Penyakit Dahulu & Riwayat
                            Penyakit Keluarga</label>
                        <textarea class="form-control" id="riwayat_dahulu" name="riwayat_dahulu"
                            placeholder="Sebutkan penyakit kronis (hipertensi, DM), riwayat operasi, rawat inap, dan penyakit signifikan di keluarga."
                            wire:model="riwayat_dahulu"></textarea>
                        @error('riwayat_dahulu')
<span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                    <div class="mb-3">
                        <label for="riwayat_alergi" class="form-label">Riwayat Alergi</label>
                        <input type="text" class="form-control" id="riwayat_alergi" name="riwayat_alergi"
                            placeholder="Sebutkan alergi obat atau makanan, jika tidak ada tulis 'Tidak Ada'"
                            wire:model="riwayat_alergi">
                        @error('riwayat_alergi')
<span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                </div>
            </div>

            <div class="panel panel-inverse bg-gray-100">
                <div class="panel-heading overflow-auto d-flex">
                    <h4 class="panel-title">Pemeriksaan Fisik (Objective)</h4>
                </div>
                <div class="panel-body">
                    <h5 class="mb-3">Tanda-Tanda Vital</h5>
                    <div class="row vital-signs-grid">
                        <div class="col-md-3 mb-3">
                            <label for="tekanan_darah" class="form-label">Tekanan Darah (mmHg)</label>
                            <input type="text" class="form-control" id="tekanan_darah" name="tekanan_darah"
                                placeholder="120/80" wire:model="tekanan_darah">
                            @error('tekanan_darah')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nadi" class="form-label">Nadi (x/menit)</label>
                            <input type="number" class="form-control" id="nadi" name="nadi"
                                placeholder="80" wire:model="nadi">
                            @error('nadi')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="pernapasan" class="form-label">Pernapasan (x/menit)</label>
                            <input type="number" class="form-control" id="pernapasan" name="pernapasan"
                                placeholder="18" wire:model="pernapasan">
                            @error('pernapasan')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="suhu" class="form-label">Suhu (°C)</label>
                            <input type="number" class="form-control" id="suhu" name="suhu"
                                step="0.1" placeholder="36.5" wire:model="suhu">
                            @error('suhu')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="saturasi_o2" class="form-label">SpO2 (%)</label>
                            <input type="number" class="form-control" id="saturasi_o2" name="saturasi_o2"
                                placeholder="98" wire:model="saturasi_o2">
                            @error('saturasi_o2')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                            <input type="number" class="form-control" id="berat_badan" name="berat_badan"
                                step="0.1" placeholder="65.5" wire:model="berat_badan">
                            @error('berat_badan')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" class="form-control" id="tinggi_badan" name="tinggi_badan"
                                placeholder="170" wire:model="tinggi_badan">
                            @error('tinggi_badan')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Keadaan Umum & Kesadaran</h5>
                    <div class="row patient-info-grid">
                        <div class="col-md-4 mb-3">
                            <label for="kesadaran" class="form-label">Tingkat Kesadaran</label>
                            <select class="form-select" id="kesadaran" name="kesadaran" wire:model="kesadaran">
                                <option value="Compos Mentis">Compos Mentis</option>
                                <option value="Apatis">Apatis</option>
                                <option value="Somnolen">Somnolen</option>
                                <option value="Sopor">Sopor</option>
                                <option value="Koma">Koma</option>
                            </select>
                            @error('kesadaran')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kesan_sakit" class="form-label">Kesan Sakit</label>
                            <select class="form-select" id="kesan_sakit" name="kesan_sakit"
                                wire:model="kesan_sakit">
                                <option value="Tidak Tampak Sakit">Tidak Tampak Sakit</option>
                                <option value="Sakit Ringan">Sakit Ringan</option>
                                <option value="Sakit Sedang">Sakit Sedang</option>
                                <option value="Sakit Berat">Sakit Berat</option>
                            </select>
                            @error('kesan_sakit')
<span class="text-danger">{{ $message }}</span>
@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="status_gizi" class="form-label">Status Gizi</label>
                            <select class="form-select" id="status_gizi" name="status_gizi"
                                wire:model="status_gizi">
                                <option value="Baik">Baik</option>
                                <option value="Kurang">Kurang</option>
                                <option value="Buruk">Buruk</option>
                                <option value="Lebih">Lebih / Obesitas</option>
                            </select>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Pemeriksaan Head to Toe</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="pemeriksaan-item" x-data="{
                                checked: @entangle('kepala_normal'),
                                init() {
                                    this.$watch('checked', value => {
                                        if (value) $wire.set('kepala_temuan', '');
                                    })
                                }
                            }" x-init="init">
                                <h6>Kepala, Mata, THT, Leher</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="kepala_normal"
                                        x-model="checked" wire:model="kepala_normal">
                                    <label class="form-check-label" for="kepala_normal">Dalam Batas
                                        Normal</label>
                                </div>
                                <textarea class="form-control" id="kepala_temuan" name="kepala_temuan" placeholder="Jelaskan temuan abnormal..."
                                    wire:model="kepala_temuan" :disabled="checked"></textarea>
                                <template x-if="!checked">
                                    <span>
                                        @error('kepala_temuan')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </span>
                                </template>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="pemeriksaan-item" x-data="{
                                checked: @entangle('jantung_normal'),
                                init() {
                                    this.$watch('checked', value => {
                                        if (value) $wire.set('jantung_temuan', '');
                                    })
                                }
                            }" x-init="init">
                                <h6>Thorax - Jantung</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="jantung_normal"
                                        x-model="checked" wire:model="jantung_normal">
                                    <label class="form-check-label" for="jantung_normal">Dalam Batas
                                        Normal</label>
                                </div>
                                <textarea class="form-control" id="jantung_temuan" name="jantung_temuan" placeholder="Jelaskan temuan abnormal..."
                                    wire:model="jantung_temuan" :disabled="checked"></textarea>
                                <template x-if="!checked">
                                    <span>
                                        @error('jantung_temuan')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </span>
                                </template>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="pemeriksaan-item" x-data="{
                                checked: @entangle('paru_normal'),
                                init() {
                                    this.$watch('checked', value => {
                                        if (value) $wire.set('paru_temuan', '');
                                    })
                                }
                            }" x-init="init">
                                <h6>Thorax - Paru</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="paru_normal"
                                        x-model="checked" wire:model="paru_normal">
                                    <label class="form-check-label" for="paru_normal">Dalam Batas
                                        Normal</label>
                                </div>
                                <textarea class="form-control" id="paru_temuan" name="paru_temuan" placeholder="Jelaskan temuan abnormal..."
                                    wire:model="paru_temuan" :disabled="checked"></textarea>
                                <template x-if="!checked">
                                    <span>
                                        @error('paru_temuan')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </span>
                                </template>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="pemeriksaan-item" x-data="{
                                checked: @entangle('abdomen_normal'),
                                init() {
                                    this.$watch('checked', value => {
                                        if (value) $wire.set('abdomen_temuan', '');
                                    })
                                }
                            }" x-init="init">
                                <h6>Abdomen</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="abdomen_normal"
                                        x-model="checked" wire:model="abdomen_normal">
                                    <label class="form-check-label" for="abdomen_normal">Dalam Batas
                                        Normal</label>
                                </div>
                                <textarea class="form-control" id="abdomen_temuan" name="abdomen_temuan" placeholder="Jelaskan temuan abnormal..."
                                    wire:model="abdomen_temuan" :disabled="checked"></textarea>
                                <template x-if="!checked">
                                    <span>
                                        @error('abdomen_temuan')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </span>
                                </template>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="pemeriksaan-item" x-data="{
                                checked: @entangle('ekstremitas_normal'),
                                init() {
                                    this.$watch('checked', value => {
                                        if (value) $wire.set('ekstremitas_temuan', '');
                                    })
                                }
                            }" x-init="init">
                                <h6>Ekstremitas & Kulit</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="ekstremitas_normal"
                                        x-model="checked" wire:model="ekstremitas_normal">
                                    <label class="form-check-label" for="ekstremitas_normal">Dalam Batas
                                        Normal</label>
                                </div>
                                <textarea class="form-control" id="ekstremitas_temuan" name="ekstremitas_temuan"
                                    placeholder="Jelaskan temuan abnormal..." wire:model="ekstremitas_temuan" :disabled="checked"></textarea>
                                <template x-if="!checked">
                                    <span>
                                        @error('ekstremitas_temuan')
<span class="text-danger">{{ $message }}</span>
@enderror
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-inverse bg-gray-100">
                <div class="panel-heading overflow-auto d-flex">
                    <h4 class="panel-title">Diagnosis & Perencanaan (Assessment & Plan)</h4>
                </div>
                <div class="panel-body">
                    <div class="mb-3">
                        <label for="diagnosis_kerja" class="form-label">Diagnosis Kerja</label>
                        <textarea class="form-control" id="diagnosis_kerja" name="diagnosis_kerja"
                            placeholder="Tuliskan diagnosis kerja berdasarkan anamnesis dan pemeriksaan fisik." wire:model="diagnosis_kerja"></textarea>
                        @error('diagnosis_kerja')
<span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                    <div class="mb-3">
                        <label for="rencana_awal" class="form-label">Rencana Awal</label>
                        <textarea class="form-control" id="rencana_awal" name="rencana_awal"
                            placeholder="Tuliskan rencana awal, meliputi:&#10;- Terapi/Tindakan&#10;- Pemeriksaan Penunjang (jika perlu)&#10;- Edukasi Pasien&#10;- Rencana Rujukan (jika perlu)"
                            wire:model="rencana_awal"></textarea>
                        @error('rencana_awal')
<span class="text-danger">{{ $message }}</span>
@enderror
                    </div>
                </div>
            </div>
            <hr>
            @role('administrator|supervisor|operator')
<button type="button" x-init="$($el).on('click', function() {
    $('#modal-konfirmasi').modal('show');
})" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Submit
                    </button>
@endrole
            @if (isset($data->pemeriksaanAwal) && $data->pemeriksaanAwal->count() > 0)
<button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/diagnosis/form/{{ $data->id }}'">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Lanjut Diagnosis
                </button>
@endif
                        <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                        onclick="window.location.href='/klinik/pemeriksaanawal'">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Data
                        </button>
                        <x-alert />

                        <x-modal.konfirmasi />
                        </form>
                        </div>
                        <div class="tab-pane fade" id="default-tab-1" role="tabpanel" wire:ignore.self>
                        <div class="panel panel-inverse mb-4"
                        style="height: 400px; display: flex; flex-direction: column;">
                        <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-history me-2"></i>History Tes Up
                        and Go</h4>
                        </div>
                        <div class="panel-body overflow-auto p-3"
                        style="flex: 1; background-color: #f8fafc; min-height: 0;">
                        @php
                            $historyTugCount = 0;
                        @endphp

                        <div class="history-pa-timeline">
                        @foreach ($data->pasien->rekamMedis as $row)
                        @if ($row->tug)
                        @php $historyTugCount++; @endphp
                        <div class="history-pa-item">
                        <div class="history-pa-card p-3 shadow-sm border border-1 border-gray-200">
                        <div
                        class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <span class="fw-bold text-dark fs-13px">
                        <i class="fa fa-calendar-alt text-primary me-2"></i>
                        {{ $row->tug->created_at->format('d M Y') }}
                        </span>
                        @php
                            $risiko = strtolower($row->tug->risiko_jatuh ?? '');
                            $risikoColor = 'bg-success';
                            if ($risiko === 'sedang') {
                                $risikoColor = 'bg-warning text-dark';
                            } elseif ($risiko === 'tinggi') {
                                $risikoColor = 'bg-danger text-white';
                            }
                        @endphp
                        <span class="badge {{ $risikoColor }} px-2 py-1 rounded-pill fs-10px">
                        Risiko Jatuh: {{ $row->tug->risiko_jatuh }}
                        </span>
                        </div>

                        <div class="row">
                        <!-- Col 1: Waktu & Observasi -->
                        <div class="col-md-6 border-end-md pb-3 pb-md-0">
                        <div class="section-title-pa"><i
                        class="fa fa-running me-2 text-info"></i>Hasil Tes</div>
                        <div class="fs-12px mb-2">
                        <strong>Waktu Tes:</strong>
                        <span class="badge bg-info text-white ms-1 fs-11px">{{ $row->tug->waktu_tes_detik }}
                        detik</span>
                        </div>
                        <div class="fs-12px">
                        <strong>Observasi Kualitatif Gerakan:</strong>
                        <ul class="list-unstyled ps-0 mt-1 mb-0">
                        @php
                            $observasi = is_string($row->tug->observasi_kualitatif)
                                ? json_decode($row->tug->observasi_kualitatif, true) ?? []
                                : (is_array($row->tug->observasi_kualitatif)
                                    ? $row->tug->observasi_kualitatif
                                    : []);
                        @endphp
                        @forelse ($observasi as $item)
                        <li class="fs-11px text-dark mb-1">
                        <i class="fa fa-check-circle text-info me-1"></i> {{ $item }}
                        </li>
                    @empty
                        <li class="fs-11px text-muted italic">Tidak ada observasi abnormal</li>
                    @endforelse
                    </ul>
                    </div>
                    </div>

                    <!-- Col 2: Rekomendasi & Catatan -->
                    <div class="col-md-6 pt-3 pt-md-0">
                    <div class="section-title-pa"><i
                    class="fa fa-clipboard-list me-2 text-warning"></i>Catatan &
                    Rekomendasi</div>
                    <div class="fs-12px">
                    <strong>Rekomendasi / Catatan:</strong>
                    <div class="text-dark bg-light p-2 rounded mt-1 fs-11px" style="min-height: 50px;">
                    {{ $row->tug->catatan ?: '-' }}
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    @endif
                    @endforeach

                    @if ($historyTugCount === 0)
                    <div class="text-center text-muted my-4">
                    <i class="fa fa-running fa-3x mb-3 text-gray-300"></i>
                    <p class="mb-0 fs-13px">Belum ada history Tes Up and Go sebelumnya</p>
                    </div>
                    @endif
                    </div>
                    </div>
                    </div>
                    <form wire:submit.prevent="submitTug">
                    <div class="note alert-secondary mb-2">
                    <div class="note-content">
                    <h3>Hasil Tes</h3>
                    <div class="mb-3">
                    <label class="form-label">Waktu yang Dibutuhkan:</label>
                    <div class="input-group"> <input type="number" id="waktu_tes_detik"
                    name="waktu_tes_detik" placeholder="Contoh: 11.5" wire:model="waktu_tes_detik"
                    step="0.01" class="form-control" required>
                    <div class="input-group-append">
                    <span class="input-group-text bg-info text-white">detik</span>
                    </div>
                    </div>
                    <small class="text-muted">Waktu > 14 detik dapat mengindikasikan peningkatan risiko
                    jatuh.</small>
                    @error('waktu_tes_detik')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                    <label class="form-label">Observasi Kualitatif Gerakan (Pilih yang sesuai):</label>
                    @php
                        // Daftar observasi kualitatif sebagai array agar lebih mudah di-maintain dan menghindari duplikasi value
                        $observasiOptions = [
                            [
                                'id' => 'observasi_lambat_ragu',
                                'label' => 'Mulai dengan lambat/ragu-ragu',
                                'value' => 'Mulai dengan lambat/ragu-ragu',
                                'col' => 12,
                            ],
                            [
                                'id' => 'observasi_tidak_seimbang',
                                'label' => 'Kehilangan keseimbangan saat berjalan',
                                'value' => 'Kehilangan keseimbangan saat berjalan',
                                'col' => 12,
                            ],
                            [
                                'id' => 'observasi_langkah_pendek',
                                'label' => 'Langkah pendek dan tidak normal',
                                'value' => 'Langkah pendek dan tidak normal',
                                'col' => 12,
                            ],
                            [
                                'id' => 'observasi_berhenti_saat_jalan',
                                'label' => 'Berhenti saat sedang berjalan',
                                'value' => 'Berhenti saat sedang berjalan',
                                'col' => '6 col-md-3',
                            ],
                            [
                                'id' => 'observasi_bergoyang',
                                'label' => 'Badan tampak bergoyang (swaying)',
                                'value' => 'Badan tampak bergoyang (swaying)',
                                'col' => '6 col-md-3',
                            ],
                            [
                                'id' => 'observasi_berbalik_tidak_stabil',
                                'label' => 'Berbalik tidak stabil',
                                'value' => 'Berbalik tidak stabil',
                                'col' => '6 col-md-3',
                            ],
                            [
                                'id' => 'observasi_berpegangan',
                                'label' => 'Berpegangan pada objek sekitar untuk bantuan',
                                'value' => 'Berpegangan pada objek sekitar untuk bantuan',
                                'col' => '6 col-md-3',
                            ],
                        ];
                    @endphp
                    <div class="row g-2">
                    @foreach ($observasiOptions as $idx => $opt)
                    @if ($idx === 3)
                    <div class="col-12">
                    <hr>
                    </div>
                    @endif
                    <div class="col-{{ $opt['col'] }}">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="{{ $opt['id'] }}"
                    value="{{ $opt['value'] }}" wire:model="observasi_kualitatif" />
                    <label class="form-check-label" for="{{ $opt['id'] }}">
                    {{ $opt['label'] }}
                    </label>
                    </div>
                    </div>
                    @endforeach
                    </div>
                    @error('observasi_kualitatif')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                    </div>
                    <div class="note alert-secondary mb-2">
                    <div class="note-content">
                    <h3>Penilaian & Rekomendasi</h3>
                    <div class="mb-3">
                    <label class="form-label">Penilaian Risiko Jatuh:</label>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="risiko_jatuh" value="Rendah"
                    wire:model="risiko_jatuh" id="risiko_jatuh_rendah">
                    <label class="form-check-label" for="risiko_jatuh_rendah">Risiko Rendah
                    (Mobilitas
                    Normal)</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="risiko_jatuh"
                    id="risiko_jatuh_sedang" value="Sedang" wire:model="risiko_jatuh">
                    <label class="form-check-label" for="risiko_jatuh_sedang">Risiko Sedang (Perlu
                    observasi_kualitatif lanjut)</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="risiko_jatuh"
                    id="risiko_jatuh_tinggi" value="Tinggi" wire:model="risiko_jatuh">
                    <label class="form-check-label" for="risiko_jatuh_tinggi">Risiko Tinggi (Perlu
                    intervensi)</label>
                    </div>
                    @error('risiko_jatuh')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>

                    <div class="mb-3">
                    <label class="form-label">Catatan Tambahan / Rekomendasi:</label>
                    <textarea id="catatan" rows="4" wire:model="catatan" class="form-control"
                        placeholder="Tulis catatan observasi_kualitatif lain atau rencana tindak lanjut..."></textarea>
                    @error('catatan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                    </div>
                    </div>
                    <hr>
                    @role('administrator|supervisor|operator')
                        <button type="button" x-init="$($el).on('click', function() {
                            $('#modal-konfirmasi').modal('show');
                        })" class="btn btn-success"
                        wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Submit
                        </button>
                    @endrole
                    @if (isset($data->pemeriksaanAwal) && $data->pemeriksaanAwal->count() > 0)
                    <button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/diagnosis/form/{{ $data->id }}'">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Lanjut Diagnosis
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/pemeriksaanawal'">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Data
                    </button>
                    <x-alert />

                    <x-modal.konfirmasi />
                    </form>
                    </div>
                    </div>

                    <div wire:loading>
                    <x-loading />
                    </div>
                    </div>)
