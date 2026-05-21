<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Informasi</li>
        <li class="breadcrumb-item active">Pasien</li>
    @endsection

    <h1 class="page-header">Informasi Pasien</h1>

    @push('css')
        <style>
            /* Patient Info Card CSS */
            .patient-info-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .patient-header {
                background: linear-gradient(135deg, #1e293b, #3b82f6);
                color: #fff;
                padding: 16px;
                position: relative;
            }

            .patient-avatar {
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

            .patient-body {
                padding: 16px;
            }

            .patient-meta-label {
                font-size: 10px;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                font-weight: 600;
                margin-bottom: 2px;
            }

            .patient-meta-value {
                font-size: 13px;
                color: #1e293b;
                font-weight: 500;
            }

            .patient-divider {
                height: 1px;
                background-color: #e2e8f0;
                margin: 12px 0;
            }

            /* History Card CSS */
            .history-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                transition: all 0.2s ease-in-out;
            }

            .history-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border-color: #cbd5e1;
            }

            .w-15px {
                width: 15px !important;
            }

            @media (min-width: 992px) {
                .border-end-lg {
                    border-right: 1px solid #e2e8f0;
                }
            }

            /* Visit Navigation Tabs Style */
            .visit-nav-list {
                background: transparent;
                scrollbar-width: thin;
                scrollbar-color: #cbd5e1 #f1f5f9;
            }

            .visit-nav-list::-webkit-scrollbar {
                height: 6px;
            }

            .visit-nav-list::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }

            .visit-nav-list::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }

            .visit-nav-list::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            .visit-nav-item {
                background-color: #ffffff;
                border-bottom: 4px solid transparent !important;
                transition: all 0.2s ease-in-out;
                cursor: pointer;
                text-align: left;
            }

            .visit-nav-item:hover {
                background-color: #f8fafc;
                border-bottom-color: #cbd5e1 !important;
                transform: translateY(-2px);
            }

            .visit-nav-item.active-visit {
                background-color: #eff6ff !important;
                border-bottom-color: #3b82f6 !important;
                box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06) !important;
            }

            .visit-nav-item.active-visit .visit-nav-icon {
                background-color: #3b82f6;
                color: #ffffff;
            }

            .visit-nav-item.active-visit .visit-nav-date {
                color: #1e3a8a;
            }

            .visit-nav-item.active-visit .visit-nav-doctor {
                color: #3b82f6 !important;
            }

            .visit-nav-item.active-visit .visit-nav-badge {
                background-color: #3b82f6 !important;
                color: #ffffff !important;
                border-color: #3b82f6 !important;
            }

            .visit-nav-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                background-color: #f1f5f9;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease-in-out;
            }
        </style>
    @endpush

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <div class="w-100">
                <div wire:ignore>
                    <select class="form-control" x-init="$($el).select2({
                        width: '100%',
                        dropdownAutoWidth: true,
                        templateResult: format,
                        placeholder: 'Ketik Nama/No. KTP/No. RM',
                        minimumInputLength: 1,
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
                        $wire.set('noRm', $($el).val());
                    });
                    
                    function format(data) {
                        if (!data.id) {
                            return data.text;
                        }
                        var $data = $('<table><tr><th>No. RM</th><th>:</th><th>' + data.rm + '</th></tr>' +
                            '<tr><th>No. KTP</th><th>:</th><td>' + data.nik + '</td></tr>' +
                            '<tr><th>Nama</th><th>:</th><td>' + data.nama + '</td></tr>' +
                            '<tr><th>Alamat</th><th>:</th><td>' + data.alamat + '</td></tr></table>');
                        return $data;
                    }">
                    </select>
                </div>
            </div>
        </div>


    </div>
    @php
        $dokter = auth()->user()->hasRole('administrator')
            ? 1
            : auth()->user()->kepegawaianPegawai?->nakes?->dokter ?? 0;
    @endphp
    @if ($dataPasien)
        <div class="patient-info-card mb-4">
            <div class="patient-header d-flex align-items-center">
                <div class="patient-avatar me-3">
                    @if (str_starts_with(strtolower($dataPasien->jenis_kelamin ?? ''), 'l'))
                        <i class="fa fa-mars"></i>
                    @elseif(str_starts_with(strtolower($dataPasien->jenis_kelamin ?? ''), 'p'))
                        <i class="fa fa-venus"></i>
                    @else
                        <i class="fa fa-user"></i>
                    @endif
                </div>
                <div>
                    <h5 class="mb-0 text-white fw-bold">{{ $dataPasien->nama }}</h5>
                    <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                        No. RM: {{ $dataPasien->id }}
                    </span>
                </div>
            </div>

            <div class="patient-body bg-light bg-opacity-50">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="patient-meta-label">No. KTP</div>
                        <div class="patient-meta-value">{{ $dataPasien->nik ?: '-' }}</div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="patient-meta-label">No. Telpon</div>
                        <div class="patient-meta-value">{{ $dataPasien->no_hp ?: '-' }}</div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="patient-meta-label">Jenis Kelamin / Usia</div>
                        <div class="patient-meta-value">
                            @if (str_starts_with(strtolower($dataPasien->jenis_kelamin ?? ''), 'l'))
                                <span class="text-info"><i class="fa fa-mars me-1"></i>Laki-laki</span>
                            @elseif(str_starts_with(strtolower($dataPasien->jenis_kelamin ?? ''), 'p'))
                                <span class="text-danger" style="color: #ec4899 !important;"><i
                                        class="fa fa-venus me-1"></i>Perempuan</span>
                            @else
                                <span>{{ $dataPasien->jenis_kelamin }}</span>
                            @endif
                            / {{ $dataPasien->umur }} Tahun
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="patient-meta-label">Tanggal Lahir</div>
                        <div class="patient-meta-value">
                            {{ $dataPasien->tanggal_lahir ? $dataPasien->tanggal_lahir->format('d M Y') : '-' }}</div>
                    </div>
                </div>

                <div class="patient-divider"></div>

                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="patient-meta-label">Tanggal Daftar</div>
                        <div class="patient-meta-value">
                            {{ $dataPasien->tanggal_daftar ? $dataPasien->tanggal_daftar->format('d M Y') : '-' }}</div>
                    </div>
                    <div class="col-md-9 col-sm-6">
                        <div class="patient-meta-label">Alamat</div>
                        <div class="patient-meta-value">{{ $dataPasien->alamat ?: '-' }}</div>
                    </div>
                </div>

                @if ($dataPasien->deskripsi)
                    <div class="patient-divider"></div>
                    <div>
                        <div class="patient-meta-label">Catatan</div>
                        <div class="patient-meta-value text-muted">{{ $dataPasien->deskripsi }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-white p-4 mb-4"
            style="border: 1px solid #e2e8f0 !important; border-radius: 8px;">
            @if ($dataPasien->rekamMedis->count() > 0)
                <div x-data="{ activeVisit: '{{ $dataPasien->rekamMedis->first()->id ?? '' }}' }">
                    <div class="card-header bg-white border-0 ps-0 pt-0 mb-3">
                        <h4 class="card-title text-dark fw-bold mb-0">
                            <i class="fa fa-notes-medical text-primary me-2"></i>Riwayat Rekam Medis Pasien
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <!-- Visit History Navigation Tabs (Horizontal at the Top) -->
                        <div class="visit-nav-container mb-4">
                            <div class="d-flex flex-nowrap overflow-x-auto gap-2 pb-2 visit-nav-list">
                                @foreach ($dataPasien->rekamMedis as $row)
                                    <button type="button" @click="activeVisit = '{{ $row->id }}'"
                                        :class="activeVisit == '{{ $row->id }}' ? 'active-visit' : ''"
                                        class="visit-nav-item border-0 p-3 rounded shadow-sm d-flex align-items-center justify-content-between flex-shrink-0"
                                        style="min-width: 220px; max-width: 280px;">
                                        <div class="d-flex align-items-center me-2">
                                            <div class="visit-nav-icon me-3">
                                                <i class="fa fa-calendar-check fs-16px"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold visit-nav-date fs-12px">
                                                    {{ $row->created_at->format('d M Y') }}</div>
                                                <small
                                                    class="visit-nav-doctor text-secondary d-block fs-10px text-truncate"
                                                    style="max-width: 130px;">
                                                    #{{ $row->id }}
                                                </small>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Visit Details -->
                        <div class="visit-details-container">
                            @foreach ($dataPasien->rekamMedis as $row)
                                <div x-show="activeVisit == '{{ $row->id }}'"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform translate-x-4"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    class="history-card p-4">
                                    <!-- Card Header: Date & Info -->
                                    <div
                                        class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-calendar-check text-primary me-2 fs-16px"></i>
                                            <span class="fw-bold text-dark fs-14px">
                                                {{ $row->created_at->format('d F Y') }}
                                            </span>
                                            <span class="mx-2 text-muted">|</span>
                                            <small class="text-secondary">Waktu:
                                                {{ $row->created_at->format('H:i') }}</small>
                                        </div>
                                        <span class="badge bg-secondary text-white px-2.5 py-1.5 rounded fs-11px">
                                            ID: #{{ $row->id }}
                                        </span>
                                    </div>

                                    <!-- Card Body: Grid of Process Steps -->
                                    <div class="row g-4">
                                        <!-- Left column: Main diagnosis, examinations, TUG -->
                                        <div class="col-lg-12 border-end-lg">
                                            <!-- 1. Registrasi & Nakes -->
                                            <div class="mb-4">
                                                <h6 class="text-primary fw-bold mb-2">
                                                    <i class="fa fa-file-signature me-2"></i>Registrasi & Pemeriksa
                                                </h6>
                                                <div class="bg-light p-3 rounded">
                                                    <div class="row g-3">
                                                        <div class="col-sm-6">
                                                            <small
                                                                class="text-muted d-block fs-10px uppercase fw-600">Dokter
                                                                Pemeriksa</small>
                                                            <strong
                                                                class="text-dark fs-12px">{{ $row->nakes?->nama ?: '-' }}</strong>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <small
                                                                class="text-muted d-block fs-10px uppercase fw-600">Keluhan
                                                                Awal</small>
                                                            <span
                                                                class="text-dark fs-12px">{{ $row->keluhan_awal ?: '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 pt-2 border-top text-end">
                                                        <span
                                                            class="badge bg-light text-secondary fs-10px border fw-normal">
                                                            <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                            {{ $row->pengguna->nama }}
                                                            ({{ $row->created_at->format('d M Y, H:i') }})
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 2. Hasil Pemeriksaan Awal -->
                                            @if ($row->pemeriksaanAwal)
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-2">
                                                        <i class="fa fa-heartbeat me-2"></i>Pemeriksaan Awal
                                                    </h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <div class="mb-2">
                                                            <span
                                                                class="text-dark fw-bold d-block mb-1 border-bottom pb-1"><i
                                                                    class="fa fa-clipboard-list me-1 text-muted"></i>Anamnesis</span>
                                                            <div class="row g-2">
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Keluhan
                                                                        Utama</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->keluhan_utama ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Riwayat
                                                                        Penyakit Sekarang</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->riwayat_sekarang ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Riwayat
                                                                        Dahulu & Keluarga</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->riwayat_dahulu ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Riwayat
                                                                        Alergi</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->riwayat_alergi ?: '-' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="patient-divider"></div>

                                                        <div class="mb-2">
                                                            <span
                                                                class="text-dark fw-bold d-block mb-1 border-bottom pb-1"><i
                                                                    class="fa fa-chart-line me-1 text-muted"></i>Pemeriksaan
                                                                Fisik (TTV & Antropometri)</span>
                                                            <div class="row g-2">
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block fs-10px">Tekanan
                                                                        Darah</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->tekanan_darah ?: '-' }}
                                                                        mmHg</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Nadi</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->nadi ?: '-' }}
                                                                        x/menit</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Suhu</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->suhu ?: '-' }}
                                                                        °C</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">SpO2</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->saturasi_o2 ?: '-' }}
                                                                        %</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block fs-10px">Berat /
                                                                        Tinggi</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->berat_badan ?: '-' }}
                                                                        kg /
                                                                        {{ $row->pemeriksaanAwal->tinggi_badan ?: '-' }}
                                                                        cm</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Pernapasan</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->pernapasan ?: '-' }}
                                                                        x/menit</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block fs-10px">Tingkat
                                                                        Kesadaran</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->kesadaran ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block fs-10px">Kesan
                                                                        Sakit</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->kesan_sakit ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block fs-10px">Status
                                                                        Gizi</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->pemeriksaanAwal->status_gizi ?: '-' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="patient-divider"></div>

                                                        <div class="mb-2">
                                                            <span
                                                                class="text-dark fw-bold d-block mb-1 border-bottom pb-1"><i
                                                                    class="fa fa-stethoscope me-1 text-muted"></i>Pemeriksaan
                                                                Head to Toe</span>
                                                            <div class="row g-2">
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Kepala,
                                                                        Mata, THT, Leher</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->kepala_normal == 1 ? 'Normal' : ($row->pemeriksaanAwal->kepala_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Jantung</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->jantung_normal == 1 ? 'Normal' : ($row->pemeriksaanAwal->jantung_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Paru</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->paru_normal == 1 ? 'Normal' : ($row->pemeriksaanAwal->paru_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Abdomen</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->abdomen_normal == 1 ? 'Normal' : ($row->pemeriksaanAwal->abdomen_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Ekstremitas</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->ekstremitas_normal == 1 ? 'Normal' : ($row->pemeriksaanAwal->ekstremitas_temuan ?: '-') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if ($row->pemeriksaanAwal->diagnosis_kerja || $row->pemeriksaanAwal->rencana_awal)
                                                            <div class="patient-divider"></div>
                                                            <div class="row g-2">
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Diagnosis
                                                                        Kerja Awal</small>
                                                                    <strong
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->diagnosis_kerja ?: '-' }}</strong>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Rencana
                                                                        Awal</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->pemeriksaanAwal->rencana_awal ?: '-' }}</span>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="mt-2 pt-2 border-top text-end">
                                                            <span
                                                                class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                {{ $row->pemeriksaanAwal->pengguna->nama }}
                                                                ({{ $row->pemeriksaanAwal->created_at->format('d M Y, H:i') }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 3. Tes Up And Go -->
                                            @if ($row->tug)
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-2">
                                                        <i class="fa fa-walking me-2"></i>Tes Up And Go
                                                    </h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <div class="row g-2 mb-2">
                                                            <div class="col-sm-6">
                                                                <small class="text-muted d-block fs-10px">Waktu
                                                                    Tes</small>
                                                                <strong
                                                                    class="text-dark fs-12px">{{ $row->tug->waktu_tes_detik }}
                                                                    detik</strong>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <small class="text-muted d-block fs-10px">Risiko
                                                                    Jatuh</small>
                                                                <span
                                                                    class="badge bg-{{ str_contains(strtolower($row->tug->risiko_jatuh), 'tinggi') ? 'danger' : (str_contains(strtolower($row->tug->risiko_jatuh), 'sedang') ? 'warning' : 'success') }} text-white py-1 px-2">
                                                                    {{ $row->tug->risiko_jatuh }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        @php
                                                            $observasi = is_string($row->tug->observasi_kualitatif)
                                                                ? json_decode($row->tug->observasi_kualitatif, true) ??
                                                                    []
                                                                : (is_array($row->tug->observasi_kualitatif)
                                                                    ? $row->tug->observasi_kualitatif
                                                                    : []);
                                                        @endphp
                                                        @if (count($observasi) > 0)
                                                            <div class="mt-2">
                                                                <small
                                                                    class="text-muted d-block fs-10px mb-1">Observasi
                                                                    Kualitatif Gerakan</small>
                                                                <ul class="list-unstyled mb-0 ps-1">
                                                                    @foreach ($observasi as $obsItem)
                                                                        <li class="text-dark fs-11px mb-0.5"><i
                                                                                class="fa fa-check-circle text-success me-1"></i>{{ $obsItem }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        @if ($row->tug->catatan)
                                                            <div class="mt-2 border-top pt-2">
                                                                <small class="text-muted d-block fs-10px">Catatan
                                                                    Tambahan / Rekomendasi</small>
                                                                <small
                                                                    class="text-dark fs-12px italic">{{ $row->tug->catatan }}</small>
                                                            </div>
                                                        @endif

                                                        <div class="mt-2 pt-2 border-top text-end">
                                                            <span
                                                                class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                {{ $row->tug->pengguna->nama }}
                                                                ({{ $row->tug->created_at->format('d M Y, H:i') }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 4. Diagnosis (ICD10) -->
                                            @if ($row->diagnosis)
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-2">
                                                        <i class="fa fa-stethoscope me-2"></i>Diagnosis & ICD-10
                                                    </h6>
                                                    <div class="bg-light p-3 rounded">
                                                        @if ($row->diagnosis->icd10_uraian && $row->diagnosis->icd10_uraian->count() > 0)
                                                            <div class="mb-2">
                                                                <small
                                                                    class="text-muted d-block fs-10px mb-1">ICD-10</small>
                                                                @foreach ($row->diagnosis->icd10_uraian as $item)
                                                                    <div class="text-dark fs-12px mb-1 fw-semibold">
                                                                        <span
                                                                            class="badge bg-primary text-white me-2 fs-10px">{{ $item->id }}</span>{{ $item->uraian }}
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        @if ($row->diagnosis->diagnosis_banding)
                                                            <div class="mt-2 border-top pt-2">
                                                                <small class="text-muted d-block fs-10px">Diagnosis
                                                                    Banding (Differential Diagnosis)</small>
                                                                <strong
                                                                    class="text-dark fs-12px">{{ $row->diagnosis->diagnosis_banding }}</strong>
                                                            </div>
                                                        @endif

                                                        @if ($row->diagnosis->file && $row->diagnosis->file->count() > 0)
                                                            <div class="mt-2 border-top pt-2">
                                                                <small
                                                                    class="text-muted d-block fs-10px mb-2">Dokumentasi</small>
                                                                <div class="row g-2">
                                                                    @foreach ($row->diagnosis->file as $item)
                                                                        <div class="col-4">
                                                                            <a href="{{ $item->link }}"
                                                                                target="_blank"
                                                                                class="d-block border rounded overflow-hidden shadow-sm hover-opacity">
                                                                                <img src="{{ $item->link }}"
                                                                                    alt="{{ $item->judul }}"
                                                                                    class="img-fluid w-100"
                                                                                    style="height: 80px; object-fit: cover;">
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="mt-2 pt-2 border-top text-end">
                                                            <span
                                                                class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                {{ $row->diagnosis->pengguna->nama }}
                                                                ({{ $row->diagnosis->created_at->format('d M Y, H:i') }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- 5. Tindakan -->
                                            @if ($row->tindakan && $row->tindakan->count() > 0)
                                                <div class="mb-4">
                                                    <h6 class="text-indigo fw-bold mb-2">
                                                        <i class="fa fa-syringe me-2"></i>Tindakan Medis
                                                    </h6>
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach ($row->tindakan as $item)
                                                            <div class="alert alert-indigo mb-0 p-3">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <strong class="fs-12px">{{ $loop->iteration }}.
                                                                        {{ $item->tarifTindakan->nama }}</strong>
                                                                    <span
                                                                        class="badge bg-indigo text-white fs-10px">{{ $item->qty }}x</span>
                                                                </div>
                                                                <hr class="my-2 bg-indigo bg-opacity-30">
                                                                <div class="fs-11px">
                                                                    @if ($item->dokter?->nama)
                                                                        <div class="mb-1"><i
                                                                                class="fa fa-user-md me-1 text-info w-15px"></i>Dokter:
                                                                            {{ $item->dokter->nama }}</div>
                                                                    @endif
                                                                    @if ($item->perawat?->nama)
                                                                        <div class="mb-1"><i
                                                                                class="fa fa-user-nurse me-1 text-success w-15px"></i>Perawat:
                                                                            {{ $item->perawat->nama }}</div>
                                                                    @endif
                                                                    <div class="mt-1 fw-bold">
                                                                        Biaya: {{ number_format_id($item->biaya) }}
                                                                        @if ($item->diskon > 0)
                                                                            <span class="text-danger"> (Diskon:
                                                                                {{ number_format_id($item->diskon) }})</span>
                                                                        @endif
                                                                        =
                                                                        {{ number_format_id($item->biaya * $item->qty - $item->diskon) }}
                                                                    </div>
                                                                    @if ($item->catatan)
                                                                        <div
                                                                            class="mt-1 text-secondary bg-white bg-opacity-50 p-2 rounded border border-indigo-subtle border-opacity-30">
                                                                            <strong>Catatan:</strong>
                                                                            {{ $item->catatan }}
                                                                        </div>
                                                                    @endif
                                                                    @if ($item->membutuhkan_sitemarking)
                                                                        <div class="mt-2">
                                                                            <a href="/klinik/sitemarking/form/{{ $row->id }}"
                                                                                target="_blank"
                                                                                class="btn btn-xs btn-outline-indigo">
                                                                                <i
                                                                                    class="fa fa-map-marker-alt me-1"></i>Site
                                                                                Marking
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 text-end">
                                                        <span
                                                            class="badge bg-light text-secondary fs-10px border fw-normal">
                                                            <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                            {{ $row->tindakan->first()->pengguna->nama }}
                                                            ({{ $row->tindakan->first()->created_at->format('d M Y, H:i') }})
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 6. Resep Obat -->
                                            @if (collect($row->resepObat)->count() > 0)
                                                @php
                                                    $groupedResep = collect($row->resepObat)
                                                        ->groupBy('resep')
                                                        ->map(function ($group) {
                                                            $first = $group->first();
                                                            return [
                                                                'catatan' => $first->catatan,
                                                                'nama' => $first->nama,
                                                                'barang' => $group
                                                                    ->map(function ($r) {
                                                                        return [
                                                                            'id' => $r->barang_satuan_id,
                                                                            'satuan' => $r->barangSatuan->nama,
                                                                            'nama' => $r->barangSatuan->barang->nama,
                                                                            'harga' => $r->harga,
                                                                            'qty' => $r->qty,
                                                                            'subtotal' => $r->harga * $r->qty,
                                                                        ];
                                                                    })
                                                                    ->toArray(),
                                                            ];
                                                        })
                                                        ->values()
                                                        ->toArray();
                                                @endphp
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-2">
                                                        <i class="fa fa-prescription-bottle-alt me-2"></i>Resep Obat
                                                    </h6>
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach ($groupedResep as $item)
                                                            <div class="alert alert-primary mb-0 p-3">
                                                                <strong class="fs-12px">Resep {{ $loop->iteration }}:
                                                                    {{ $item['nama'] }}</strong>
                                                                <hr class="my-2 bg-primary bg-opacity-30">
                                                                <div class="ps-1">
                                                                    @foreach ($item['barang'] as $barang)
                                                                        <div
                                                                            class="fs-11px mb-1 d-flex justify-content-between">
                                                                            <span>• {{ $barang['nama'] }} /
                                                                                {{ $barang['satuan'] }}</span>
                                                                            <span
                                                                                class="fw-semibold">{{ $barang['qty'] }}
                                                                                x
                                                                                {{ number_format_id($barang['harga']) }}
                                                                                =
                                                                                {{ number_format_id($barang['subtotal']) }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                @if ($item['catatan'])
                                                                    <div
                                                                        class="mt-2 border-top border-primary border-opacity-20 pt-2 fs-10px">
                                                                        <strong>Catatan Resep:</strong>
                                                                        {{ $item['catatan'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 text-end">
                                                        <span
                                                            class="badge bg-light text-secondary fs-10px border fw-normal">
                                                            <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                            {{ $row->resepObat->first()->pengguna->nama }}
                                                            ({{ $row->resepObat->first()->created_at->format('d M Y, H:i') }})
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- 7. Pembayaran -->
                                            @if ($row->pembayaran)
                                                <div class="mb-4">
                                                    <h6 class="text-success fw-bold mb-2">
                                                        <i class="fa fa-wallet me-2"></i>Pembayaran & Kasir
                                                    </h6>
                                                    <div class="alert alert-success mb-0 p-3">
                                                        <div class="d-flex justify-content-between mb-1 fs-11px">
                                                            <span class="text-muted">No. Nota:</span>
                                                            <strong
                                                                class="text-dark">#{{ $row->pembayaran->id }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1 fs-11px">
                                                            <span class="text-muted">Total Tindakan:</span>
                                                            <span
                                                                class="text-dark fw-semibold">{{ number_format_id($row->pembayaran->total_tindakan) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1 fs-11px">
                                                            <span class="text-muted">Total Resep:</span>
                                                            <span
                                                                class="text-dark fw-semibold">{{ number_format_id($row->pembayaran->total_resep) }}</span>
                                                        </div>
                                                        @if ($row->pembayaran->diskon > 0)
                                                            <div
                                                                class="d-flex justify-content-between mb-1 fs-11px text-danger">
                                                                <span>Diskon:</span>
                                                                <span>-{{ number_format_id($row->pembayaran->diskon) }}</span>
                                                            </div>
                                                        @endif
                                                        <hr class="my-2 bg-success bg-opacity-30">
                                                        <div
                                                            class="d-flex justify-content-between fs-13px fw-bold mb-2">
                                                            <span>Total Tagihan:</span>
                                                            <span
                                                                class="text-success">{{ number_format_id($row->pembayaran->total_tagihan) }}</span>
                                                        </div>

                                                        <div class="row g-2 mt-1">
                                                            <div class="col-6">
                                                                <div
                                                                    class="bg-white bg-opacity-50 p-2 rounded fs-10px border border-success border-opacity-10 h-100">
                                                                    <strong
                                                                        class="d-block mb-1 text-success text-truncate"><i
                                                                            class="fa fa-money-bill-wave me-1"></i>Metode
                                                                        1:
                                                                        {{ $row->pembayaran->metode_bayar ?: '-' }}</strong>
                                                                    <div class="fw-bold text-dark fs-11px mb-1">
                                                                        {{ number_format_id($row->pembayaran->bayar) }}
                                                                    </div>
                                                                    @if ($row->pembayaran->keterangan)
                                                                        <small
                                                                            class="text-muted italic d-block">Catatan:
                                                                            {{ $row->pembayaran->keterangan }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if ($row->pembayaran->metode_bayar_2 || $row->pembayaran->bayar_2 > 0)
                                                                <div class="col-6">
                                                                    <div
                                                                        class="bg-white bg-opacity-50 p-2 rounded fs-10px border border-success border-opacity-10 h-100">
                                                                        <strong
                                                                            class="d-block mb-1 text-success text-truncate"><i
                                                                                class="fa fa-credit-card me-1"></i>Metode
                                                                            2:
                                                                            {{ $row->pembayaran->metode_bayar_2 ?: '-' }}</strong>
                                                                        <div class="fw-bold text-dark fs-11px mb-1">
                                                                            {{ number_format_id($row->pembayaran->bayar_2) }}
                                                                        </div>
                                                                        @if ($row->pembayaran->keterangan_2)
                                                                            <small
                                                                                class="text-muted italic d-block">Catatan:
                                                                                {{ $row->pembayaran->keterangan_2 }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 text-end">
                                                        <span
                                                            class="badge bg-light text-secondary fs-10px border fw-normal">
                                                            <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                            {{ $row->pembayaran->pengguna->nama }}
                                                            ({{ $row->pembayaran->created_at->format('d M Y, H:i') }})
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Footer: Operator & Waktu updates -->
                                    <div
                                        class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top border-light-subtle text-muted fs-11px">
                                        <div>
                                            <i class="fa fa-user-edit me-1"></i>
                                            Penanggung Jawab: <strong
                                                class="text-dark">{{ $row->pengguna?->nama ?: '-' }}</strong>
                                        </div>
                                        <div>
                                            <i class="fa fa-clock me-1"></i>
                                            Terakhir diupdate: {{ $row->updated_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-muted my-5 py-5 bg-light rounded">
                    <i class="fa fa-folder-open fa-3x mb-3 text-gray-300"></i>
                    <p class="mb-0 fs-14px fw-semibold text-secondary">Data Rekam Medis tidak ada</p>
                </div>
            @endif
        </div>
    @endif
    <div wire:loading>
        <x-loading />
    </div>
</div>
