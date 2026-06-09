<?php

namespace App\Http\Controllers;

use App\Models\KepegawaianPegawai;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function pegawai(Request $req)
    {
        return response()->json([
            'status' => 'sukses',
            'data' => KepegawaianPegawai::select(
                'id',
                'nama',
                'panggilan',
                'alamat',
            )
                ->orderBy('nama')
                ->get()
                ->map(fn ($q) => [
                    'id' => $q->id,
                    'nama' => $q->nama,
                    'panggilan' => $q->panggilan,
                    'alamat' => $q->alamat,
                ])
                ->toArray(),
        ], 200);
    }
}
