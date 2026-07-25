@if (count($resep) > 0)
    <template x-for="(row, y) in resep" :key="'resep-'+y" id="resep">
        <template x-for="(b, i) in row.barang" :key="'barang-'+y+'-'+i">
            <tr>
                <td x-text="i === 0 ? (tindakan.length + y + 1) : ''"></td>
                <td class="text-nowrap">
                    <template x-if="i === 0">
                        <div class="mb-2">
                            <strong>Resep : </strong><span x-text="row.nama"></span>
                            <br>
                            <small class="text-muted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Catatan : <span x-text="row.catatan"></span></small>
                        </div>
                    </template>
                    <div class="ms-4 border-start border-3 ps-2 border-info d-flex align-items-center">
                        <span>
                            <span x-text="b.nama"></span> <span class="badge bg-secondary ms-1" x-text="b.satuan"></span>
                        </span>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control text-end" :value="formatNumber(b.harga)" disabled>
                </td>
                <td>
                    <input type="text" class="form-control text-center" :value="b.qty" disabled>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control text-end" :value="formatNumber(b.diskon || 0)" disabled>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control text-end"
                        :value="formatNumber((parseFloat(b.harga) * parseFloat(b.qty)) - parseFloat(b.diskon || 0))" disabled>
                </td>
                <td class="w-10px">
                </td>
            </tr>
        </template>
    </template>
    <tr class="bg-light-500">
        <td colspan="5" class="text-end"><strong>Total Resep</strong></td>
        <td>
            <input type="text" class="form-control text-end" :value="formatNumber(total_resep)" disabled>
        </td>
        <td></td>
    </tr>
@endif
