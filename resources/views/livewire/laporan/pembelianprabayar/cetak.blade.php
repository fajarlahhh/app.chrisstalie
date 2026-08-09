@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Pembelian Paket Prabayar</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $tanggal1 }} s/d {{ $tanggal2 }}</td>
        </tr>
        <tr>
            <th class="w-100px">Pengguna</th>
            <th class="w-10px">:</th>
            <td>{{ $pengguna }}</td>
        </tr>
    </table>
    <br>
@endif

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white text-center" style="width:40px">No.</th>
            <th class="bg-gray-300 text-white">Tanggal</th>
            <th class="bg-gray-300 text-white">No. Transaksi</th>
            <th class="bg-gray-300 text-white">Pasien</th>
            <th class="bg-gray-300 text-white">Paket Perawatan</th>
            <th class="bg-gray-300 text-white">Metode Bayar</th>
            <th class="bg-gray-300 text-white text-end">Jumlah</th>
            <th class="bg-gray-300 text-white">Kasir</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $no => $item)
            <tr>
                <td class="text-center">{{ $no + 1 }}.</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->pasien?->nama ?? '-' }}</td>
                <td>{{ $item->paketPerawatan?->nama ?? '-' }}</td>
                <td>{{ $item->metode_bayar }}</td>
                <td class="text-end">{{ number_format_id($item->bayar) }}</td>
                <td>{{ $item->pengguna?->nama ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-end">Total</th>
            <th class="text-end">{{ number_format_id($data->sum('bayar')) }}</th>
            <th></th>
        </tr>
    </tfoot>
</table>
