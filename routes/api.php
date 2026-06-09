<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('pegawai', [ApiController::class, 'pegawai']);
Route::post('pegawai/belumsync', [ApiController::class, 'pegawaiBelumSync']);
Route::post('pegawai/simpansync', [ApiController::class, 'pegawaiSimpanSync']);

Route::post('kehadiran/simpan', [ApiController::class, 'simpanKehadiran']);
