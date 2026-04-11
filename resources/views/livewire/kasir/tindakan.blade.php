@if (count($tindakan) > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="w-10px">No.</th>
                <th>Tindakan</th>
                <th class="text-end w-150px">Harga</th>
                <th class="w-100px">Qty</th>
                <th class="w-150px">Diskon <small class="text-muted">(Rp.)</small></th>
                <th class="text-end w-150px">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(row, x) in tindakan" :key="x" id="tindakan">
                <tr>
                    <td x-text="x + 1"></td>
                    <td nowrap>
                        <span x-text="row.nama"></span>
                        <br>
                        <span class="text-muted">
                            &nbsp;&nbsp;&nbsp;- Dokter : <span
                                x-text="row.dokter_id ? dataNakes.find(n => n.id == row.dokter_id)?.nama : '-'"></span>
                            <br>
                            &nbsp;&nbsp;&nbsp;- Perawat : <span
                                x-text="row.perawat_id ? dataNakes.find(n => n.id == row.perawat_id)?.nama : '-'"></span>
                            <br>
                            Catatan : <span x-text="row.catatan ? row.catatan : '-'"></span>
                        </span>
                    </td>
                    <td>
                        <input type="text" class="form-control text-end" :value="formatNumber(row.biaya)" disabled>
                    </td>
                    <td>
                        <input type="text" class="form-control" :value="formatNumber(row.qty)" disabled>
                    </td>
                    <td>
                        <input type="number" class="form-control" @input="hitungTotalTindakan()"
                            x-model.number="row.diskon">
                    </td>
                    <td>
                        <input type="text" class="form-control text-end"
                            :value="formatNumber(row.biaya * row.qty - row.diskon)" disabled>
                    </td>
                </tr>
            </template>
        </tbody>
        <tfoot>
            <tr class="bg-gray-100">
                <td colspan="5" class="text-end">Total Harga Tindakan</td>
                <td>
                    <input type="text" class="form-control text-end" :value="formatNumber(total_tindakan)" disabled>
                </td>
            </tr>
        </tfoot>
    </table>
@endif
