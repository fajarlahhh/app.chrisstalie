<div>
    @section('title', 'Tindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item">Statistik</li>
        <li class="breadcrumb-item active">Tindakan</li>
    @endsection

    <!-- BEGIN page-header -->
    <h1 class="page-header">Tindakan</h1>
    <!-- END page-header -->

    <div class="panel panel-inverse" data-sortable-id="table-basic-2">
        <!-- BEGIN panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:;" wire:click="print" x-init="$($el).on('click', function() {
                setTimeout(() => {
                    $('#modal-cetak').modal('show')
                }, 1000)
            })" class="btn btn-outline-info btn-block">
                Cetak</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <input id="tanggal1"  type="date" autocomplete="off" min="2025-11-29" max="{{ date('Y-m-d') }}"
                    wire:model.lazy="tanggal1" class="form-control w-auto">&nbsp;s/d&nbsp;
                <input id="tanggal2"  type="date" autocomplete="off" min="2025-11-29" max="{{ date('Y-m-d') }}"
                    wire:model.lazy="tanggal2" class="form-control w-auto">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.laporan.statistik.tindakan.cetak', ['cetak' => false])
        </div>
    </div>
    <x-modal.cetak judul="Tindakan" />

    <div wire:loading>
        <x-loading />
    </div>
</div>
