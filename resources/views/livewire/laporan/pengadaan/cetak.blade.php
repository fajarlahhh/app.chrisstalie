@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Pengadaan</h4>
        <br>
        <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-hover">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>Tanggal</th>
            <th>No. Bukti</th>
            <th>Deskripsi</th>
            <th>Jatuh Tempo</th>
            <th>Barang/Item</th>
            <th>Pelunasan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $totalLunas = 0;
        @endphp
        @foreach ($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->date }}</td>
                <td>{{ $row->receipt }}</td>
                <td>{{ $row->uraian }}</td>
                <td>{{ $row->due_date }}</td>
                <td class="w-400px">
                    <table class="table-bordered fs-10px">
                        <tr class="bg-gray-100">
                            <th class="text-nowrap w-250px p-1">Barang/Item</th>
                            <th class="w-100px p-1">Harga Satuan</th>
                            <th class="w-50px p-1">Qty</th>
                            <th class="w-100px p-1">Harga</th>
                        </tr>
                        @foreach ($row->purchaseDetail as $j => $subRow)
                            <tr>
                                <td class="p-1">
                                    {{ $subRow->goods_id ? $subRow->goods?->nama : $subRow->nama }}</td>
                                <td class="text-end p-1  text-nowrap">
                                    {{ number_format_id($subRow->harga) }}</td>
                                <td class="text-end p-1  text-nowrap">
                                    {{ number_format_id($subRow->qty) }}</td>
                                <td class="text-end p-1  text-nowrap">
                                    {{ number_format_id($subRow->qty * $subRow->harga) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="p-1" colspan="3">Total</td>
                            <td class="text-end p-1  text-nowrap">
                                @php
                                    $total += $row->purchaseDetail->sum(fn($q) => $q->harga * $q->qty);
                                @endphp
                                {{ number_format_id($row->purchaseDetail->sum(fn($q) => $q->harga * $q->qty)) }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="text-nowrap">
                    @if ($row->expenditure)
                        @php
                            $totalLunas += $row->purchaseDetail->sum(fn($q) => $q->harga * $q->qty);
                        @endphp
                        {{ $row->expenditure->date }},
                        {{ $row->expenditure->uraian }}<br>
                        {{ $row->expenditure->pengguna->nama }}
                    @endif
                </td>
            </tr>
        @endforeach
        <tr>
            <th colspan="5">TOTAL</th>
            <th class="text-end">{{ number_format_id($total) }}</th>
            <th class="text-end">{{ number_format_id($totalLunas) }}</th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
