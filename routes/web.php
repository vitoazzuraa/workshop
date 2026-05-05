<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\JsDemoController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/login/google', [GoogleController::class, 'redirect'])->name('login.google');
Route::get('/login/google/callback', [GoogleController::class, 'callback']);

Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp', [OtpController::class, 'verify'])->name('otp.verify');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ── Landing page (public) ──────────────────────────────────────────────
Route::get('/', [KantinController::class, 'index'])->name('kantin.index');

// ── Kantin (public — guest tidak perlu login) ──────────────────────────
Route::prefix('kantin')->name('kantin.')->group(function () {
    Route::get('/menu/{idvendor}', [KantinController::class, 'menu'])->name('menu');
    Route::post('/pesan', [KantinController::class, 'pesan'])->name('pesan');
    Route::get('/status/{id}', [KantinController::class, 'status'])->name('status');
    Route::get('/cek-status/{id}', [KantinController::class, 'cekStatus'])->name('cek.status');
    Route::get('/cek-pesanan', [KantinController::class, 'cekPesanan'])->name('cek.pesanan');
    Route::post('/midtrans/callback', [KantinController::class, 'midtransCallback'])->name('midtrans.callback');
});
// Alias — sesuaikan dengan URL di dashboard Midtrans
Route::post('/midtrans-callback', [KantinController::class, 'midtransCallback']);

// ── Protected (wajib login) ────────────────────────────────────────────
Route::middleware('check.login')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Koleksi Buku (Modul 1)
    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('buku', BukuController::class)->only(['index', 'store', 'update', 'destroy']);

    // Barang & Tag Harga
    Route::resource('barang', BarangController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('barang/cetak', [BarangController::class, 'cetakForm'])->name('barang.cetak.form');
    Route::post('barang/cetak', [BarangController::class, 'cetakPdf'])->name('barang.cetak.pdf');

    // Wilayah (JSON API untuk cascading select)
    Route::get('wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('wilayah/kota/{provinsiId}', [WilayahController::class, 'kota'])->name('wilayah.kota');
    Route::get('wilayah/kecamatan/{kotaId}', [WilayahController::class, 'kecamatan'])->name('wilayah.kecamatan');
    Route::get('wilayah/kelurahan/{kecamatanId}', [WilayahController::class, 'kelurahan'])->name('wilayah.kelurahan');

    // Demo JS / jQuery (Modul JS)
    Route::get('js-demo/table', [JsDemoController::class, 'table'])->name('js.table');
    Route::get('js-demo/datatable', [JsDemoController::class, 'datatable'])->name('js.datatable');
    Route::get('js-demo/select', [JsDemoController::class, 'select'])->name('js.select');

    // POS Kasir (Ajax & Axios)
    Route::get('pos/ajax', [PosController::class, 'indexAjax'])->name('pos.ajax');
    Route::get('pos/axios', [PosController::class, 'indexAxios'])->name('pos.axios');
    Route::get('pos/cari', [PosController::class, 'cari'])->name('pos.cari');
    Route::post('pos/bayar', [PosController::class, 'bayar'])->name('pos.bayar');
    Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');

    // PDF
    Route::get('pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

    // Vendor (hanya admin dan vendor)
    Route::prefix('vendor')->name('vendor.')->middleware('check.vendor')->group(function () {
        Route::resource('menu', VendorController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('pesanan', [VendorController::class, 'pesanan'])->name('pesanan');
        Route::get('scan', [VendorController::class, 'scan'])->name('scan');
        Route::get('scan/hasil/{id}', [VendorController::class, 'scanHasil'])->name('scan.hasil');
    });

    // Customer (foto kamera)
    Route::get('customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('customer/tambah1', [CustomerController::class, 'tambah1'])->name('customer.tambah1');
    Route::post('customer/tambah1', [CustomerController::class, 'simpan1'])->name('customer.simpan1');
    Route::get('customer/tambah2', [CustomerController::class, 'tambah2'])->name('customer.tambah2');
    Route::post('customer/tambah2', [CustomerController::class, 'simpan2'])->name('customer.simpan2');

    // Guest kantin
    Route::get('guest', [GuestController::class, 'index'])->name('guest.index');

    // Barcode & QR Scanner
    Route::get('barcode/scan', [BarcodeController::class, 'scan'])->name('barcode.scan');
    Route::get('barcode/hasil/{kode}', [BarcodeController::class, 'hasil'])->name('barcode.hasil');
});
