<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Member</li>
        <li class="breadcrumb-item active">Paket Prabayar</li>
    @endsection

    <h1 class="page-header">Paket Prabayar</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'"
                class="btn btn-outline-secondary btn-block">
                Tambah</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <select name="status" id="status" class="form-control w-auto" wire:model.live="status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>&nbsp;
                <input id="cari" type="text" class="form-control w-auto" placeholder="Cari" autocomplete="off"
                    wire:model.lazy="cari">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.member.paketprabayar.tabel', ['cetak' => false])
        </div>
        <div class="panel-footer">
            {{ $data->links() }}
        </div>
    </div>
    <x-modal.cetak judul='Bukti Pendaftaran' />

    <div wire:loading>
        <x-loading />
    </div>
</div>
