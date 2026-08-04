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
        $saldo = $request->user()->memberSaldo()->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil riwayat saldo member',
            'data' => $saldo,
        ], 200);
    }

    public function poin(Request $request)
    {
        $poin = $request->user()->memberPoin()->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil riwayat poin member',
            'data' => $poin,
        ], 200);
    }
}
