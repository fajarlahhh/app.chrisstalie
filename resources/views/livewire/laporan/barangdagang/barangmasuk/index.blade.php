<div>
    @section('title', 'Barang Masuk')

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Barang Masuk</li>
    @endsection

    <h1 class="page-header">Barang Masuk</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:;" wire:click="print" x-init="$($el).on('click', function() {
                setTimeout(() => {
                    $('#modal-cetak').modal('show')
                }, 1000)
            })" class="btn btn-outline-info btn-block">
                Cetak</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <select id="persediaan"  class="form-control w-auto" wire:model.lazy="persediaan">
                    @role('administrator|supervisor')
                        <option value="">Semua Persediaan</option>
                    @endrole
                    <option value="Apotek">Persediaan Barang Dagang</option>
                    @role('administrator|supervisor')
                        <option value="Klinik">Persediaan Alat & Bahan</option>
                    @endrole
                </select>&nbsp;
                <select id="jenis"  class="form-control w-auto" wire:model.lazy="jenis">
                    <option value="pertransaksi">Per Transaksi</option>
                    <option value="perbarang">Per Barang</option>
                </select>&nbsp;
                <input id="tanggal1"  type="date" min="2025-11-29" max="{{ date('Y-m-d') }}" wire:model.lazy="tanggal1"
                    class="form-control w-auto">&nbsp;s/d&nbsp;
                <input id="tanggal2"  type="date" min="2025-11-29" max="{{ date('Y-m-d') }}" wire:model.lazy="tanggal2"
                    class="form-control w-auto">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.laporan.barangdagang.barangmasuk.cetak', ['cetak' => false])
        </div>
    </div>
    <x-modal.cetak judul="Laporan Barang Masuk" />

    <div wire:loading>
        <x-loading />
    </div>
</div>
