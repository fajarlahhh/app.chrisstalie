@if (count($resep) > 0)
    <tr>
        <th class="w-10px">No.</th>
        <th colspan="4">Resep</th>
        <th class="text-end w-150px">Sub Total</th>
        <th class="w-5px"></th>
    </tr>
    <template x-for="(row, y) in resep" :key="y" id="resep">
        <tr>
            <td x-text="tindakan.length + y + 1"></td>
            <td class="text-nowrap" colspan="4">
                <span x-text="row.resep"></span>. <span x-text="row.nama"></span>
                <br>
                <span class="text-muted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Catatan : <span
                        x-text="row.catatan"></span></span>
            </td>
            <td>
                <input type="text" class="form-control text-end"
                    :value="formatNumber(row.barang.reduce((sum, b) => sum + (b.harga * b.qty), 0))" disabled>
            </td>
            <td class="w-10px">
                <button type="button" class="btn btn-danger btn-sm mt-2px" wire:loading.attr="disabled"
                    @click="hapusResep(y)">
                    <i class="fa fa-times"></i>
                </button>
            </td>
        </tr>
    </template>
    <tr class="bg-gray-100">
        <td colspan="5" class="text-end">Total Resep</td>
        <td>
            <input type="text" class="form-control text-end" :value="formatNumber(total_resep)" disabled>
        </td>
        <td></td>
    </tr>
@endif
