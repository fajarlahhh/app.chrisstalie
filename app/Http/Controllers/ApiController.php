<?php

namespace App\Http\Controllers;

use App\Models\KepegawaianPegawai;
use App\Models\KepegawaianKehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function pegawaiBelumSync(Request $req)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data pegawai belum sync',
            'data' => KepegawaianPegawai::where('sinkron', 0)->select('id', 'nama', 'panggilan', 'alamat')->get()->toArray(),
        ], 200);
    }

    public function pegawaiSimpanSync(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'data' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            $data = json_decode($req->data, true);
            KepegawaianPegawai::where('sinkron', 0)->whereIn('id', collect($data)->pluck('id')->toArray())->update(['sinkron' => 1]);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menyimpan sync pegawai',
                'data' => null
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function simpanKehadiran(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'data' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            $data = json_decode($req->data, true);
            KepegawaianKehadiran::insertOrIgnore(collect($data)->map(function ($q) {
                return [
                    'id' => $q['tanggal'].'-'. $q['waktu'].'-'.$q['pegawai_id'],
                    'kepegawaian_pegawai_id' => $q['pegawai_id'],
                    'waktu' => $q['waktu'],
                    'tanggal' => $q['tanggal'],
                    'kode' => $q['kode'],
                    'masuk' => $q['kode'] == '0' ? $q['waktu'] : null,
                    'pulang' => $q['kode'] == '1' ? $q['waktu'] : null,
                ];
            })->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menyimpan kehadiran',
                'data' => null,
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function pegawai(Request $req)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data pegawai',
            'data' => KepegawaianPegawai::select(
                'id',
                'nik',
                'nama',
                'panggilan',
                'alamat',
                'sinkron',
            )
                ->orderBy('nama')
                ->get()
                ->map(fn ($q) => [
                    'id' => $q->id,
                    'nik' => $q->nik,
                    'nama' => $q->nama,
                    'panggilan' => $q->panggilan,
                    'alamat' => $q->alamat,
                    'sinkron' => $q->sinkron,
                ])
                ->toArray(),
        ], 200);
    }
}
