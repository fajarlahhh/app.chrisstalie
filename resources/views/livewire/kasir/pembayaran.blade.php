<div class="mb-3">
    <label class="form-label text-danger">Total Diskon</label>
    <input class="form-control text-end text-bold fs-20px text-danger" type="text" disabled
        :value="formatNumber(total_diskon_tindakan + total_diskon_barang)" />
</div>
<div class="mb-3 text-success">
    <label class="form-label">Total Tagihan</label>
    <input class="form-control text-end text-bold fs-20px text-success" type="text" disabled :value="formatNumber(total_tagihan)" />
</div>
@role('administrator|supervisor')
    <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input id="tanggal"  class="form-control" type="date" wire:model="tanggal" x-model="tanggal" />
        @error('tanggal')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
@endrole
<hr>
<div class="note alert-success mb-2">
    <div class="note-content">
        <h5>Pembayaran</h5>
        <hr>
        <div class="mb-3">
            <label class="form-label">Metode Bayar 1</label>
            <div class="input-group">
                <select id="metode_bayar"  class="form-control" wire:model="metode_bayar" x-model="metode_bayar" data-width="100%"
                    @change="if (metode_bayar != 1) {
                        cash = total_tagihan;
                        cash_2 = 0;
                    } else {
                        cash = 0;
                        cash_2 = 0;
                    }">
                    <option hidden>-- Pilih Metode Bayar --</option>
                    <template x-for="item in dataMetodeBayar" :key="item.id">
                        <option :value="item.id" x-text="item.nama" :selected="metode_bayar == item.id"></option>
                    </template>
                </select>
                <input id="cash"  class="form-control text-end fs-16px text-bold" type="number" wire:model="cash" x-model.number="cash"
                    @input="if (parseInt(cash || 0) == 0 || parseInt(cash || 0) >= parseInt(total_tagihan || 0)){
                        cash_2 = 0;
                    }"
                    x-effect="
                    if (metode_bayar != 1) {
                        cash = total_tagihan;
                    } else {
                        cash = 0;
                    }
                " />
            </div>
        </div>
        <template x-if="parseInt(cash) < parseInt(total_tagihan) && cash > 0">
            <div class="mb-3">
                <label class="form-label">Metode Bayar 2</label>
                <div class="input-group">
                    <select id="metode_bayar_2"  class="form-control" wire:model="metode_bayar_2" x-model="metode_bayar_2" data-width="100%">
                        <option hidden>-- Pilih Metode Bayar --</option>
                        <template x-for="item in dataMetodeBayar" :key="item.id">
                            <option :value="item.id" x-text="item.nama" :selected="metode_bayar_2 == item.id">
                            </option>
                        </template>
                    </select>
                    <input id="cash_2"  class="form-control text-end fs-16px" type="number" wire:model="cash_2"
                        x-model.number="cash_2"
                        x-effect="
                        if (parseInt(total_tagihan || 0) - parseInt(cash || 0) <= 0) {
                            cash_2 = 0;
                        } else {
                            cash_2 = Math.max(parseInt(total_tagihan || 0) - parseInt(cash || 0), 0);
                        }
                    " />
                </div>
            </div>
        </template>
        <hr>
        <div class="mb-3">
            <label class="form-label">Uang Kembali</label>
            <input class="form-control text-end" type="text" disabled
                :value="formatNumber((cash + (cash_2 > 0 ? parseInt(cash_2 || 0) : 0) > parseInt(total_tagihan || 0)) ?
                    cash + parseInt(cash_2 || 0) - parseInt(total_tagihan || 0) :
                    0)" />
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea id="keterangan"  class="form-control" type="text" wire:model="keterangan" x-model="keterangan"></textarea>
            @error('keterangan')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
