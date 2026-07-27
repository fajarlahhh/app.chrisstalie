<div>
    @section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengaturan</li>
        <li class="breadcrumb-item">Diskon</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Diskon <small>Pengaturan</small></h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit="submit">
            <div class="panel-body" x-data="{ jenis: @entangle('jenis') }">
                {{-- <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select id="jenis"  class="form-control" wire:model.live="jenis" x-model="jenis">
                        <option value="Tindakan">Tindakan</option>
                        <option value="Barang">Barang Dagang</option>
                    </select>
                    @error('jenis')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3" x-show="jenis == 'Barang'" x-transition>
                    <label class="form-label">Cari Barang</label>
                    <div wire:ignore>
                        <select id="barang_satuan_id"  class="form-control" wire:model="barang_satuan_id" x-init="$($el).select2({
                            width: '100%',
                            dropdownAutoWidth: true
                        });
                        $($el).on('change', function(e) {
                            $wire.set('barang_satuan_id', e.target.value);
                        });">
                            <option value="" selected hidden>-- Pilih Tindakan --</option>
                            @foreach ($dataBarang as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama'] }} (Rp. {{ number_format_id($item['harga']) }} / {{ $item['satuan'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('barang_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}
                <div class="mb-3" x-show="jenis == 'Tindakan'" x-transition>
                    <label class="form-label">Cari Tindakan</label>
                    <div wire:ignore>
                        <select id="tarif_tindakan_id"  class="form-control" wire:model="tarif_tindakan_id" x-init="$($el).select2({
                            width: '100%',
                            dropdownAutoWidth: true
                        });
                        $($el).on('change', function(e) {
                            $wire.set('tarif_tindakan_id', e.target.value);
                        });">
                            <option value="" selected hidden>-- Pilih Tindakan --</option>
                            @foreach ($dataTarifTindakan as $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama'] }} (Rp. {{ number_format_id($item['tarif']) }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('tarif_tindakan_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="date" class="form-control" wire:model="tanggal_mulai" id="tanggal_mulai"
                        min="{{ date('Y-m-d') }}">
                    @error('tanggal_mulai')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tanggal_berakhir">Tanggal Berakhir</label>
                    <input type="date" class="form-control" wire:model="tanggal_berakhir" id="tanggal_berakhir"
                        min="{{ date('Y-m-d') }}">
                    @error('tanggal_berakhir')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="diskon">Diskon</label>
                    <input type="text" class="form-control text-end" wire:model="diskon" id="diskon">
                    @error('diskon')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Uraian</label>
                    <textarea id="uraian"  class="form-control" type="text" wire:model="uraian"></textarea>
                    @error('uraian')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor')
                    <button type="button" onclick="$('#modal-konfirmasi').modal('show');" class="btn btn-success" wire:loading.attr="disabled">
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
