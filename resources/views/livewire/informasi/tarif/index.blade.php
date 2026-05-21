<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Informasi</li>
        <li class="breadcrumb-item active">Tarif</li>
    @endsection

    @push('css')
        <style>
            /* Tariff Card Layout */
            .tariff-info-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                overflow: hidden;
                margin-bottom: 20px;
            }

            .tariff-header {
                background: linear-gradient(135deg, #1e293b, #0ea5e9);
                color: #fff;
                padding: 16px;
                position: relative;
            }

            .tariff-header.package-header {
                background: linear-gradient(135deg, #0f172a, #6366f1);
            }

            .tariff-avatar {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.15);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                color: #fff;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .tariff-body {
                padding: 16px;
            }

            .tariff-meta-label {
                font-size: 10px;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                font-weight: 600;
                margin-bottom: 2px;
            }

            .tariff-meta-value {
                font-size: 14px;
                color: #1e293b;
                font-weight: 600;
            }

            .tariff-divider {
                height: 1px;
                background-color: #e2e8f0;
                margin: 12px 0;
            }

            /* Simulator Widget Card */
            .simulator-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .simulator-header {
                background: linear-gradient(135deg, #312e81, #4f46e5);
                color: #fff;
                padding: 16px;
            }

            .simulator-body {
                padding: 20px;
            }

            /* Detail Tindakan List */
            .detail-tindakan-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                margin-top: 15px;
                overflow: hidden;
            }
            
            .detail-tindakan-header {
                background-color: #f8fafc;
                border-bottom: 1px solid #e2e8f0;
                padding: 12px 16px;
            }

            .detail-tindakan-body {
                padding: 16px;
            }

            .tindakan-row-item {
                transition: all 0.2s ease-in-out;
                padding: 10px;
                border-radius: 6px;
            }

            .tindakan-row-item:hover {
                background-color: #f1f5f9;
                transform: translateX(2px);
            }
            
            .me-2\.5 {
                margin-right: 0.625rem !important;
            }
            .p-2\.5 {
                padding: 0.625rem !important;
            }
            .my-1\.5 {
                margin-top: 0.375rem !important;
                margin-bottom: 0.375rem !important;
            }
        </style>
    @endpush

    <h1 class="page-header">Informasi Tarif</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <div wire:ignore class="w-100">
                <select class="form-control" x-init="$($el).select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Ketik Nama Tarif',
                    minimumInputLength: 1,
                    dataType: 'json',
                    ajax: {
                        url: '/cari/tarif',
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
                    $wire.set('tarifId', $($el).val());
                });">
                </select>
            </div>
        </div>
    </div>

    <x-alert />

    @if ($data)
        <div class="card border-0 shadow-sm bg-white p-4 mb-4"
            style="border: 1px solid #e2e8f0 !important; border-radius: 8px;">
            @if (str_contains($tarifId, '-tindakan'))
                    <div class="row g-4">
                        <!-- Left Column: Data Tarif Tindakan -->
                        <div class="col-md-6">
                            <div class="tariff-info-card">
                                <div class="tariff-header d-flex align-items-center">
                                    <div class="tariff-avatar me-3">
                                        <i class="fa fa-hand-holding-medical text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Data Tarif Tindakan</h5>
                                        <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                                            ID: {{ $data->id }}
                                        </span>
                                    </div>
                                </div>

                                <div class="tariff-body bg-light bg-opacity-50">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="tariff-meta-label">Nama Tindakan</div>
                                            <div class="tariff-meta-value text-primary fs-15px">{{ $data->nama }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="tariff-meta-label">Tarif Standard</div>
                                            <div class="tariff-meta-value text-success fs-18px">Rp. {{ number_format_id($data->tarif) }}</div>
                                        </div>
                                    </div>

                                    @if ($data->catatan)
                                        <div class="tariff-divider"></div>
                                        <div>
                                            <div class="tariff-meta-label">Catatan / Deskripsi</div>
                                            <div class="tariff-meta-value text-muted fw-normal fs-12px">{!! nl2br(e($data->catatan)) !!}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Simulasi Diskon -->
                        <div class="col-md-6" x-data="{
                            diskon1: 0,
                            diskon2: 0,
                            diskon3: 0,
                            get tarif() { return {{ $data->tarif ?? 0 }} },
                            get nominalDiskon1() {
                                return Math.round(this.tarif * this.diskon1 / 100)
                            },
                            get tarifSetelahDiskon1() {
                                return this.tarif - this.nominalDiskon1
                            },
                            get nominalDiskon2() {
                                return Math.round(this.tarifSetelahDiskon1 * this.diskon2 / 100)
                            },
                            get tarifSetelahDiskon2() {
                                return this.tarifSetelahDiskon1 - this.nominalDiskon2
                            },
                            get nominalDiskon3() {
                                return Math.round(this.tarifSetelahDiskon2 * this.diskon3 / 100)
                            },
                            get tarifAkhir() {
                                return this.tarifSetelahDiskon2 - this.nominalDiskon3
                            },
                            formatRupiah(amount) {
                                return 'Rp. ' + amount.toLocaleString('id-ID');
                            }
                        }">
                            <div class="simulator-card">
                                <div class="simulator-header d-flex align-items-center">
                                    <div class="tariff-avatar me-3" style="background: rgba(255, 255, 255, 0.2);">
                                        <i class="fa fa-calculator text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Simulasi Diskon Tindakan</h5>
                                        <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">Interactive Simulator</span>
                                    </div>
                                </div>

                                <div class="simulator-body">
                                    <div class="d-flex flex-column gap-3">
                                        <!-- Row 1: Diskon 1 -->
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded">
                                            <div class="fw-semibold text-dark fs-12px" style="flex: 1;">Diskon Tahap 1 (%)</div>
                                            <div class="me-3" style="width: 100px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="1" min="0" max="100" class="form-control text-center fw-bold" x-model="diskon1">
                                                    <span class="input-group-text bg-secondary text-white">%</span>
                                                </div>
                                            </div>
                                            <div class="text-end fw-bold text-danger fs-12px" style="width: 120px;" x-text="formatRupiah(nominalDiskon1)"></div>
                                        </div>

                                        <!-- Row 2: Diskon 2 -->
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded">
                                            <div class="fw-semibold text-dark fs-12px" style="flex: 1;">Diskon Tahap 2 (%)</div>
                                            <div class="me-3" style="width: 100px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="1" min="0" max="100" class="form-control text-center fw-bold" x-model="diskon2">
                                                    <span class="input-group-text bg-secondary text-white">%</span>
                                                </div>
                                            </div>
                                            <div class="text-end fw-bold text-danger fs-12px" style="width: 120px;" x-text="formatRupiah(nominalDiskon2)"></div>
                                        </div>

                                        <!-- Row 3: Diskon 3 -->
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded">
                                            <div class="fw-semibold text-dark fs-12px" style="flex: 1;">Diskon Tahap 3 (%)</div>
                                            <div class="me-3" style="width: 100px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="1" min="0" max="100" class="form-control text-center fw-bold" x-model="diskon3">
                                                    <span class="input-group-text bg-secondary text-white">%</span>
                                                </div>
                                            </div>
                                            <div class="text-end fw-bold text-danger fs-12px" style="width: 120px;" x-text="formatRupiah(nominalDiskon3)"></div>
                                        </div>

                                        <div class="tariff-divider"></div>

                                        <!-- Summary Box -->
                                        <div class="alert alert-warning border-0 mb-0 p-3 d-flex flex-column gap-2">
                                            <div class="d-flex justify-content-between fs-12px">
                                                <span class="text-muted fw-semibold">Total Nilai Diskon:</span>
                                                <strong class="text-danger" x-text="formatRupiah(nominalDiskon1 + nominalDiskon2 + nominalDiskon3)"></strong>
                                            </div>
                                            <hr class="my-1.5 bg-warning bg-opacity-50">
                                            <div class="d-flex justify-content-between align-items-center fs-14px">
                                                <span class="text-dark fw-bold">Tarif Akhir setelah Diskon:</span>
                                                <strong class="text-success fs-16px" x-text="formatRupiah(tarifAkhir)"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        <!-- Left Column: Data Paket Perawatan & Detail -->
                        <div class="col-md-6">
                            <!-- Paket Perawatan Main Card -->
                            <div class="tariff-info-card">
                                <div class="tariff-header package-header d-flex align-items-center">
                                    <div class="tariff-avatar me-3">
                                        <i class="fa fa-cubes text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Data Paket Perawatan</h5>
                                        <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                                            ID: {{ $data->id }}
                                        </span>
                                    </div>
                                </div>

                                <div class="tariff-body bg-light bg-opacity-50">
                                    <div class="row g-3">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="tariff-meta-label">Nama Paket</div>
                                            <div class="tariff-meta-value text-primary fs-14px">{{ $data->nama }}</div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="tariff-meta-label">Tarif Paket</div>
                                            <div class="tariff-meta-value text-success fs-15px">Rp. {{ number_format_id($data->tarif) }}</div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="tariff-meta-label">Jenis Paket</div>
                                            <div class="tariff-meta-value">
                                                <span class="badge bg-indigo text-white fs-11px px-2.5 py-1.5 rounded">{{ $data->jenis }}</span>
                                            </div>
                                        </div>
                                        @if ($data->jenis == 'Non Bundling')
                                            <div class="col-md-6 col-sm-12">
                                                <div class="tariff-meta-label">Masa Aktif</div>
                                                <div class="tariff-meta-value text-dark"><i class="fa fa-calendar-alt text-muted me-1 fs-12px"></i>{{ $data->masa_aktif }} Hari</div>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($data->uraian)
                                        <div class="tariff-divider"></div>
                                        <div>
                                            <div class="tariff-meta-label">Uraian Paket</div>
                                            <div class="tariff-meta-value text-muted fw-normal fs-12px">{!! nl2br(e($data->uraian)) !!}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Detail Tindakan Card -->
                            @if ($data->paketPerawatanDetail && $data->paketPerawatanDetail->count() > 0)
                                <div class="detail-tindakan-card">
                                    <div class="detail-tindakan-header d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 text-dark fw-bold">
                                            <i class="fa fa-notes-medical text-primary me-2"></i>Detail Tindakan Medis Paket
                                        </h6>
                                        <span class="badge bg-light text-secondary border fs-10px">
                                            {{ $data->paketPerawatanDetail->count() }} Tindakan
                                        </span>
                                    </div>
                                    <div class="detail-tindakan-body">
                                        <div class="d-flex flex-column gap-2">
                                            @foreach ($data->paketPerawatanDetail as $item)
                                                <div class="tindakan-row-item border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold me-2.5 fs-11px" style="min-width: 26px; text-align: center;">{{ $item->qty }}x</span>
                                                        <span class="text-dark fw-semibold fs-12px">{{ $item->tarifTindakan->nama }}</span>
                                                    </div>
                                                    <span class="text-secondary fs-12px">@ Rp. {{ number_format_id($item->tarifTindakan->tarif) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column: Simulasi Diskon -->
                        <div class="col-md-6" x-data="{
                            diskon1: 0,
                            diskon2: 0,
                            diskon3: 0,
                            get tarif() { return {{ $data->tarif ?? 0 }} },
                            get nominalDiskon1() {
                                return Math.round(this.tarif * this.diskon1 / 100)
                            },
                            get tarifSetelahDiskon1() {
                                return this.tarif - this.nominalDiskon1
                            },
                            get nominalDiskon2() {
                                return Math.round(this.tarifSetelahDiskon1 * this.diskon2 / 100)
                            },
                            get tarifSetelahDiskon2() {
                                return this.tarifSetelahDiskon1 - this.nominalDiskon2
                            },
                            get nominalDiskon3() {
                                return Math.round(this.tarifSetelahDiskon2 * this.diskon3 / 100)
                            },
                            get tarifAkhir() {
                                return this.tarifSetelahDiskon2 - this.nominalDiskon3
                            },
                            formatRupiah(amount) {
                                return 'Rp. ' + amount.toLocaleString('id-ID');
                            }
                        }">
                            <div class="simulator-card">
                                <div class="simulator-header d-flex align-items-center" style="background: linear-gradient(135deg, #1e1b4b, #6366f1);">
                                    <div class="tariff-avatar me-3" style="background: rgba(255, 255, 255, 0.2);">
                                        <i class="fa fa-calculator text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Simulasi Diskon Paket</h5>
                                        <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">Interactive Simulator</span>
                                    </div>
                                </div>

                                <div class="simulator-body">
                                    <div class="d-flex flex-column gap-3">
                                        <!-- Row 1: Diskon 1 -->
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded">
                                            <div class="fw-semibold text-dark fs-12px" style="flex: 1;">Diskon Tahap 1 (%)</div>
                                            <div class="me-3" style="width: 100px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="1" min="0" max="100" class="form-control text-center fw-bold" x-model="diskon1">
                                                    <span class="input-group-text bg-secondary text-white">%</span>
                                                </div>
                                            </div>
                                            <div class="text-end fw-bold text-danger fs-12px" style="width: 120px;" x-text="formatRupiah(nominalDiskon1)"></div>
                                        </div>

                                        <!-- Row 2: Diskon 2 -->
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded">
                                            <div class="fw-semibold text-dark fs-12px" style="flex: 1;">Diskon Tahap 2 (%)</div>
                                            <div class="me-3" style="width: 100px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="1" min="0" max="100" class="form-control text-center fw-bold" x-model="diskon2">
                                                    <span class="input-group-text bg-secondary text-white">%</span>
                                                </div>
                                            </div>
                                            <div class="text-end fw-bold text-danger fs-12px" style="width: 120px;" x-text="formatRupiah(nominalDiskon2)"></div>
                                        </div>

                                        <!-- Row 3: Diskon 3 -->
                                        <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded">
                                            <div class="fw-semibold text-dark fs-12px" style="flex: 1;">Diskon Tahap 3 (%)</div>
                                            <div class="me-3" style="width: 100px;">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="1" min="0" max="100" class="form-control text-center fw-bold" x-model="diskon3">
                                                    <span class="input-group-text bg-secondary text-white">%</span>
                                                </div>
                                            </div>
                                            <div class="text-end fw-bold text-danger fs-12px" style="width: 120px;" x-text="formatRupiah(nominalDiskon3)"></div>
                                        </div>

                                        <div class="tariff-divider"></div>

                                        <!-- Summary Box -->
                                        <div class="alert alert-warning border-0 mb-0 p-3 d-flex flex-column gap-2">
                                            <div class="d-flex justify-content-between fs-12px">
                                                <span class="text-muted fw-semibold">Total Nilai Diskon:</span>
                                                <strong class="text-danger" x-text="formatRupiah(nominalDiskon1 + nominalDiskon2 + nominalDiskon3)"></strong>
                                            </div>
                                            <hr class="my-1.5 bg-warning bg-opacity-50">
                                            <div class="d-flex justify-content-between align-items-center fs-14px">
                                                <span class="text-dark fw-bold">Tarif Akhir setelah Diskon:</span>
                                                <strong class="text-success fs-16px" x-text="formatRupiah(tarifAkhir)"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

    <div wire:loading>
        <x-loading />
    </div>
</div>
