<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori/store', [App\Http\Controllers\KategoriController::class, 'store'])->name('kategori.store');
    
    Route::get('/buku', [App\Http\Controllers\BukuController::class, 'index'])->name('buku.index');
    Route::post('/buku/store', [App\Http\Controllers\BukuController::class, 'store'])->name('buku.store');
});