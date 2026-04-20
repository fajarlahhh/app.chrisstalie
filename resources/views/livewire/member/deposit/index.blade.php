<div>
    @section('title', 'Deposit Member')

    @section('breadcrumb')
        <li class="breadcrumb-item">Member</li>
        <li class="breadcrumb-item active">Deposit</li>
    @endsection

    <h1 class="page-header">Deposit</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'" class="btn btn-outline-secondary btn-block">
                Tambah</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <input id="tanggal1"  class="form-control w-auto" type="date" wire:model.lazy="tanggal1"
                    max="{{ date('Y-m-d') }}" />&nbsp;
                <input id="tanggal2"  class="form-control w-auto" type="date" wire:model.lazy="tanggal2"
                    max="{{ date('Y-m-d') }}" />&nbsp;
                <input id="cari"  type="text" class="form-control w-auto" placeholder="Cari" autocomplete="off"
                    wire:model.lazy="cari">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.member.deposit.tabel', ['cetak' => false])
        </div>
        <div class="panel-footer">
            {{ $data->links() }}
        </div>
    </div>
    <x-modal.cetak judul='Nota' />

    <div wire:loading>
        <x-loading />
    </div>
</div>
