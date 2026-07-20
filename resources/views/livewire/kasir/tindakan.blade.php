@if (count($tindakan) > 0)
    <template x-for="(row, x) in tindakan.filter(i => !i.paket_perawatan_id)" :key="x" id="tindakan">
        <tr>
            <td x-text="registrasi_paket_perawatan.length + x + 1"></td>
            <td nowrap>
                <strong>Tindakan : </strong><span x-text="row.nama"></span>
                <br>
                <small class="text-muted">
                    &nbsp;&nbsp;&nbsp;- Dokter : <span
                        x-text="row.dokter_id ? dataNakes.find(n => n.id == row.dokter_id)?.nama : '-'"></span>
                    <br>
                    &nbsp;&nbsp;&nbsp;- Perawat : <span
                        x-text="row.perawat_id ? dataNakes.find(n => n.id == row.perawat_id)?.nama : '-'"></span>
                    <br>
                    Catatan : <span x-text="row.catatan ? row.catatan : '-'"></span>
                </small>
            </td>
            <td>
                <input type="text" class="form-control text-end" :value="formatNumber(row.biaya)" disabled>
            </td>
            <td>
                <input type="text" class="form-control" :value="formatNumber(row.qty)" disabled>
            </td>
            <td>
                <input type="number" class="form-control" @input="hitungTotalTindakan()" x-model.number="row.diskon" disabled>
            </td>
            <th>
                <input type="text" class="form-control text-end"
                    :value="formatNumber(row.biaya * row.qty - row.diskon)" disabled>
            </th>
            <td></td>
        </tr>
    </template>
    <tr class="bg-light-500">
        <td colspan="5" class="text-end"><strong>Total Harga Tindakan</strong></td>
        <td>
            <input type="text" class="form-control text-end" :value="formatNumber(total_tindakan)" disabled>
        </td>
        <td></td>
    </tr>
@endif
