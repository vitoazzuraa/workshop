<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [App\Http\Controllers\KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [App\Http\Controllers\KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{idkategori}/edit', [App\Http\Controllers\KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/update/{idkategori}', [App\Http\Controllers\KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/destroy/{idkategori}', [App\Http\Controllers\KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/buku', [App\Http\Controllers\BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [App\Http\Controllers\BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku/store', [App\Http\Controllers\BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{idbuku}/edit', [App\Http\Controllers\BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/update/{idbuku}', [App\Http\Controllers\BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/destroy/{idbuku}', [App\Http\Controllers\BukuController::class, 'destroy'])->name('buku.destroy');

    Route::get('/download-sertifikat', [PDFController::class, 'generateSertifikat'])->name('pdf.sertifikat');
    Route::get('/download-undangan', [PDFController::class, 'generateUndangan'])->name('pdf.undangan');

    Route::get('/otp-verification', [App\Http\Controllers\GoogleController::class, 'otpIndex'])->name('otp.index');
    Route::post('/otp-verify', [App\Http\Controllers\GoogleController::class, 'otpVerify'])->name('otp.verify');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id_barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id_barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/print', [BarangController::class, 'print'])->name('barang.print');
});
