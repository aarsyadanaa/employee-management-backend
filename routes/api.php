<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PdfController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('pegawai', MainController::class);

//login
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [LoginController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout']);
Route::middleware('cors')->group(function () {
    // Rute yang memerlukan CORS
    Route::get('/generate-pdf', [PdfController::class, 'generatePdf']);
});
Route::middleware('cors')->group(function () {
    Route::get('/generate-pdf', [PdfController::class, 'generatePdf']);
});

