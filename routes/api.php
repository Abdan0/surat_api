<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DisposisiController;


// Auth API
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('user-profile', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Surat API
Route::middleware('auth:api')->group(function () {
    Route::get('surat', [SuratController::class, 'index']);
    Route::post('surat', [SuratController::class, 'store']);
    Route::get('surat/{id}', [SuratController::class, 'show']);
    Route::put('surat/{id}', [SuratController::class, 'update']);
    Route::patch('surat/{id}', [SuratController::class, 'update']);
    Route::delete('surat/{id}', [SuratController::class, 'destroy']);
});

// Kategori API
Route::middleware('auth:api')->group(function () {
    Route::get('kategori', [KategoriController::class, 'index']);
    Route::post('kategori', [KategoriController::class, 'store']);
    Route::get('kategori/{id}', [KategoriController::class, 'show']);
    Route::put('kategori/{id}', [KategoriController::class, 'update']);
    Route::patch('kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('kategori/{id}', [KategoriController::class, 'destroy']);
});

//Disposi API
Route::middleware('auth:api')->group(function () {
    Route::get('disposisi', [DisposisiController::class, 'index']);
    Route::post('disposisi', [DisposisiController::class, 'store']);
    Route::get('disposisi/{id}', [DisposisiController::class, 'show']);
    Route::put('disposisi/{id}', [DisposisiController::class, 'update']);
    Route::patch('disposisi/{id}', [DisposisiController::class, 'update']);
    Route::delete('disposisi/{id}', [DisposisiController::class, 'destroy']);
});

// Agenda API
Route::middleware('auth:api')->group(function () {
    Route::get('agenda', [AgendaController::class, 'index']);
    Route::post('agenda', [AgendaController::class, 'store']);
    Route::get('agenda/{id}', [AgendaController::class, 'show']);
    Route::put('agenda/{id}', [AgendaController::class, 'update']);
    Route::patch('agenda/{id}', [AgendaController::class, 'update']);
    Route::delete('agenda/{id}', [AgendaController::class, 'destroy']);
});
