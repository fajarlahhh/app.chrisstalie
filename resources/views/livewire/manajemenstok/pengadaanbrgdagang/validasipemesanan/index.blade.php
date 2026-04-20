<div>
    @section('title', 'Validasi Pemesanan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengadaan Barang Dagang</li>
        <li class="breadcrumb-item active">Validasi Pemesanan</li>
    @endsection
    <div class="alert alert-warning">Menu ini digunakan untuk melakukan validasi SP terhadap jumlah barang yang datang pada
        satu waktu. Jika ada perbedaan qty, harga, dan supplier, maka wajib dilakukan koreksi/validasi terhadap SP
        tersebut.</div>
    <h1 class="page-header">Validasi Pemesanan <small>Pengadaan Barang Dagang</small></h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading overflow-auto d-flex">
            <div class="ms-auto d-flex align-items-center">
                <select id="status" class="form-control w-auto" wire:model.lazy="status">
                    <option value="Belum Validasi">Belum Validasi</option>
                    <option value="Sudah Validasi">Sudah Validasi</option>
                </select>&nbsp;
                @if ($status == 'Sudah Validasi')
                    <input id="bulan" type="month" class="form-control w-auto" wire:model.lazy="bulan"
                        max="{{ date('Y-m') }}">
                    &nbsp;
                @endif
                <input id="cari" type="text" class="form-control w-auto" placeholder="Cari" autocomplete="off"
                    wire:model.lazy="cari">
            </div>
        </div>
        <div class="panel-body table-responsive">
            <x-alert />
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-10px">No.</th>
                        <th>Data Permintaan</th>
                        <th>Nomor SP</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Supplier</th>
                        <th>Penanggung Jawab</th>
                        <th>Catatan</th>
                        <th class="w-600px">Detail Barang</th>
                        @if ($status == 'Sudah Validasi')
                            <th>Validasi</th>
                        @endif
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td nowrap>
                                <small>
                                    <ul>
                                        <li>Nomor: {{ $item->pengadaanPermintaan?->nomor }}</li>
                                        <li>Deskripsi: {{ $item->pengadaanPermintaan?->deskripsi }}</li>
                                        <li>Tanggal: {{ $item->pengadaanPermintaan?->created_at }}</li>
                                        <li>Jenis Barang: {{ $item->pengadaanPermintaan?->jenis_barang }}</li>
                                    </ul>
                                </small>
                            </td>
                            <td>{{ $item->nomor }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->supplier->nama }}</td>
                            <td>{{ $item->penanggungJawab?->nama }}</td>
                            <td>{{ $item->catatan }}</td>
                            <td>
                                <table class="table table-bordered fs-11px">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty Pemesanan</th>
                                            <th>Qty Pemesanan <br><small>Tervalidasi</small></th>
                                            <th>Qty Masuk</th>
                                            <th>Harga Beli</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->pengadaanPemesananDetail as $detail)
                                            <tr>
                                                <td class="text-nowrap w-300px">
                                                    {{ $detail->barangSatuan->barang->nama }}</td>
                                                <td class="text-nowrap w-80px">
                                                    @if ($detail->barangSatuan->konversi_satuan)
                                                        {!! $detail->barangSatuan->nama . ' <small>' . $detail->barangSatuan->konversi_satuan . '</small>' !!}
                                                    @else
                                                        {{ $detail->barangSatuan->nama }}
                                                    @endif
                                                </td>
                                                <td class="text-nowrap text-end w-80px">
                                                    {{ $detail->qty_lama }}
                                                </td>
                                                <td class="text-nowrap text-end w-80px">
                                                    {{ $detail->qty }}
                                                </td>
                                                <td class="text-nowrap text-end w-80px p-1">
                                                    {{ $item->stokMasuk->where('barang_id', $detail->barang_id)->sum('qty') }}
                                                </td>
                                                <td class="text-nowrap text-end w-80px">
                                                    {{ number_format_id($detail->harga_beli) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                            @if ($status == 'Sudah Validasi')
                                <td nowrap>
                                    <small>
                                        <ul>
                                            <li>Validator: {{ $item->validator?->nama }}</li>
                                            <li>Tanggal Validasi: {{ $item->validated_at }}</li>
                                        </ul>
                                    </small>
                                </td>
                            @endif
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if ($status == 'Sudah Validasi')
                                        @if ($item->stokMasuk->count() > 0)
                                            <x-action :row="$item" custom="" :detail="false" :edit="false"
                                                :print="true" :permanentdelete="false" :restore="false" :delete="false" />
                                        @else
                                            <x-action :row="$item" custom="" :detail="false" :edit="false"
                                                :print="true" :permanentdelete="false" :restore="false"
                                                :delete="true" />
                                        @endif
                                    @else
                                        {{-- @if ($status == 'Sudah Buat SP')
                                                <x-action :row="$item" custom="" :detail="false"
                                                    :edit="true" :print="true" :permanentdelete="false"
                                                    :restore="false" :delete="true" />
                                            @else --}}
                                        <x-action :row="$item" custom="" :detail="false" :edit="true"
                                            :print="true" :permanentdelete="false" :restore="false" :delete="true" />
                                        {{-- @endif --}}
                                    @endif
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            {{ $data->links() }}
        </div>
    </div>

    <div wire:loading>
        <x-loading />
    </div>
    <x-modal.cetak judul='Surat Pemesanan' />
</div>
