@if (count($resep) > 0)
    <template x-for="(row, y) in resep" :key="y" id="resep">
        <tr>
            <td x-text="tindakan.length + y + 1"></td>
            <td class="text-nowrap" colspan="4">
                <strong>Resep : </strong><span x-text="row.nama"></span>
                <br>
                <small class="text-muted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Catatan : <span
                        x-text="row.catatan"></span></small>
            </td>
            <td>
                <input type="text" class="form-control text-end"
                    :value="formatNumber(row.barang.reduce((sum, b) => sum + (b.harga * b.qty), 0))" disabled>
            </td>
            <td class="w-10px">
            </td>
        </tr>
    </template>
    <tr class="bg-light-500">
        <td colspan="5" class="text-end"><strong>Total Resep</strong></td>
        <td>
            <input type="text" class="form-control text-end" :value="formatNumber(total_resep)" disabled>
        </td>
        <td></td>
    </tr>
@endif
