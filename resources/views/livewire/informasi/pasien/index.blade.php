<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Informasi</li>
        <li class="breadcrumb-item active">Pasien</li>
    @endsection

    <h1 class="page-header">Informasi Pasien</h1>

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
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center bg-gradient-cyan-blue">
                <div class="patient-avatar me-3">
                    @if (str_starts_with(strtolower($dataPasien->jenis_kelamin ?? ''), 'l'))
                        <i class="fa fa-mars text-white"></i>
                    @elseif(str_starts_with(strtolower($dataPasien->jenis_kelamin ?? ''), 'p'))
                        <i class="fa fa-venus text-white"></i>
                    @else
                        <i class="fa fa-user text-muted"></i>
                    @endif
                </div>
                <div>
                    <h5 class="mb-0 text-white fw-bold">{{ $dataPasien->nama }}</h5>
                    <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                        No. RM: {{ $dataPasien->id }}
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box">
                            <div class="info-box-content">
                                <div class="info-box-label fs-11px text-secondary">No. KTP</div>
                                <div class="info-box-value">{{ $dataPasien->nik ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box">
                            <div class="info-box-content">
                                <div class="info-box-label fs-11px text-secondary">
                                    No. Telpon
                                </div>
                                <div class="info-box-value">{{ $dataPasien->no_hp ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box">
                            <div class="info-box-content">
                                <div class="info-box-label fs-11px text-secondary">Jenis Kelamin / Usia</div>
                                <div class="info-box-value">
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
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box">
                            <div class="info-box-content">
                                <div class="info-box-label fs-11px text-secondary">Tanggal Lahir</div>
                                <div class="info-box-value">
                                    {{ $dataPasien->tanggal_lahir ? $dataPasien->tanggal_lahir->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box">
                            <div class="info-box-content">
                                <div class="info-box-label fs-11px text-secondary">Tanggal Daftar</div>
                                <div class="info-box-value">
                                    {{ $dataPasien->tanggal_daftar ? $dataPasien->tanggal_daftar->format('d M Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-6">
                        <div class="info-box">
                            <div class="info-box-content">
                                <div class="info-box-label">Alamat</div>
                                <div class="info-box-value">{{ $dataPasien->alamat ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($dataPasien->deskripsi)
                    <hr>
                    <div class="info-box">
                        <div class="info-box-icon" style="background: rgba(255, 159, 14, 0.15);">
                            <i class="fa fa-comment-medical"></i>
                        </div>
                        <div class="info-box-content">
                            <div class="info-box-label">Catatan</div>
                            <div class="info-box-value text-muted">{{ $dataPasien->deskripsi }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-white p-4 mb-4"
            style="border: 1px solid #e2e8f0 !important; border-radius: 8px;">
            @if ($dataPasien->pembayaranTerakhir->count() > 0)
                <div x-data="{ activeVisit: '{{ $dataPasien->pembayaranTerakhir->first()->id ?? '' }}' }">
                    <div class="card-body p-0">
                        <!-- Visit History Navigation Tabs (Horizontal at the Top) -->
                        <div class="mb-2">
                            <div class="d-flex flex-nowrap overflow-x-auto gap-2 pb-2">
                                @foreach ($dataPasien->pembayaranTerakhir as $row)
                                    <button type="button" @click="activeVisit = '{{ $row->id }}'"
                                        :class="activeVisit == '{{ $row->id }}' ? 'btn-primary active' : 'btn-gray-100'"
                                        class="btn p-3 rounded shadow-sm d-flex align-items-center justify-content-between flex-shrink-0">
                                        <div class="d-flex align-items-center me-2">
                                            <div class="visit-nav-icon me-3">
                                                <i class="fa fa-calendar-check fs-16px"></i>
                                            </div>
                                            <div class="text-start">
                                                <div class="fw-bold visit-nav-date fs-12px">
                                                    {{ $row->created_at->format('d M Y') }}</div>
                                                <small
                                                    :class="activeVisit == '{{ $row->id }}' ?
                                                        'text-white text-opacity-75' : 'text-secondary'"
                                                    class="d-block fs-10px text-truncate" style="max-width: 130px;">
                                                    # {{ $row->id }}
                                                </small>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Visit Details -->
                        <div>
                            @foreach ($dataPasien->pembayaranTerakhir as $row)
                                <div x-show="activeVisit == '{{ $row->id }}'"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform translate-x-4"
                                    x-transition:enter-end="opacity-100 transform translate-x-0"
                                    class="history-card p-4 border">
                                    <!-- Card Header: Date & Info -->
                                    <div
                                        class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-dark fs-14px">
                                                {{ $row->created_at->format('d F Y') }}
                                            </span>
                                            <span class="mx-2 text-muted">|</span>
                                            <small class="text-secondary">
                                                {{ $row->created_at->format('H:i') }}</small>
                                        </div>
                                        <span class="badge bg-secondary text-white px-2.5 py-1.5 rounded fs-11px">
                                            No. Nota: {{ $row->id }}
                                        </span>
                                    </div>

                                    @if ($row->registrasi_id)
                                        <div class="row g-4 ">
                                            <!-- Left column: Main diagnosis, examinations, TUG -->
                                            <div class="col-lg-12 border-end-lg">
                                                <!-- 1. Registrasi & Nakes -->
                                                <div class="mb-4">
                                                    <h6 class="text-primary fw-bold mb-2">
                                                        <i class="fa fa-file-signature me-2"></i>Registrasi
                                                    </h6>
                                                    <div class="bg-blue-100 p-3 rounded">
                                                        <div class="row g-3">
                                                            <div class="col-sm-4">
                                                                <small
                                                                    class="text-muted d-block fs-10px uppercase fw-600">No. Reg.
                                                                    </small>
                                                                <strong
                                                                    class="text-dark fs-12px">{{ $row->registrasi_id }}</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <small
                                                                    class="text-muted d-block fs-10px uppercase fw-600">Dokter
                                                                    Pemeriksa</small>
                                                                <strong
                                                                    class="text-dark fs-12px">{{ $row->registrasi->nakes?->nama ?: '-' }}</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <small
                                                                    class="text-muted d-block fs-10px uppercase fw-600">Keluhan
                                                                    Awal</small>
                                                                <span
                                                                    class="text-dark fs-12px">{{ $row->registrasi->keluhan_awal ?: '-' }}</span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mt-2 pt-2 text-end">
                                                            <span
                                                                class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                {{ $row->registrasi->pengguna->nama }}
                                                                ({{ $row->registrasi->created_at->format('d M Y, H:i') }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- 2. Hasil Pemeriksaan Awal -->
                                                @if ($row->registrasi->pemeriksaanAwal)
                                                    <div class="mb-4">
                                                        <h6 class="text-success fw-bold mb-2">
                                                            <i class="fa fa-heartbeat me-2"></i>Pemeriksaan Awal
                                                        </h6>
                                                        <div class="bg-success-100 p-3 rounded">
                                                            <span class="text-dark fw-bold d-block mb-2">
                                                                Anamnesis
                                                            </span>
                                                            <div class="row g-3 p-3">
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Keluhan
                                                                        Utama</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->keluhan_utama ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Riwayat
                                                                        Penyakit Sekarang</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->riwayat_sekarang ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Riwayat
                                                                        Dahulu & Keluarga</small>
                                                                    <span
                                                                        class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->riwayat_dahulu ?: '-' }}</span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Riwayat
                                                                        Alergi</small>
                                                                    <span
                                                                        class="text-dark fs-12px text-danger fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->riwayat_alergi ?: '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <span class="text-dark fw-bold d-block mb-2">
                                                                Pemeriksaan Fisik (TTV & Antropometri)
                                                            </span>
                                                            <div class="row g-3 p-3">
                                                                <div class="col-6 col-md-4 ">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Tekanan
                                                                                Darah</small>
                                                                            <strong
                                                                                class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->tekanan_darah ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">mmHg</span></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Nadi</small>
                                                                            <strong
                                                                                class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->nadi ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">x/mnt</span></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Suhu</small>
                                                                            <strong
                                                                                class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->suhu ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">°C</span></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">SpO2</small>
                                                                            <strong
                                                                                class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->saturasi_o2 ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">%</span></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Berat
                                                                                / Tinggi</small>
                                                                            <strong
                                                                                class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->berat_badan ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">kg</span>
                                                                                /
                                                                                {{ $row->registrasi->pemeriksaanAwal?->tinggi_badan ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">cm</span></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Pernapasan</small>
                                                                            <strong
                                                                                class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->pernapasan ?: '-' }}
                                                                                <span
                                                                                    class="fs-10px text-muted fw-normal">x/mnt</span></strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Kesadaran</small>
                                                                            <strong
                                                                                class="text-dark fs-12px text-truncate d-block"
                                                                                style="max-width: 120px;">{{ $row->registrasi->pemeriksaanAwal?->kesadaran ?: '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Kesan
                                                                                Sakit</small>
                                                                            <strong
                                                                                class="text-dark fs-12px text-truncate d-block"
                                                                                style="max-width: 120px;">{{ $row->registrasi->pemeriksaanAwal?->kesan_sakit ?: '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-4">
                                                                    <div class="ttv-card">
                                                                        <div>
                                                                            <small
                                                                                class="text-muted d-block fs-9px uppercase fw-700">Status
                                                                                Gizi</small>
                                                                            <strong
                                                                                class="text-dark fs-12px text-truncate d-block"
                                                                                style="max-width: 120px;">{{ $row->registrasi->pemeriksaanAwal?->status_gizi ?: '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <span class="text-dark fw-bold d-block mb-2">
                                                                Pemeriksaan Head to Toe
                                                            </span>
                                                            <div class="row g-3 p-3">
                                                                <div class="col-md-4 col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Kepala,
                                                                        Mata,
                                                                        THT, Leher</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->kepala_normal == 1 ? 'Normal' : ($row->registrasi->pemeriksaanAwal?->kepala_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Jantung</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->jantung_normal == 1 ? 'Normal' : ($row->registrasi->pemeriksaanAwal?->jantung_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Paru</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->paru_normal == 1 ? 'Normal' : ($row->registrasi->pemeriksaanAwal?->paru_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Abdomen</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->abdomen_normal == 1 ? 'Normal' : ($row->registrasi->pemeriksaanAwal?->abdomen_temuan ?: '-') }}</span>
                                                                </div>
                                                                <div class="col-md-4 col-sm-6">
                                                                    <small
                                                                        class="text-muted d-block fs-10px">Ekstremitas</small>
                                                                    <span
                                                                        class="text-dark fs-12px fw-semibold">{{ $row->registrasi->pemeriksaanAwal?->ekstremitas_normal == 1 ? 'Normal' : ($row->registrasi->pemeriksaanAwal?->ekstremitas_temuan ?: '-') }}</span>
                                                                </div>
                                                            </div>
                                                            <hr>

                                                            @if ($row->registrasi->pemeriksaanAwal?->diagnosis_kerja || $row->registrasi->pemeriksaanAwal?->rencana_awal)
                                                                <div class="row g-3">
                                                                    <div class="col-sm-6">
                                                                        <small
                                                                            class="text-muted d-block fs-10px">Diagnosis
                                                                            Kerja Awal</small>
                                                                        <strong class="text-dark fs-13px text-primary">
                                                                            {{ $row->registrasi->pemeriksaanAwal?->diagnosis_kerja ?: '-' }}</strong>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <small
                                                                            class="text-muted d-block fs-10px">Rencana
                                                                            Awal</small>
                                                                        <span
                                                                            class="text-dark fs-12px">{{ $row->registrasi->pemeriksaanAwal?->rencana_awal ?: '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <hr>
                                                            <div class="mt-2 pt-2 text-end">
                                                                <span
                                                                    class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                    <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                    {{ $row->registrasi->pemeriksaanAwal?->pengguna->nama }}
                                                                    ({{ $row->registrasi->pemeriksaanAwal?->created_at->format('d M Y, H:i') }})
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- 3. Tes Up And Go -->
                                                @if ($row->registrasi->tesUpAndGo)
                                                    <div class="mb-4">
                                                        <h6 class="text-cyan fw-bold mb-2">
                                                            <i class="fa fa-walking me-2"></i>Tes Up And Go
                                                        </h6>
                                                        <div class="bg-cyan-100 p-3 rounded">
                                                            <div class="row g-2 mb-2">
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Waktu
                                                                        Tes</small>
                                                                    <strong
                                                                        class="text-dark fs-12px">{{ $row->registrasi->tug?->waktu_tes_detik }}
                                                                        detik</strong>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <small class="text-muted d-block fs-10px">Risiko
                                                                        Jatuh</small>
                                                                    <span
                                                                        class="badge bg-{{ str_contains(strtolower($row->registrasi->tug?->risiko_jatuh), 'tinggi') ? 'danger' : (str_contains(strtolower($row->registrasi->tug?->risiko_jatuh), 'sedang') ? 'warning' : 'success') }} text-white py-1 px-2">
                                                                        {{ $row->registrasi->tug?->risiko_jatuh }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            @php
                                                                $observasi = is_string(
                                                                    $row->registrasi->tug?->observasi_kualitatif,
                                                                )
                                                                    ? json_decode(
                                                                            $row->registrasi->tug
                                                                                ?->observasi_kualitatif,
                                                                            true,
                                                                        ) ?? []
                                                                    : (is_array(
                                                                        $row->registrasi->tug?->observasi_kualitatif,
                                                                    )
                                                                        ? $row->registrasi->tug?->observasi_kualitatif
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

                                                            @if ($row->registrasi->tug?->catatan)
                                                                <div class="mt-2 border-top pt-2">
                                                                    <small class="text-muted d-block fs-10px">Catatan
                                                                        Tambahan / Rekomendasi</small>
                                                                    <small
                                                                        class="text-dark fs-12px italic">{{ $row->registrasi->tug?->catatan }}</small>
                                                                </div>
                                                            @endif

                                                            <div class="mt-2 pt-2 border-top text-end">
                                                                <span
                                                                    class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                    <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                    {{ $row->registrasi->tug?->pengguna->nama }}
                                                                    ({{ $row->registrasi->tug?->created_at->format('d M Y, H:i') }})
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- 4. Diagnosis (ICD10) -->
                                                @if ($row->registrasi->diagnosis)
                                                    <div class="mb-4">
                                                        <h6 class="text-indigo fw-bold mb-2">
                                                            <i class="fa fa-stethoscope me-2"></i>Diagnosis & ICD-10
                                                        </h6>
                                                        <div class="bg-indigo-100 p-3 rounded">
                                                            @if ($row->registrasi->diagnosis->icd10_uraian && $row->registrasi->diagnosis->icd10_uraian->count() > 0)
                                                                <div class="mb-2">
                                                                    <small
                                                                        class="text-muted d-block fs-10px mb-1">ICD-10</small>
                                                                    @foreach ($row->registrasi->diagnosis->icd10_uraian as $item)
                                                                        <div
                                                                            class="text-dark fs-12px mb-1 fw-semibold">
                                                                            <span
                                                                                class="badge bg-indigo text-white me-2 fs-10px">{{ $item->id }}</span>{{ $item->uraian }}
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            @if ($row->registrasi->diagnosis->diagnosis_banding)
                                                                <div class="mt-2 border-top pt-2">
                                                                    <small class="text-muted d-block fs-10px">Diagnosis
                                                                        Banding (Differential Diagnosis)</small>
                                                                    <strong
                                                                        class="text-dark fs-12px">{{ $row->registrasi->diagnosis->diagnosis_banding }}</strong>
                                                                </div>
                                                            @endif

                                                            @if ($row->registrasi->diagnosis->file && $row->registrasi->diagnosis->file->count() > 0)
                                                                <div class="mt-2 border-top pt-2">
                                                                    <small
                                                                        class="text-muted d-block fs-10px mb-2">Dokumentasi</small>
                                                                    <div class="row g-2">
                                                                        @foreach ($row->registrasi->diagnosis->file as $item)
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
                                                            <hr>
                                                            <div class="mt-2 pt-2 text-end">
                                                                <span
                                                                    class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                    <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                    {{ $row->registrasi->diagnosis->pengguna->nama }}
                                                                    ({{ $row->registrasi->diagnosis->created_at->format('d M Y, H:i') }})
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <!-- 5. Tindakan -->
                                                @if ($row->registrasi->tindakan && $row->registrasi->tindakan->count() > 0)
                                                    <div class="mb-4">
                                                        <h6 class="text-orange fw-bold mb-2">
                                                            <i class="fa fa-syringe me-2"></i>Tindakan Medis
                                                        </h6>
                                                        <div class="row">
                                                            @foreach ($row->registrasi->tindakan as $item)
                                                                <div class="col-lg-6">
                                                                    <div class="alert alert-orange mb-0">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-start">
                                                                            <strong
                                                                                class="fs-12px">{{ $loop->iteration }}.
                                                                                {{ $item->tarifTindakan->nama }}</strong>
                                                                            <span
                                                                                class="badge bg-orange text-white fs-10px">{{ $item->qty }}x</span>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="fs-11px">
                                                                            @if ($item->dokter?->nama)
                                                                                <div class="mb-1"><i
                                                                                        class="fa fa-user-md me-1 text-orange w-15px"></i>Dokter:
                                                                                    {{ $item->dokter->nama }}</div>
                                                                            @endif
                                                                            @if ($item->perawat?->nama)
                                                                                <div class="mb-1"><i
                                                                                        class="fa fa-user-nurse me-1 text-orange w-15px"></i>Perawat:
                                                                                    {{ $item->perawat->nama }}</div>
                                                                            @endif
                                                                            <div class="mt-1 fw-bold">
                                                                                Biaya:
                                                                                {{ number_format_id($item->biaya) }}
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
                                                                                    <a href="/klinik/sitemarking/form/{{ $row->registrasi->id }}"
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
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- 6. Resep Obat -->
                                                @if (collect($row->registrasi->resepObat)->count() > 0)
                                                    @php
                                                        $groupedResep = collect($row->registrasi->resepObat)
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
                                                                                'nama' =>
                                                                                    $r->barangSatuan->barang->nama,
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
                                                        <h6 class="text-pink fw-bold mb-2">
                                                            <i class="fa fa-prescription-bottle-alt me-2"></i>Resep
                                                            Obat
                                                        </h6>
                                                        <div class="bg-pink-100 p-3 rounded">
                                                            <div class="row">
                                                                @foreach ($groupedResep as $item)
                                                                    <div class="col-lg-6 mb-2">
                                                                        <div class="alert bg-pink-200 mb-0">
                                                                            <strong class="fs-12px">Resep
                                                                                {{ $loop->iteration }}:
                                                                                {{ $item['nama'] }}</strong>
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
                                                                                <hr>
                                                                                <div class="mt-2 fs-10px">
                                                                                    <strong>Catatan Resep:</strong>
                                                                                    {{ $item['catatan'] }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <hr>
                                                            <div class="mt-2 text-end">
                                                                <span
                                                                    class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                    <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                    {{ $row->registrasi->resepObat->first()->pengguna->nama }}
                                                                    ({{ $row->registrasi->resepObat->first()->created_at->format('d M Y, H:i') }})
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- 7. Pembayaran -->
                                                <div class="mb-4">
                                                    <h6 class="text-red fw-bold mb-2">
                                                        <i class="fa fa-wallet me-2"></i>Pembayaran & Kasir
                                                    </h6>
                                                    <div class="alert bg-red-100 mb-0 p-3">
                                                        <div class="d-flex justify-content-between mb-1 fs-11px">
                                                            <span class="text-muted">Total Tindakan:</span>
                                                            <span
                                                                class="text-dark fw-semibold">{{ number_format_id($row->total_tindakan) }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1 fs-11px">
                                                            <span class="text-muted">Total Resep:</span>
                                                            <span
                                                                class="text-dark fw-semibold">{{ number_format_id($row->total_resep) }}</span>
                                                        </div>
                                                        @if ($row->diskon > 0)
                                                            <div
                                                                class="d-flex justify-content-between mb-1 fs-11px text-danger">
                                                                <span>Diskon:</span>
                                                                <span>-{{ number_format_id($row->diskon) }}</span>
                                                            </div>
                                                        @endif
                                                        <hr>
                                                        <div
                                                            class="d-flex justify-content-between fs-13px fw-bold mb-2">
                                                            <span>Total Tagihan:</span>
                                                            <span
                                                                class="text-dark">{{ number_format_id($row->total_tagihan) }}</span>
                                                        </div>

                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <div
                                                                    class="bg-red bg-opacity-50 p-2 rounded fs-10px border border-success border-opacity-10 h-100">
                                                                    <strong
                                                                        class="d-block mb-1 text-dark text-truncate"><i
                                                                            class="fa fa-money-bill-wave me-1"></i>Metode
                                                                        1:
                                                                        {{ $row->metode_bayar ?: '-' }}</strong>
                                                                    <div class="fw-bold text-dark fs-11px mb-1">
                                                                        {{ number_format_id($row->bayar) }}
                                                                    </div>
                                                                    @if ($row->keterangan)
                                                                        <small
                                                                            class="text-muted italic d-block">Catatan:
                                                                            {{ $row->keterangan }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if ($row->metode_bayar_2 || $row->bayar_2 > 0)
                                                                <div class="col-6">
                                                                    <div
                                                                        class="bg-white bg-opacity-50 p-2 rounded fs-10px border border-success border-opacity-10 h-100">
                                                                        <strong
                                                                            class="d-block mb-1 text-success text-truncate"><i
                                                                                class="fa fa-credit-card me-1"></i>Metode
                                                                            2:
                                                                            {{ $row->metode_bayar_2 ?: '-' }}</strong>
                                                                        <div class="fw-bold text-dark fs-11px mb-1">
                                                                            {{ number_format_id($row->bayar_2) }}
                                                                        </div>
                                                                        @if ($row->keterangan_2)
                                                                            <small
                                                                                class="text-muted italic d-block">Catatan:
                                                                                {{ $row->keterangan_2 }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <hr>
                                                        <div class="mt-2 text-end">
                                                            <span
                                                                class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                {{ $row->pengguna->nama }}
                                                                ({{ $row->created_at->format('d M Y, H:i') }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row g-4 ">
                                            <!-- Left column: Main diagnosis, examinations, TUG -->
                                            <div class="col-lg-12 border-end-lg">
                                                <!-- 1. Registrasi & Nakes -->
                                                <div class="mb-4">
                                                    <h6 class="text-pink fw-bold mb-2">
                                                        <i class="fa fa-syringe me-2"></i>Pembelian Barang
                                                    </h6>
                                                    <div class="row">
                                                        @foreach ($row->stokKeluar as $item)
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="alert alert-pink mb-0">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start">
                                                                        <strong
                                                                            class="fs-12px">{{ $loop->iteration }}.
                                                                            {{ $item->barang->nama }}</strong>
                                                                        <span
                                                                            class="badge bg-pink text-white fs-10px">{{ $item->qty }}x
                                                                            {{ $item->barangSatuan->nama }}</span>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="fs-11px">
                                                                        <div class="mt-1 fw-bold">
                                                                            Total Harga:
                                                                            {{ number_format_id($item->harga) }} x
                                                                            {{ $item->qty }}
                                                                            =
                                                                            {{ number_format_id($item->harga * $item->qty) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <h6 class="text-red fw-bold mb-2">
                                                        <i class="fa fa-wallet me-2"></i>Pembayaran & Kasir
                                                    </h6>
                                                    <div class="alert bg-red-100 mb-0 p-3">
                                                        <div class="d-flex justify-content-between mb-1 fs-11px">
                                                            <span class="text-muted">Total Pembelian Barang:</span>
                                                            <span
                                                                class="text-dark fw-semibold">{{ number_format_id($row->total_harga_barang) }}</span>
                                                        </div>
                                                        @if ($row->diskon > 0)
                                                            <div
                                                                class="d-flex justify-content-between mb-1 fs-11px text-danger">
                                                                <span>Diskon:</span>
                                                                <span>-{{ number_format_id($row->diskon) }}</span>
                                                            </div>
                                                        @endif
                                                        <hr>
                                                        <div
                                                            class="d-flex justify-content-between fs-13px fw-bold mb-2">
                                                            <span>Total Tagihan:</span>
                                                            <span
                                                                class="text-dark">{{ number_format_id($row->total_tagihan) }}</span>
                                                        </div>

                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <div
                                                                    class="bg-red bg-opacity-50 p-2 rounded fs-10px border border-success border-opacity-10 h-100">
                                                                    <strong
                                                                        class="d-block mb-1 text-dark text-truncate"><i
                                                                            class="fa fa-money-bill-wave me-1"></i>Metode
                                                                        1:
                                                                        {{ $row->metode_bayar ?: '-' }}</strong>
                                                                    <div class="fw-bold text-dark fs-11px mb-1">
                                                                        {{ number_format_id($row->bayar) }}
                                                                    </div>
                                                                    @if ($row->keterangan)
                                                                        <small
                                                                            class="text-muted italic d-block">Catatan:
                                                                            {{ $row->keterangan }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if ($row->metode_bayar_2 || $row->bayar_2 > 0)
                                                                <div class="col-6">
                                                                    <div
                                                                        class="bg-white bg-opacity-50 p-2 rounded fs-10px border border-success border-opacity-10 h-100">
                                                                        <strong
                                                                            class="d-block mb-1 text-success text-truncate"><i
                                                                                class="fa fa-credit-card me-1"></i>Metode
                                                                            2:
                                                                            {{ $row->metode_bayar_2 ?: '-' }}</strong>
                                                                        <div class="fw-bold text-dark fs-11px mb-1">
                                                                            {{ number_format_id($row->bayar_2) }}
                                                                        </div>
                                                                        @if ($row->keterangan_2)
                                                                            <small
                                                                                class="text-muted italic d-block">Catatan:
                                                                                {{ $row->keterangan_2 }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <hr>
                                                        <div class="mt-2 text-end">
                                                            <span
                                                                class="badge bg-light text-secondary fs-10px border fw-normal">
                                                                <i class="fa fa-user me-1 text-muted"></i>Oleh:
                                                                {{ $row->pengguna->nama }}
                                                                ({{ $row->created_at->format('d M Y, H:i') }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
