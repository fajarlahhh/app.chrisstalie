<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class AuthMemberController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors(),
            ], 400);
        }

        $login = $request->login;

        // Cari member berdasarkan ID Member atau Email Pasien
        $member = Member::where('id', $login)
            ->orWhereHas('pasien', function ($query) use ($login) {
                // Asumsikan email ada di tabel pasien
                $query->where('email', $login); 
            })->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan',
                'data' => null,
            ], 404);
        }

        if (!$member || !Hash::check($request->password, $member->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kredensial tidak valid',
                'data' => null,
            ], 401);
        }

        // Generate Sanctum token
        $token = $member->createToken('member-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'member' => $member,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoke token saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
            'data' => null,
        ], 200);
    }

    public function profile(Request $request)
    {
        $member = $request->user()->load(['pasien', 'memberSaldo', 'memberPoin']);
        // Menambahkan atribut saldo dan poin secara eksplisit agar muncul di JSON
        $member->append(['saldo', 'poin', 'level']);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil profil member',
            'data' => $member,
        ], 200);
    }
}
