@push('css')
<style>
    .patient-info-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
</style>
@endpush

<div class="patient-info-card mb-3">
    <div class="patient-header d-flex align-items-center">
        <div class="patient-avatar me-3">
            @if(str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'l'))
                <i class="fa fa-mars"></i>
            @elseif(str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'p'))
                <i class="fa fa-venus"></i>
            @else
                <i class="fa fa-user"></i>
            @endif
        </div>
        <div>
            <h5 class="mb-0 text-white fw-bold">{{ $data->pasien->nama }}</h5>
            <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                No. RM: {{ $data->pasien_id }}
            </span>
        </div>
    </div>
    
    <div class="patient-body bg-light bg-opacity-50">
        <div class="row g-3">
            <div class="col-6">
                <div class="patient-meta-label">No. Registrasi</div>
                <div class="patient-meta-value text-primary fw-bold">#{{ $data->id }}</div>
            </div>
            <div class="col-6">
                <div class="patient-meta-label">No. Rekam Medis</div>
                <div class="patient-meta-value">{{ $data->pasien_id }}</div>
            </div>
        </div>
        
        <div class="patient-divider"></div>
        
        <div class="row g-3">
            <div class="col-6">
                <div class="patient-meta-label">Usia</div>
                <div class="patient-meta-value">{{ $data->pasien->umur }} Tahun</div>
            </div>
            <div class="col-6">
                <div class="patient-meta-label">Jenis Kelamin</div>
                <div class="patient-meta-value">
                    @if(str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'l'))
                        <span class="text-info"><i class="fa fa-mars me-1"></i>Laki-laki</span>
                    @elseif(str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'p'))
                        <span class="text-danger" style="color: #ec4899 !important;"><i class="fa fa-venus me-1"></i>Perempuan</span>
                    @else
                        <span>{{ $data->pasien->jenis_kelamin }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

