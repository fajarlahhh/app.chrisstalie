<?php

namespace App\Livewire\Manajemenstok\Pengadaanbrgdagang\Verifikasi;

use Livewire\Component;
use App\Models\PengadaanVerifikasi;
use Illuminate\Support\Facades\DB;
use App\Models\PengadaanPermintaan;
use App\Traits\CustomValidationTrait;

class Form extends Component
{
    use CustomValidationTrait;
    public $dataBarang = [], $dataPengguna = [], $barang = [], $data, $status = 'Ditolak', $catatan;

    public function submit()
    {
        if ($this->status == 'Disetujui') {
            $this->validateWithCustomMessages([
                'status' => 'required',
                'barang' => 'required|array',
                'barang.*.qty_disetujui' => 'required|numeric|min:1',
            ]);
        } else {
            $this->validateWithCustomMessages([
                'status' => 'required',
                'catatan' => 'required',
            ]);
        }

        DB::transaction(function () {
            if ($this->status == 'Disetujui') {
                $this->data->pengadaanPermintaanDetail()->delete();
                $this->data->pengadaanPermintaanDetail()->insert(collect($this->barang)->map(fn($q) => [
                    'barang_id' => $q['barang_id'],
                    'qty_permintaan' => $q['qty'],
                    'qty_disetujui' => $q['qty_disetujui'],
                    'barang_satuan_id' => $q['id'],
                    'rasio_dari_terkecil' => $q['rasio_dari_terkecil'],
                    'pengadaan_permintaan_id' => $this->data->id,
                ])->toArray());
            }
            $pengadaanVerifikasi = PengadaanVerifikasi::where('pengadaan_permintaan_id', $this->data->id)->where('jenis', 'Permintaan Pengadaan')->whereNull('status')->first();
            if ($pengadaanVerifikasi) {
                $pengadaanVerifikasi->status = $this->status;
                $pengadaanVerifikasi->catatan = $this->catatan;
                $pengadaanVerifikasi->waktu_verifikasi = now();
                $pengadaanVerifikasi->pengguna_id = auth()->id();
                $pengadaanVerifikasi->save();
            }

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/manajemenstok/pengadaanbrgdagang/verifikasi');
    }

    public function mount(PengadaanPermintaan $data)
    {

        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->barang = $data->pengadaanPermintaanDetail->map(fn($q) => [
            'id' => $q->barang_satuan_id,
            'barang_id' => $q->barang_id,
            'nama' => $q->barangSatuan->barang->nama,
            'satuan' => $q->barangSatuan->nama,
            'rasio_dari_terkecil' => $q->rasio_dari_terkecil,
            'qty' => $q->qty_permintaan,
            'qty_disetujui' => 0,
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.manajemenstok.pengadaanbrgdagang.verifikasi.form');
    }
}
