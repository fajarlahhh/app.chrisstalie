@if (count($registrasi_paket_perawatan) > 0)
    <template x-for="(row, x) in registrasi_paket_perawatan" :key="x" id="registrasi_paket_perawatan">
        <tr>
            <td x-text="x + 1"></td>
            <td nowrap>
                <strong>Paket Perawatan : </strong><span x-text="row.nama"></span><br>
                <template x-if="row.jenis == 'Prabayar'">
                    <small class="text-danger ms-4"><span x-text="row.kode_akun_pembayaran_nama"></span> Rp. <span x-text="formatNumber(row.prabayar)"></span></small>
                </template>
            </td>
            <td>
                <input type="text" class="form-control text-end" :value="formatNumber(row.biaya)" disabled>
            </td>
            <td>
                <input type="text" class="form-control" :value="formatNumber(row.qty)" disabled>
            </td>
            <td>
                <input type="number" class="form-control" disabled>
            </td>
            <th>
                <input type="text" class="form-control text-end"
                    :value="formatNumber(row.biaya * row.qty - row.diskon)" disabled>
            </th>
            <td></td>
        </tr>
    </template>
    <tr class="bg-light-500">
        <td colspan="5" class="text-end"><strong>Total Harga Paket Perawatan</strong></td>
        <td>
            <input type="text" class="form-control text-end" :value="formatNumber(total_registrasi_paket_perawatan)" disabled>
        </td>
        <td></td>
    </tr>
@endif
