<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Pembelian Paket Prabayar</li>
    @endsection

    <h1 class="page-header">Pembelian Paket Prabayar</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:;" wire:click="print" x-init="$($el).on('click', function() {
                setTimeout(() => {
                    $('#modal-cetak').modal('show')
                }, 1000)
            })" class="btn btn-outline-info btn-block">
                Cetak</a>&nbsp;
            <div class="ms-auto d-flex align-items-center gap-2">
                <input id="tanggal1" class="form-control w-auto" type="date"
                    wire:model.lazy="tanggal1" />
                <span>s/d</span>
                <input id="tanggal2" class="form-control w-auto" type="date"
                    wire:model.lazy="tanggal2" />
                &nbsp;
                <select id="metode_bayar" class="form-control w-auto" wire:model.lazy="metode_bayar">
                    <option value="">Semua Metode Bayar</option>
                    @foreach ($dataMetodeBayar as $metode)
                        <option value="{{ $metode['nama'] }}">{{ $metode['nama'] }}</option>
                    @endforeach
                </select>
                &nbsp;
                <select id="pengguna_id" class="form-control w-auto" wire:model.lazy="pengguna_id">
                    <option value="">Semua Pengguna</option>
                    @foreach ($dataPengguna as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @include('livewire.laporan.pembelianprabayar.cetak', ['cetak' => false])
        </div>
    </div>
    <x-modal.cetak judul="" />

    <div wire:loading>
        <x-loading />
    </div>
</div>
