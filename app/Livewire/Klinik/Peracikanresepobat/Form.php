<?php

namespace App\Livewire\Klinik\Peracikanresepobat;

use App\Class\BarangClass;
use App\Models\PeracikanResepObat;
use App\Models\Registrasi;
use App\Models\ResepObat;
use App\Models\Stok;
use App\Traits\CustomValidationTrait;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    use CustomValidationTrait;
    public $dataBarang = [];
    public $dataMetodeBayar = [];
    public $barang = [];
    public $keterangan;
    public $metode_bayar = "Cash";
    public $cash = 0;
    public $total_harga_barang = 0;
    public $diskon = 0;
    public $data;
    public $resep = [];
    public $catatan;

    public function submit()
    {
        if ($this->data->peracikanResepObat) {
            return abort(404);
        }
        $this->resep = collect($this->registrasi->resepObat)
            ->groupBy('resep')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'resep' => $first->resep,
                    'catatan' => $first->catatan,
                    'nama' => $first->nama,
                    'barang' => $group->map(function ($r) {
                        $barang = collect($this->dataBarang)->firstWhere('id', $r->barang_satuan_id);
                        if (!$barang) {
                            return [
                                'id' => null,
                                'nama' => 'Terjadi Kesalahan Resep Obat',
                                'satuan' => null,
                                'kode_akun_id' => null,
                                'kode_akun_penjualan_id' => null,
                                'kode_akun_modal_id' => null,
                                'harga' => null,
                                'qty' => null,
                                'subtotal' => null,
                            ];
                        }
                        return [
                            'id' => $r->barang_satuan_id,
                            'nama' => $barang['nama'],
                            'satuan' => $barang['satuan'],
                            'kode_akun_id' => $barang['kode_akun_id'],
                            'kode_akun_penjualan_id' => $barang['kode_akun_penjualan_id'],
                            'kode_akun_modal_id' => $barang['kode_akun_modal_id'],
                            'harga' => $r->harga,
                            'qty' => $r->qty,
                            'subtotal' => $r->harga * $r->qty,
                        ];
                    })->toArray(),
                ];
            })
            ->values()->toArray();
        $this->validateWithCustomMessages([
            'resep' => 'required|array|min:1',
            'resep.*.barang' => 'required|array|min:1',
            'resep.*.barang.*.id' => 'required|integer',
            'resep.*.barang.*.qty' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) {
                    $parts = explode('.', $attribute); // ['resep', '0', 'barang', '1', 'qty']
                    $resepIndex = $parts[1] ?? null;
                    $barangIndex = $parts[3] ?? null;

                    if (
                        !is_numeric($resepIndex) ||
                        !isset($this->resep[$resepIndex]['barang'][$barangIndex])
                    ) {
                        return;
                    }
                    $barangResep = $this->resep[$resepIndex]['barang'][$barangIndex];

                    $barang = collect($this->dataBarang)->firstWhere('id', $barangResep['id']);
                    if (!$barang) return;

                    $stokTersedia = Stok::where('barang_id', $barang['barang_id'])
                        ->available()
                        ->count();

                    if (($value * $barang['rasio_dari_terkecil']) > $stokTersedia) {
                        $stokAvailable = $stokTersedia / $barang['rasio_dari_terkecil'];
                        $fail("Stok {$barang['nama']} tidak mencukupi. Tersisa {$stokAvailable} {$barang['satuan']}. Yang dibutuhkan untuk resep {$this->resep[$resepIndex]['nama']} {$value} {$barang['satuan']}.");
                    }
                }
            ],
        ]);

        DB::transaction(function () {
            $registrasiId = $this->data->getKey();

            // Delete all resepobat for this registrasi at once
            ResepObat::where('registrasi_id', $registrasiId)->delete();
            PeracikanResepObat::where('id', $registrasiId)->delete();

            $resepObatBatch = [];
            $userId = auth()->id();
            $now = now();

            foreach ($this->resep as $i => $resepRow) {
                $catatan = $resepRow['catatan'] ?? '';
                $nama = $resepRow['nama'] ?? '';
                foreach ($resepRow['barang'] as $barang) {
                    $resepObatBatch[] = [
                        'registrasi_id' => $registrasiId,
                        'nama' => $nama,
                        'barang_id' => collect($this->dataBarang)->firstWhere('id', $barang['id'])['barang_id'],
                        'barang_satuan_id' => $barang['id'],
                        'resep' => $i + 1,
                        'qty' => $barang['qty'],
                        'qty_asli' => $barang['qty'],
                        'harga' => collect($this->dataBarang)->firstWhere('id', $barang['id'])['harga'],
                        'catatan' => $catatan,
                        'pengguna_id' => $userId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            if (!empty($resepObatBatch)) {
                ResepObat::insert($resepObatBatch);
            }

            $peracikan = new PeracikanResepObat();
            $peracikan->id = $registrasiId;
            $peracikan->catatan = $this->catatan;
            $peracikan->pengguna_id = $userId;
            $peracikan->save();

            $data = Registrasi::with('resepObat.barang', 'resepObat.barangSatuan')->findOrFail($registrasiId);
            $cetak = view('livewire.klinik.peracikanresepobat.cetak', [
                'cetak' => true,
                'data' => $data,
            ])->render();
            session()->flash('cetak', $cetak);
            session()->flash('success', 'Berhasil menyimpan data');
        });

        return redirect('/klinik/peracikanresepobat');
    }

    public function mount(Registrasi $data)
    {
        $this->data = $data;
        if ($this->data->peracikanResepObat) {
            return abort(404);
        }
        $this->dataBarang = BarangClass::getBarang('Apotek');
        $resepobat = $data->resepobat;
        if (!$resepobat || $resepobat->isEmpty()) {
            $this->resep[] = [
                'barang' => [],
                'catatan' => '',
            ];
        } else {
            $this->resep = collect($resepobat)
                ->groupBy('resep')
                ->map(function ($group) {
                    $first = $group->first();
                    return [
                        'catatan' => $first->catatan,
                        'nama' => $first->nama,
                        'barang' => $group->map(function ($r) {
                            return [
                                'hapus' => false,
                                'id' => $r->barang_satuan_id,
                                'harga' => $r->harga,
                                'qty' => $r->qty,
                                'subtotal' => $r->harga * $r->qty,
                            ];
                        })->toArray(),
                    ];
                })
                ->values()
                ->toArray();
        }
    }


    public function render()
    {
        return view('livewire.klinik.peracikanresepobat.form');
    }
}
