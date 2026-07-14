<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Laba Rugi</li>
    @endsection

    <!-- BEGIN page-header -->
    <h1 class="page-header">Laba Rugi</h1>
    <!-- END page-header -->

    <div class="panel panel-inverse" data-sortable-id="table-basic-2">
        <!-- BEGIN panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <a href="javascript:;" wire:click="export" class="btn btn-outline-success btn-block">
                Export</a>&nbsp;
            <div class="ms-auto d-flex align-items-center">
                <select id="jenis" class="form-control w-auto" wire:model.lazy="jenis">
                    <option value="Bulanan">Bulanan</option>
                    <option value="Tahunan">Tahunan</option>
                </select>&nbsp;
                @if ($jenis == 'Bulanan')
                    <input id="bulan" type="month" autocomplete="off" wire:model.lazy="bulan" min="2025-09"
                        max="{{ date('Y-m') }}" class="form-control w-auto">
                @else
                    <input id="tahun" type="number" autocomplete="off" wire:model.lazy="tahun" min="2025"
                        max="{{ date('Y') }}" class="form-control w-auto">
                @endif
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            @if ($kodeAkunBelumMasuk->count() > 0)
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Ada kode akun yang belum masuk ke dalam laba rugi.
                    <ul>
                        @foreach ($kodeAkunBelumMasuk as $item)
                            <li>{{ $item->id }} - {{ $item->nama }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @include('livewire.laporan.keuanganbulanan.labarugi.' . strtolower($jenis), ['cetak' => false])
        </div>
    </div>
    <x-modal.cetak judul="Laba Rugi" />

    <div wire:loading>
        <x-loading />
    </div>
</div>
