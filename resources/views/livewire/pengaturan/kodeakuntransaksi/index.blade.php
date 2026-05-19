<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item active">Kode Akun Transaksi</li>
    @endsection

    <h1 class="page-header">Kode Akun Transaksi</h1>

    <form wire:submit.prevent="simpan">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">Form</h4>
            </div>
            <div class="panel-body table-responsive">
                <x-alert />
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Simpan
                </button>
            </div>
        </div>
    </form>
    <x-alert />
    <div wire:loading>
        <x-loading />
    </div>
</div>
