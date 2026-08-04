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
            ->orWhere('email', $login)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member tidak ditemukan',
                'data' => null,
            ], 404);
        }

        // if (!$member || !Hash::check($request->password, $member->password)) {
        // if (!Hash::check($request->password, $member->password)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Kredensial tidak valid',
        //         'data' => null,
        //     ], 401);
        // }

        // Generate Sanctum token
        $token = $member->createToken('member-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'member' => [
                    'id' => $member->id,
                    'email' => $member->email,
                    'nama' => $member->pasien->nama,
                    'tanggal_lahir' => $member->pasien->tanggal_lahir->format('Y-m-d'),
                    'jenis_kelamin' => $member->pasien->jenis_kelamin,
                    'alamat' => $member->pasien->alamat,
                    'no_hp' => $member->pasien->no_hp,
                    'nik' => $member->pasien->nik,
                ],
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
}
