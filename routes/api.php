<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthMemberController;

use App\Http\Controllers\Api\MemberController;

Route::post('pegawai', [ApiController::class, 'pegawai']);
Route::post('pegawai/belumsync', [ApiController::class, 'pegawaiBelumSync']);
Route::post('pegawai/simpansync', [ApiController::class, 'pegawaiSimpanSync']);

Route::post('kehadiran/simpan', [ApiController::class, 'simpanKehadiran']);

Route::post('member/login', [AuthMemberController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('member/logout', [AuthMemberController::class, 'logout']);
    Route::get('member/level', [MemberController::class, 'level']);
    Route::get('member/level-progress', [MemberController::class, 'levelProgress']);
    Route::get('member/profile', [MemberController::class, 'profile']);
    Route::get('member/saldo', [MemberController::class, 'saldo']);
    Route::get('member/poin', [MemberController::class, 'poin']);
    Route::get('member/history', [MemberController::class, 'history']);
    Route::get('member/history/{id}', [MemberController::class, 'historyDetail']);
});
