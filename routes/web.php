<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PDFController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori/store', [App\Http\Controllers\KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{idkategori}/edit', [App\Http\Controllers\KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/update/{idkategori}', [App\Http\Controllers\KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/destroy/{idkategori}', [App\Http\Controllers\KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/buku', [App\Http\Controllers\BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku/store', [App\Http\Controllers\BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{idbuku}/edit', [App\Http\Controllers\BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/update/{idbuku}', [App\Http\Controllers\BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/destroy/{idbuku}', [App\Http\Controllers\BukuController::class, 'destroy'])->name('buku.destroy');

    Route::get('/download-sertifikat', [PDFController::class, 'generateSertifikat']);
    Route::get('/download-undangan', [PDFController::class, 'generateUndangan']);

    Route::get('/otp-verification', [App\Http\Controllers\GoogleController::class, 'otpIndex'])->name('otp.index');
    Route::post('/otp-verify', [App\Http\Controllers\GoogleController::class, 'otpVerify'])->name('otp.verify');
});
