<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Arus Kas</li>
    @endsection

    <!-- BEGIN page-header -->
    <h1 class="page-header">Arus Kas</h1>
    <!-- END page-header -->

    <div class="panel panel-inverse" data-sortable-id="table-basic-2">
        <!-- BEGIN panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:;" wire:click="cetak" x-init="$($el).on('click', function() {
                setTimeout(() => {
                    $('#modal-cetak').modal('show')
                }, 1000)
            })" wire:loading.remove class="btn btn-indigo">
                Cetak</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <input id="bulan"  type="month" autocomplete="off" wire:model.lazy="bulan" min="2025-09"
                    max="{{ date('Y-m') }}" class="form-control w-auto">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.laporan.keuanganbulanan.aruskas.cetak', ['cetak' => false])
        </div>
    </div>
    <x-modal.cetak judul="Arus Kas" />

    <div wire:loading>
        <x-loading />
    </div>
</div>
