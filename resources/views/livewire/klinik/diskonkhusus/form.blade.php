<div x-data="tindakanForm()" x-init="init()" x-ref="alpineRoot">
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Tindakan</li>
        <li class="breadcrumb-item active">Input</li>
    @endsection

    <h1 class="page-header">Tindakan <small>Input</small></h1>
    @include('livewire.klinik.informasipasien', ['data' => $data])

    <form wire:submit.prevent="submit" @submit.prevent="syncToLivewire()"
        @keydown.enter="if ($event.target.tagName !== 'TEXTAREA') $event.preventDefault()">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">Form</h4>
            </div>
            <div class="panel-body">
                <div class="mb-3">
                    <span class="text-muted fw-bold d-block text-uppercase mb-1">Tindakan</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Tindakan</th>
                                <th style="width: 250px;">Promo Sistem</th>
                                <th>Harga Normal</th>
                                <th style="width: 180px;">Diskon Khusus (%)</th>
                                <th>Harga Setelah Diskon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, index) in tindakan" :key="'tindakan-'+index">
                                <tr>
                                    <td class="align-middle">
                                        <span class="fw-bold text-dark d-block" x-text="row.nama"></span>
                                    </td>
                                    <td class="align-middle">
                                        <template x-if="row.promo_ultah > 0 || row.promo_tindakan != 0">
                                            <div :class="row.diskon > 0 ? 'opacity-50' : ''">
                                                <template x-if="row.promo_ultah > 0">
                                                    <div class="text-danger small mb-1 fw-bold">
                                                        <i class="fa fa-gift me-1"></i> Ultah: -Rp <span x-text="new Intl.NumberFormat('id-ID').format(row.promo_ultah)"></span>
                                                    </div>
                                                </template>
                                                <template x-if="row.promo_tindakan != 0">
                                                    <div class="text-success small fw-bold">
                                                        <i class="fa fa-percent me-1"></i> <span x-text="row.promo_tindakan.uraian || 'Tindakan'"></span>: -Rp <span x-text="new Intl.NumberFormat('id-ID').format(row.promo_tindakan.harga_diskon)"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!(row.promo_ultah > 0 || row.promo_tindakan != 0)">
                                            <span class="text-muted small">-</span>
                                        </template>
                                        <template x-if="row.diskon > 0">
                                            <div class="alert alert-warning p-1 px-2 mt-2 mb-0 border-warning border-opacity-25" style="font-size: 11px;">
                                                <i class="fa fa-info-circle text-warning"></i> <strong>Catatan:</strong> Promo Sistem ini tidak akan berlaku karena menggunakan Diskon Khusus.
                                            </div>
                                        </template>
                                    </td>
                                    <td class="align-middle text-nowrap">
                                        <span :class="row.diskon > 0 ? 'text-decoration-line-through text-muted opacity-50' : ''">
                                            Rp. <span x-text="new Intl.NumberFormat('id-ID').format(row.harga_jual * row.qty)"></span>
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control text-end fw-bold text-primary" x-model.number="row.diskon" min="0" max="100" placeholder="0">
                                            <span class="input-group-text bg-white fw-bold text-muted">%</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-nowrap">
                                        <span class="fw-bold text-success">
                                            Rp. <span x-text="new Intl.NumberFormat('id-ID').format((row.harga_jual * row.qty) - ((row.harga_jual * row.qty) * (row.diskon || 0) / 100))"></span>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Resep Obat Section -->
                <table class="table table-borderless p-0 mt-3">
                    <template x-for="(group, groupIndex) in resep" :key="'resep-'+groupIndex">
                        <tr>
                            <td class="p-3 border">
                                <div class="mb-3">
                                    <span class="text-muted fw-bold d-block text-uppercase mb-1">Resep Obat</span>
                                    <span class="fw-bold fs-4 text-dark" x-text="group.resep"></span>
                                    <div class="text-muted small" x-text="group.catatan"></div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Nama Obat</th>
                                                <th>Harga Normal</th>
                                                <th style="width: 200px;">Diskon Khusus (%)</th>
                                                <th>Harga Setelah Diskon</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(barang, itemIndex) in group.barang" :key="'barang-'+itemIndex">
                                                <tr>
                                                    <td class="align-middle">
                                                        <span class="fw-bold text-dark d-block" x-text="barang.nama"></span>
                                                        <span class="text-muted small" x-text="'Qty: ' + barang.qty + ' ' + (barang.satuan || '')"></span>
                                                    </td>
                                                    <td class="align-middle text-nowrap">
                                                        <span :class="barang.diskon > 0 ? 'text-decoration-line-through text-muted opacity-50' : ''">
                                                            Rp. <span x-text="new Intl.NumberFormat('id-ID').format(barang.harga * barang.qty)"></span>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" class="form-control text-end fw-bold text-primary" x-model.number="barang.diskon" min="0" max="100" placeholder="0">
                                                            <span class="input-group-text bg-white fw-bold text-muted">%</span>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-nowrap">
                                                        <span class="fw-bold text-success">
                                                            Rp. <span x-text="new Intl.NumberFormat('id-ID').format((barang.harga * barang.qty) - ((barang.harga * barang.qty) * (barang.diskon || 0) / 100))"></span>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </template>
                </table>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Submit
                    </button>
                @endrole
                <button type="button" class="btn btn-secondary m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/tindakan'">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Data
                </button>
                <x-alert />
            </div>


            <x-modal.konfirmasi />
    </form>
</div>

<div wire:loading>
    <x-loading />
</div>
</div>

@push('scripts')
    <script>
        function tindakanForm() {
            return {
                tindakan: @js($tindakan),
                resep: @js($resep),
                dataTindakan: @js($dataTindakan),

                syncToLivewire() {
                    // Sinkronkan data ke Livewire
                    if (window.Livewire && window.Livewire.find) {
                        let componentId = this.$root.closest('[wire\\:id]')?.getAttribute('wire:id');
                        if (componentId) {
                            let $wire = window.Livewire.find(componentId);
                            if ($wire && typeof $wire.set === 'function') {
                                $wire.set('tindakan', JSON.parse(JSON.stringify(this.tindakan)), false);
                                $wire.set('resep', JSON.parse(JSON.stringify(this.resep)), false);
                            }
                        }
                    }
                },
                init() {},
            }
        }
    </script>
@endpush
