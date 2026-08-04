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
            'data' => $member,
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
