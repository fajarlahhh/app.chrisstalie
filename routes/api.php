<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('pegawai', [ApiController::class, 'pegawai']);
Route::post('kehadiran/simpan', [ApiController::class, 'simpanKehadiran']);
