<table class="table table-hover">
    <thead>
        <tr>
            <th rowspan="2" class="w-10px">No.</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">Kategori</th>
            <th rowspan="2">ICD 9 CM</th>
            <th rowspan="2" class="text-end">Tarif</th>
            <th colspan="4">Biaya</th>
            <th colspan="2">Keuntungan</th>
            <th rowspan="2">Catatan</th>
            @if ($cetak == false)
                <th rowspan="2"></th>
            @endif
        </tr>
        <tr>
            <th class="text-end">Biaya Alat</th>
            <th class="text-end">Biaya Bahan <small class="text-muted">(Harga Beli Tertinggi)</small></th>
            <th class="text-end">Biaya Jasa Dokter</th>
            <th class="text-end">Biaya Jasa Perawat</th>
            <th class="text-end">Rp.</th>
            <th class="text-end">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $cetak == false ? ($data->currentPage() - 1) * $data->perPage() + $loop->iteration : $loop->iteration }}
                </td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kode_akun_id }} - {{ $item->kodeAkun?->nama }}</td>
                <td>{{ $item->icd_9_cm }}</td>
                <td class="text-end">{{ $cetak == false ? number_format($item->tarif) : $item->tarif }}</td>
                <td class="text-end">
                    {{ $cetak == false ? number_format($item->tarifTindakanAlat->sum(fn($q) => $q->qty * $q->biaya)) : $item->tarifTindakanAlat->sum(fn($q) => $q->qty * $q->biaya) }}
                </td>
                <td class="text-end">
                    {{ $cetak == false ? number_format($item->tarifTindakanBahan->sum(fn($q) => $q->qty * $q->barang?->harga_beli_tertinggi)) : $item->tarifTindakanBahan->sum(fn($q) => $q->qty * $q->barang?->harga_beli_tertinggi) }}
                </td>
                <td class="text-end">
                    {{ $cetak == false ? number_format($item->biaya_jasa_dokter) : $item->biaya_jasa_dokter }}</td>
                <td class="text-end">
                    {{ $cetak == false ? number_format($item->biaya_jasa_perawat) : $item->biaya_jasa_perawat }}</td>
                @php
                    $keuntungan =
                        $item->tarif -
                        $item->tarifTindakanAlat->sum(fn($q) => $q->qty * $q->biaya) -
                        $item->tarifTindakanBahan->sum(fn($q) => $q->qty * $q->barang?->harga_beli_tertinggi) -
                        $item->biaya_jasa_dokter -
                        $item->biaya_jasa_perawat;
                @endphp
                <td class="text-end">
                    {{ $cetak == false ? number_format($keuntungan) : $keuntungan }}
                </td>
                <td class="text-end">
                    {{ $item->tarif != 0 ? ($cetak == false ? number_format(($keuntungan / $item->tarif) * 100) : ($keuntungan / $item->tarif) * 100) : 0 }}
                </td>
                <td nowrap>{!! nl2br(e($item->catatan)) !!}</td>
                @if ($cetak == false)
                    <td class="with-btn-group text-end" nowrap>
                        @role('administrator|supervisor')
                            <x-action :row="$item" custom="" :detail="false" :edit="true" :print="false"
                                :permanentdelete="false" :restore="false" :delete="true" />
                        @endrole
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
