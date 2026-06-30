@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-100px" alt="" />
        <br>
        <br>
        <h5>Laporan Laba Rugi</h5>
        <h6>Periode : {{ $tahun }}</h6>
    </div>
@endif

<table class="table table-borderless table-hover fs-11px">
    <thead class="border border-gray-100 border-bottom border-top border-start border-end">
        <tr class="bg-gray-100 text-center">
            <th class="border-bottom border-top border-start border-end p-1">No</th>
            <th class="border-bottom border-top border-start border-end p-1 text-start">Uraian</th>
            <th class="border-bottom border-top border-start border-end p-1">Jan</th>
            <th class="border-bottom border-top border-start border-end p-1">Feb</th>
            <th class="border-bottom border-top border-start border-end p-1">Mar</th>
            <th class="border-bottom border-top border-start border-end p-1">Apr</th>
            <th class="border-bottom border-top border-start border-end p-1">Mei</th>
            <th class="border-bottom border-top border-start border-end p-1">Jun</th>
            <th class="border-bottom border-top border-start border-end p-1">Jul</th>
            <th class="border-bottom border-top border-start border-end p-1">Ags</th>
            <th class="border-bottom border-top border-start border-end p-1">Sep</th>
            <th class="border-bottom border-top border-start border-end p-1">Okt</th>
            <th class="border-bottom border-top border-start border-end p-1">Nov</th>
            <th class="border-bottom border-top border-start border-end p-1">Des</th>
            <th class="border-bottom border-top border-start border-end p-1">Total</th>
        </tr>
    </thead>
    <tbody class="border border-gray-100 border-bottom border-top border-start border-end">
        @foreach ($data as $index => $item)
            <tr>
                <td class="w-10px p-1 text-center">{{ $item['nomor'] }}</td>
                <td class="p-1 border-start border-end text-nowrap">{!! $item['uraian'] !!}</td>
                @for ($i = 1; $i <= 12; $i++)
                    <td class="text-end p-1 border-start border-end">{{ $item['nilai_bulan_'.$i] ?? '' }}</td>
                @endfor
                <td class="text-end p-1 border-start border-end fw-bold">{{ $item['total'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
