<div class="card mb-4">
    <div class="card-header d-flex align-items-center bg-gradient-cyan-blue">
        <div class="patient-avatar me-3">
            @if (str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'l'))
                <i class="fa fa-mars text-white"></i>
            @elseif(str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'p'))
                <i class="fa fa-venus text-white"></i>
            @else
                <i class="fa fa-user text-muted"></i>
            @endif
        </div>
        <div>
            <h5 class="mb-0 text-white fw-bold text-nowrap">{{ $data->pasien->nama }}</h5>
            <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                No. RM: {{ $data->pasien->id }}
            </span>
        </div>
        <div class="text-end w-100 fw-bold fs-14px text-white">#{{ $data->id }}</div>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="info-box">
                    <div class="info-box-content">
                        <div class="info-box-label fs-11px text-secondary">No. KTP</div>
                        <div class="info-box-value">{{ $data->pasien->nik ?: '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="info-box">
                    <div class="info-box-content">
                        <div class="info-box-label fs-11px text-secondary">
                            No. Telpon
                        </div>
                        <div class="info-box-value">{{ $data->pasien->no_hp ?: '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="info-box">
                    <div class="info-box-content">
                        <div class="info-box-label fs-11px text-secondary">Jenis Kelamin / Usia</div>
                        <div class="info-box-value">
                            @if (str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'l'))
                                <span class="text-info"><i class="fa fa-mars me-1"></i>Laki-laki</span>
                            @elseif(str_starts_with(strtolower($data->pasien->jenis_kelamin ?? ''), 'p'))
                                <span class="text-danger" style="color: #ec4899 !important;"><i
                                        class="fa fa-venus me-1"></i>Perempuan</span>
                            @else
                                <span>{{ $data->pasien->jenis_kelamin }}</span>
                            @endif
                            / {{ $data->pasien->umur }} Tahun
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="info-box">
                    <div class="info-box-content">
                        <div class="info-box-label fs-11px text-secondary">Tanggal Lahir</div>
                        <div class="info-box-value">
                            {{ $data->pasien->tanggal_lahir ? $data->pasien->tanggal_lahir->format('d M Y') : '-' }}
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
                            {{ $data->pasien->tanggal_daftar ? $data->pasien->tanggal_daftar->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-6">
                <div class="info-box">
                    <div class="info-box-content">
                        <div class="info-box-label">Alamat</div>
                        <div class="info-box-value">{{ $data->pasien->alamat ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if ($data->pasien->deskripsi)
            <hr>
            <div class="info-box">
                <div class="info-box-content">
                    <div class="info-box-label fs-11px text-secondary">Catatan</div>
                    <div class="info-box-value">{{ $data->pasien->deskripsi }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
