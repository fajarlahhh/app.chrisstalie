<div>
    @section('title', 'Harga Jual')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item active">Harga Jual</li>
    @endsection

    <h1 class="page-header">Harga Jual</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            @role('administrator|supervisor')
                <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'"
                    class="btn btn-outline-secondary btn-block">Tambah</a>&nbsp;
                <a href="javascript:;" wire:click="export" class="btn btn-outline-success btn-block">
                    Export</a>
            @endrole
            <div class="ms-auto d-flex align-items-center">
                <select class="form-control w-auto" wire:model.lazy="barang_id" x-init="$($el).selectpicker({
                    liveSearch: true,
                    width: 'auto',
                    size: 10,
                    container: 'body',
                    style: '',
                    showSubtext: true,
                    styleBase: 'form-control'
                })">
                    <option value="">Semua Barang</option>
                    @foreach ($dataBarang as $item)
                        <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.pengaturan.hargajual.tabel', ['cetak' => false])
        </div>
        <div class="panel-footer">
            {{ $data->links() }}
        </div>
    </div>

    <div wire:loading>
        <x-loading />
    </div>
</div>
