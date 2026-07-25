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
                <template x-if="row.promo && row.promo.length > 0">
                    <select class="form-control mb-1"
                        @change="
                        let val = $event.target.value.toString();
                        if (val.includes('%')) {
                            row.diskon = (parseFloat(val) / 100) * (parseFloat(row.biaya) * parseFloat(row.qty));
                        } else {
                            row.diskon = parseFloat(val) || 0;
                        }
                        hitungTotalTindakan();
                    ">
                        <template x-for="(p, idx) in row.promo" :key="idx">
                            <option :value="p.nilai" x-text="`${p.uraian} (${p.rupiah})`"></option>
                        </template>
                    </select>
                </template>
                <template x-if="row.diskon > 0">
                    <input type="text" class="form-control text-end" :value="formatNumber(row.diskon)" disabled>
                    <span class="text-danger">Diskon Khusus</span>
                </template>
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
