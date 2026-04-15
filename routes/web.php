<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KatalogMenuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PesananMasukController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rute Halaman Utama (Katalog)
Route::get('/', [KatalogMenuController::class, 'index'])->name('landing');

// Rute untuk Guest (Pengunjung)
Route::get('/katalog', [KatalogMenuController::class, 'index'])->name('katalog.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/midtrans-callback', [MidtransWebhookController::class, 'handle']);

Auth::routes();

// Rute yang WAJIB Login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Modul Buku: Manajemen Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{idkategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/update/{idkategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/destroy/{idkategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Modul Buku: Manajemen Koleksi
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku/store', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{idbuku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/update/{idbuku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/destroy/{idbuku}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Modul UMKM: Manajemen Barang
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id_barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id_barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/print', [BarangController::class, 'print'])->name('barang.print');

    // Modul Kasir & Wilayah
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/cari-barang', [KasirController::class, 'cariBarang'])->name('kasir.cari');
    Route::post('/kasir/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::post('/wilayah/regency', [WilayahController::class, 'getRegency'])->name('wilayah.regency');
    Route::post('/wilayah/district', [WilayahController::class, 'getDistrict'])->name('wilayah.district');
    Route::post('/wilayah/village', [WilayahController::class, 'getVillage'])->name('wilayah.village');

    // Fitur PDF & Keamanan
    Route::get('/download-sertifikat', [PDFController::class, 'generateSertifikat'])->name('pdf.sertifikat');
    Route::get('/download-undangan', [PDFController::class, 'generateUndangan'])->name('pdf.undangan');
    Route::get('/otp-verification', [GoogleController::class, 'otpIndex'])->name('otp.index');
    Route::post('/otp-verify', [GoogleController::class, 'otpVerify'])->name('otp.verify');

    // PAYMENT GATEWAY: Fitur Khusus Vendor (Menu & Pesanan)
    Route::get('/menu', [MenuController::class, 'index'])->name('user.menu.index');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('user.menu.create');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('user.menu.store');
    Route::get('/menu/{idmenu}/edit', [MenuController::class, 'edit'])->name('user.menu.edit');
    Route::put('/menu/{idmenu}', [MenuController::class, 'update'])->name('user.menu.update');
    Route::delete('/menu/{idmenu}', [MenuController::class, 'destroy'])->name('user.menu.destroy');
    Route::get('/pesanan-masuk', [PesananMasukController::class, 'index'])->name('user.pesanan.index');
    Route::get('/user/pesanan/scanner', [PesananMasukController::class, 'scanner'])->name('user.pesanan.scanner');
    Route::get('/user/pesanan/periksa/{idpesanan}', [PesananMasukController::class, 'periksa'])->name('user.pesanan.periksa');
});

// Auth Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
