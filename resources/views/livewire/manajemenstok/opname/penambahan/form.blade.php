<div>
    @section('title', 'Penambahan Opname')

    @section('breadcrumb')
        <li class="breadcrumb-item">Manajemen Stok</li>
        <li class="breadcrumb-item">Opname</li>
        <li class="breadcrumb-item">Penambahan</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Penambahan <small>Opname</small></h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit="submit">
            <div class="panel-body">
                <div x-data="{ transaksi: @entangle('transaksi') }">
                    <div class="mb-3">
                        <label class="form-label">Transaksi</label>
                        <select id="transaksi"  class="form-control" wire:model.live="transaksi" x-model="transaksi">
                            <option value="" selected hidden>-- Pilih Transaksi --</option>
                            <option value="pemindahan">Pemindahan Stok</option>
                            <option value="opname">Opname</option>
                        </select>
                        @error('harga_jual')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cari Barang</label>
                        <div wire:ignore>
                            <select id="barang_id"  class="form-control" wire:model="barang_id" x-init="$($el).select2({
                                width: '100%',
                                dropdownAutoWidth: true
                            });
                            $($el).on('change', function(e) {
                                $wire.set('barang_id', e.target.value);
                            });">
                                <option value="" selected hidden>-- Pilih Barang --</option>
                                @foreach ($dataBarang as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }} </option>
                                @endforeach
                            </select>
                        </div>
                        @error('barang_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3" x-show="transaksi == 'pemindahan'" x-transition>
                        <label class="form-label">Cari Stok Keluar</label>
                        <div wire:ignore>
                            <select id="stok_keluar_id"  class="form-control" wire:model="stok_keluar_id" x-init="$($el).select2({
                                width: '100%',
                                dropdownAutoWidth: true
                            });
                            $($el).on('change', function(e) {
                                $wire.set('stok_keluar_id', e.target.value);
                            });">
                                <option value="" selected hidden>-- Pilih Stok Keluar --</option>
                                @foreach ($dataStokKeluar as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }} (Rp. {{ $item['harga'] }} x
                                        {{ $item['qty'] }} {{ $item['satuan'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('barang_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if ($barang_id)
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <select id="satuan_id"  class="form-control" wire:model.live="satuan_id">
                            <option value="" selected hidden>-- Pilih Satuan --</option>
                            @foreach ($dataBarangSatuan as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama'] }}
                                    {{ $item['konversi_satuan'] ? '(' . $item['konversi_satuan'] . ')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">No. Batch</label>
                    <input id="no_batch"  type="text" class="form-control" wire:model="no_batch">
                    @error('no_batch')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tanggal_kedaluarsa">Tgl. Kedaluarsa</label>
                    <input type="date" class="form-control" wire:model="tanggal_kedaluarsa" id="tanggal_kedaluarsa"
                        min="{{ date('Y-m-d') }}">
                    @error('tanggal_kedaluarsa')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="qty_masuk">Qty Masuk (Dalam Satuan {{ $satuan['nama'] ?? '' }})</label>
                    <input type="number" class="form-control" wire:model="qty_masuk" min="1" autocomplete="off" id="qty_masuk">
                    @error('qty_masuk')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="harga_beli">Harga Beli</label>
                    <input type="text" class="form-control text-end" wire:model="harga_beli" id="harga_beli">
                    @error('harga_beli')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="alert alert-danger">
                    <ul>
                        <li>Jika barang yang dinputkan adalah pemindahan stok dari apotek ke klinik, maka inputkan harga
                            beli nya sesua dengan <strong>Harga Beli/Modal yang dikeluarkan dari stok apotek</strong>
                        </li>
                        <li>Jika barang yang dinputkan adalah barang lebih dari hasil opname, maka inputkan
                            <strong>Harga Beli/Modal = 0</strong>
                        </li>
                    </ul>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea id="catatan"  class="form-control" type="text" wire:model="catatan"></textarea>
                    @error('catatan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor')
                    <button type="button" x-init="$($el).on('click', function() {
                        $('#modal-konfirmasi').modal('show');
                    })" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Submit
                    </button>
                @endrole
                <button type="button" onclick="window.location.href='/manajemenstok/pengurangan'"
                    class="btn btn-warning" wire:loading.attr="disabled">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Kembali
                </button>
                <x-alert />
            </div>
            <x-modal.konfirmasi />
        </form>
    </div>


    <div wire:loading>
        <x-loading />
    </div>
</div>
