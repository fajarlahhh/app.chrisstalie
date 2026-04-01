<div>
    @section('title', 'Penyusutan Aset')

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Penyusutan Aset</li>
    @endsection

    <h1 class="page-header">Aset</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:;" wire:click="export" class="btn btn-outline-success btn-block">
                Export</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <input type="number" autocomplete="off" wire:model.lazy="tahun" min="2025"
                    max="{{ date('Y') }}" class="form-control w-auto">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.laporan.penyusutanaset.cetak', ['cetak' => false])
        </div>
    </div>
    <x-modal.cetak judul="" />

    <div wire:loading>
        <x-loading />
    </div>
</div>
