<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Informasi</li>
        <li class="breadcrumb-item active">Barang Dagang</li>
    @endsection

    @push('css')
        <style>
            /* Merchandise Card Layout */
            .merch-info-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                overflow: hidden;
                margin-bottom: 20px;
            }

            .merch-header {
                background: linear-gradient(135deg, #1e293b, #0d9488);
                color: #fff;
                padding: 16px;
                position: relative;
            }

            .merch-avatar {
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

            .merch-body {
                padding: 16px;
            }

            .merch-meta-label {
                font-size: 10px;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                font-weight: 600;
                margin-bottom: 2px;
            }

            .merch-meta-value {
                font-size: 14px;
                color: #1e293b;
                font-weight: 600;
            }

            .merch-divider {
                height: 1px;
                background-color: #e2e8f0;
                margin: 12px 0;
            }

            /* Pricing/Satuan Table Card */
            .pricing-card {
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .pricing-header {
                background: linear-gradient(135deg, #0f172a, #475569);
                color: #fff;
                padding: 16px;
            }

            .pricing-body {
                padding: 16px;
            }

            .pricing-row-item {
                transition: all 0.2s ease-in-out;
                padding: 10px;
                border-radius: 6px;
            }

            .pricing-row-item:hover {
                background-color: #f1f5f9;
                transform: translateX(2px);
            }
        </style>
    @endpush

    <h1 class="page-header">Informasi Barang Dagang</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <div wire:ignore class="w-100">
                <select class="form-control" x-init="$($el).select2({
                    width: '100%',
                    dropdownAutoWidth: true,
                    placeholder: 'Ketik Nama Barang',
                    minimumInputLength: 1,
                    dataType: 'json',
                    ajax: {
                        url: '/cari/barang',
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
                    $wire.set('barangId', $($el).val());
                });">
                </select>
            </div>
        </div>
    </div>

    <x-alert />

    @if ($dataBarang)
        <div class="card border-0 shadow-sm bg-white p-4 mb-4"
            style="border: 1px solid #e2e8f0 !important; border-radius: 8px;">
            <div class="row g-4">
                <!-- Left Column: Data Barang Dagang -->
                <div class="col-md-6">
                    <div class="merch-info-card mb-0">
                        <div class="merch-header d-flex align-items-center">
                            <div class="merch-avatar me-3">
                                <i class="fa fa-box-open text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-white fw-bold">Data Barang Dagang</h5>
                                <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">
                                    ID: {{ $dataBarang->id }}
                                </span>
                            </div>
                        </div>

                        <div class="merch-body bg-light bg-opacity-50">
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">
                                    <div class="merch-meta-label">Nama Barang</div>
                                    <div class="merch-meta-value text-primary fs-15px">{{ $dataBarang->nama }}</div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="merch-meta-label">Persediaan</div>
                                    <div class="merch-meta-value text-dark">{{ $dataBarang->persediaan }}</div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="merch-meta-label">Kategori / Akun</div>
                                    <div class="merch-meta-value text-dark">{{ $dataBarang->kodeAkun?->nama ?: '-' }}</div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="merch-meta-label">KFA Code</div>
                                    <div class="merch-meta-value text-dark">{{ $dataBarang->kfa ?: '-' }}</div>
                                </div>
                            </div>

                            <div class="merch-divider"></div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="merch-meta-label">Stok Tersedia (Satuan Utama)</div>
                                    <div class="merch-meta-value text-success fs-16px">
                                        {{ $dataBarang->stokTersedia->count() / $dataBarang->barangSatuanUtama->rasio_dari_terkecil }}
                                        {{ $dataBarang->barangSatuanUtama?->nama }}
                                        <span class="fs-12px text-muted fw-normal">{{ $dataBarang->barangSatuanUtama->konversi_satuan }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($dataBarang->perlu_resep == 1 || $dataBarang->khusus == 1)
                                <div class="merch-divider"></div>
                                <div class="d-flex gap-2">
                                    @if($dataBarang->perlu_resep == 1)
                                        <span class="badge bg-warning text-dark px-2.5 py-1.5 fs-11px"><i class="fa fa-prescription-bottle-alt me-1"></i>Perlu Resep</span>
                                    @endif
                                    @if($dataBarang->khusus == 1)
                                        <span class="badge bg-danger text-white px-2.5 py-1.5 fs-11px"><i class="fa fa-exclamation-triangle me-1"></i>Barang Dagang Khusus</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Daftar Satuan & Harga Jual -->
                <div class="col-md-6">
                    <div class="pricing-card">
                        <div class="pricing-header d-flex align-items-center">
                            <div class="merch-avatar me-3" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa fa-tags text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-white fw-bold">Daftar Satuan & Harga Jual</h5>
                                <span class="badge bg-white bg-opacity-20 text-white mt-1 fs-10px">Harga per Satuan</span>
                            </div>
                        </div>
                        <div class="pricing-body">
                            <div class="d-flex flex-column gap-2">
                                @foreach ($dataBarang->barangSatuan as $index => $row)
                                    <div class="pricing-row-item border-bottom pb-2 mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-secondary bg-opacity-15 text-dark fw-bold me-2 fs-10px">{{ $index + 1 }}</span>
                                                <span class="text-dark fw-bold fs-13px">{{ $row->nama }}</span>
                                            </div>
                                            <div class="ps-3">
                                                {!! $row->rasio_dari_terkecil == 1
                                                    ? '<span class="badge bg-success bg-opacity-10 text-success fs-10px">Satuan Terkecil</span>'
                                                    : '<span class="badge bg-warning bg-opacity-10 text-warning fs-10px">' . $row->konversi_satuan . '</span>' !!}
                                                {!! $row->utama == 1 ? '<span class="badge bg-info bg-opacity-10 text-info fs-10px ms-1">Satuan Utama</span>' : '' !!}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fs-10px text-muted text-uppercase fw-600 mb-0.5">Harga Jual</div>
                                            <strong class="text-primary fs-14px">Rp. {{ number_format_id($row->harga_jual) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div wire:loading>
        <x-loading />
    </div>
</div>
