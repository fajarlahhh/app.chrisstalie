<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthMemberController;

Route::post('pegawai', [ApiController::class, 'pegawai']);
Route::post('pegawai/belumsync', [ApiController::class, 'pegawaiBelumSync']);
Route::post('pegawai/simpansync', [ApiController::class, 'pegawaiSimpanSync']);

Route::post('kehadiran/simpan', [ApiController::class, 'simpanKehadiran']);

Route::post('member/login', [AuthMemberController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('member/logout', [AuthMemberController::class, 'logout']);
});
