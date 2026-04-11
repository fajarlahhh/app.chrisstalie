@if (count($resep) > 0)
    <table class="table table-bordered mb-1">
        <thead>
            <tr>
                <th class="w-10px">No.</th>
                <th>Resep</th>
                <th class="text-end w-150px">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="(rsp, y) in resep" :key="y" id="resep">
                <tr>
                    <td x-text="rsp.resep"></td>
                    <td class="text-nowrap">
                        <span x-text="rsp.nama"></span>
                        <br>
                        <span class="text-muted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Catatan : <span
                                x-text="rsp.catatan"></span></span>
                    </td>
                    <td>
                        <input type="text" class="form-control text-end"
                            :value="formatNumber(rsp.detail.reduce((sum, b) => sum + (b.harga * b.qty), 0))" disabled>
                    </td>
                </tr>
            </template>
        </tbody>
        <tfoot>
            <tr class="bg-gray-100">
                <td class="text-end" colspan="2">Total Resep</td>
                <td>
                    <input type="text" class="form-control text-end" :value="formatNumber(total_resep)" disabled>
                </td>
            </tr>
        </tfoot>
    </table>
@endif
