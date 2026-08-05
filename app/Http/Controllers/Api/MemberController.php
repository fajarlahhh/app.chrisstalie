<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function profile(Request $request)
    {
        $member = $request->user()->load(['pasien']);
        // Menambahkan atribut saldo dan poin secara eksplisit agar muncul di JSON
        $member->append(['saldo', 'poin', 'level']);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil profil member',
            'data' => [
                'id' => $member->id,
                'email' => $member->email,
                'nama' => $member->pasien->nama,
                'tanggal_lahir' => $member->pasien->tanggal_lahir->format('Y-m-d'),
                'jenis_kelamin' => $member->pasien->jenis_kelamin,
                'alamat' => $member->pasien->alamat,
                'no_hp' => $member->pasien->no_hp,
                'nik' => $member->pasien->nik,
            ],
        ], 200);
    }

    public function saldo(Request $request)
    {
        $saldo = $request->user()->saldo;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil saldo member',
            'data' => $saldo,
        ], 200);
    }

    public function poin(Request $request)
    {
        $poin = $request->user()->poin;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil poin member',
            'data' => $poin,
        ], 200);
    }

    public function level(Request $request)
    {
        $level = $request->user()->level;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil level member',
            'data' => $level,
        ], 200);
    }

    public function levelProgress(Request $request)
    {
        $member = $request->user();
        $pembayaran = (int) $member->memberPembayaran()
            ->where(\Illuminate\Support\Facades\DB::raw('year(tanggal)'), date('Y'))
            ->sum('total_tagihan');

        $currentLevel = 'Bronze';
        $nextLevel = 'Silver';
        $target = 5000000;
        $prevTarget = 0;

        if ($pembayaran < 5000000) {
            $currentLevel = 'Bronze';
            $nextLevel = 'Silver';
            $target = 5000000;
            $prevTarget = 0;
        } else if ($pembayaran >= 5000000 && $pembayaran < 7000000) {
            $currentLevel = 'Silver';
            $nextLevel = 'Gold';
            $target = 7000000;
            $prevTarget = 5000000;
        } else if ($pembayaran >= 7000000 && $pembayaran < 12000000) {
            $currentLevel = 'Gold';
            $nextLevel = 'Diamond';
            $target = 12000000;
            $prevTarget = 7000000;
        } else if ($pembayaran >= 12000000) {
            $currentLevel = 'Diamond';
            $nextLevel = null;
            $target = $pembayaran; 
            $prevTarget = 12000000;
        }

        $percentage = 100;
        if ($nextLevel) {
            $percentage = round((($pembayaran - $prevTarget) / ($target - $prevTarget)) * 100, 2);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil progress level member',
            'data' => [
                'current_level' => $currentLevel,
                'next_level' => $nextLevel,
                'current_spending' => $pembayaran,
                'target_spending' => $target,
                'next_level_remaining' => $nextLevel ? $target - $pembayaran : 0,
                'progress_percentage' => $percentage,
                'start_spending' => $prevTarget,
            ],
        ], 200);
    }
    public function history(Request $request)
    {
        $member = $request->user();
        $perPage = $request->input('per_page', 10);

        // Query langsung ke Pembayaran milik pasien ini, hanya load relasi yang diperlukan untuk list
        $pembayaranList = \App\Models\Pembayaran::with([
            'registrasi:id',
            'registrasi.registrasiPaketPerawatan:id,registrasi_id,paket_perawatan_id',
            'registrasi.registrasiPaketPerawatan.paketPerawatan:id,nama',
            'registrasi.tindakan:id,registrasi_id,tarif_tindakan_id',
            'registrasi.tindakan.tarifTindakan:id,nama',
            'registrasi.resepObat:id,registrasi_id,barang_satuan_id',
            'registrasi.resepObat.barangSatuan:id,nama',
            'stokKeluar:id,pembayaran_id,barang_id,barang_satuan_id,qty,harga',
            'stokKeluar.barang:id,nama',
            'stokKeluar.barangSatuan:id,nama',
        ])
        ->where('pasien_id', $member->id)
        ->select('id', 'created_at', 'total_tagihan', 'registrasi_id')
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        $history = $pembayaranList->getCollection()->map(function ($pembayaran) {
            // Ambil nama paket jika ada
            $namaPaket = null;
            if ($pembayaran->registrasi && $pembayaran->registrasi->registrasiPaketPerawatan->isNotEmpty()) {
                $namaPaket = $pembayaran->registrasi->registrasiPaketPerawatan->map(function ($rp) {
                    return $rp->paketPerawatan->nama ?? null;
                })->filter()->implode(', ');
            }

            return [
                'id_pembayaran' => $pembayaran->id,
                'tanggal'       => $pembayaran->created_at ? $pembayaran->created_at->format('d F Y') : null,
                'total_tagihan' => $pembayaran->total_tagihan,
                'nama_paket'    => $namaPaket,
                'tindakan'      => $pembayaran->registrasi ? $pembayaran->registrasi->tindakan : [],
                'resep'         => $pembayaran->registrasi ? $pembayaran->registrasi->resepObat->map(fn($r) => $r->barangSatuan->nama ?? null)->filter()->values() : [],
                'pembelian_lainnya' => $pembayaran->stokKeluar->map(fn($s) => [
                    'id'         => $s->id,
                    'nama_barang'=> $s->barang->nama ?? null,
                    'qty'        => $s->qty,
                    'satuan'     => $s->barangSatuan->nama ?? null,
                    'harga'      => $s->harga,
                ])->values(),
            ];
        });

        return response()->json([
            'success'     => true,
            'message'     => 'Berhasil mengambil history kunjungan',
            'data'        => $history,
            'pagination'  => [
                'current_page' => $pembayaranList->currentPage(),
                'per_page'     => $pembayaranList->perPage(),
                'total'        => $pembayaranList->total(),
                'last_page'    => $pembayaranList->lastPage(),
            ],
        ], 200);
    }

    public function historyDetail(Request $request, $id)
    {
        $member = $request->user();

        $pembayaran = \App\Models\Pembayaran::with([
            'registrasi.nakes',
            'registrasi.tug',
            'registrasi.pengguna',
            'registrasi.pemeriksaanAwal.pengguna',
            'registrasi.diagnosis.pengguna',
            'registrasi.tindakan.pengguna',
            'registrasi.tindakan.tarifTindakan',
            'registrasi.tindakan.dokter',
            'registrasi.tindakan.perawat',
            'registrasi.tindakan.barangSatuan',
            'registrasi.tindakan.barangSatuan.barang',
            'registrasi.siteMarking.pengguna',
            'registrasi.resepObat.pengguna',
            'registrasi.resepObat.barangSatuan',
            'registrasi.resepObat.barangSatuan.barang',
            'registrasi.resepObat.barangSatuan.barang.kodeAkun',
            'registrasi.registrasiPaketPerawatan.paketPerawatan',
            'stokKeluar.barang',
            'stokKeluar.barangSatuan',
            'pengguna'
        ])
        ->where('pasien_id', $member->id) // pastikan milik member ini
        ->find($id);

        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Detail history tidak ditemukan atau Anda tidak memiliki akses',
                'data' => null,
            ], 404);
        }

        // Ambil nama paket jika ada
        $namaPaket = null;
        if ($pembayaran->registrasi && $pembayaran->registrasi->registrasiPaketPerawatan->isNotEmpty()) {
            $namaPaket = $pembayaran->registrasi->registrasiPaketPerawatan->map(function ($rp) {
                return $rp->paketPerawatan->nama ?? null;
            })->filter()->implode(', ');
        }

        $detail = [
            'id_pembayaran' => $pembayaran->id,
            'tanggal' => $pembayaran->created_at ? $pembayaran->created_at->format('Y-m-d H:i:s') : null,
            'total_tagihan' => $pembayaran->total_tagihan,
            'nama_paket' => $namaPaket,
            'tindakan' => $pembayaran->registrasi ? $pembayaran->registrasi->tindakan : [],
            'resep' => $pembayaran->registrasi ? $pembayaran->registrasi->resepObat : [],
            'pembelian_lainnya' => $pembayaran->stokKeluar ?? [],
            'detail_lengkap' => $pembayaran
        ];

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil detail history kunjungan',
            'data' => $detail,
        ], 200);
    }
}
