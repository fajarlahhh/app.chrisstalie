<table class="table table-bordered mb-1">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>Item</th>
            <th class="text-end w-150px">Harga</th>
            <th class="w-100px">Qty</th>
            <th class="w-150px">Diskon <small class="text-muted">(Rp.)</small></th>
            <th class="text-end w-150px">Sub Total</th>
            <th class="w-5px"></th>
        </tr>
    </thead>
    <tbody>
        <template x-for="(brg, z) in barang" :key="z" id="barang">
            <tr>
                <td x-text="tindakan.length + resep.length + z + 1"></td>
                <td wire:ignore>
                    <select class="form-control" required x-model="brg.id" x-init="$($el).select2({
                        width: '100%',
                        dropdownAutoWidth: true
                    });
                    $($el).on('change', function(e) {
                        brg.id = e.target.value;
                        updateBarang(z);
                    });
                    $watch('brg.id', (value) => {
                        if (value !== $($el).val()) {
                            $($el).val(value).trigger('change');
                        }
                    });">
                        <option selected hidden>-- Pilih Barang --</option>
                        <template x-for="item in dataBarangApotek" :key="item.id">
                            <option :value="item.id" :selected="brg.id == item.id"
                                x-text="`${item.nama} (Rp. ${new Intl.NumberFormat('id-ID').format(item.harga)} / ${item.satuan})`">
                            </option>
                        </template>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control text-end w-150px" :value="formatNumber(brg.harga)"
                        disabled>
                </td>
                <td>
                    <input type="number" class="form-control w-100px" min="1" step="any"
                        x-model.number="brg.qty" @input="hitungTotalBarang(z)">
                </td>
                <td>
                    <input type="number" class="form-control" @input="hitungTotalBarang(z)" :max="brg.harga * brg.qty"
                        x-model.number="brg.diskon">
                </td>
                <td>
                    <input type="text" class="form-control text-end"
                        :value="formatNumber(brg.harga * brg.qty - parseInt(brg.diskon || 0))" disabled>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" wire:loading.attr="disabled"
                        @click="hapusBarang(z)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </template>
    </tbody>
    <tfoot>
        <tr class="bg-gray-100">
            <td colspan="4">
                <button type="button" wire:loading.attr="disabled" class="btn btn-secondary" @click="tambahBarang">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Tambah Barang
                </button>
                <br>
                <template x-if="$store.wireErrors?.barang">
                    <span class="text-danger" x-text="$store.wireErrors.barang"></span>
                </template>
            </td>
            <td class="text-end">Total Harga Barang</td>
            <td>
                <input type="text" class="form-control text-end" :value="formatNumber(total_barang)" disabled>
            </td>
            <td></td>
        </tr>
    </tfoot>
</table>
