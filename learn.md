# PANDUAN BELAJAR LARAVEL — DARI NOL, SETIAP BARIS DIJELASKAN
## Khusus untuk pemula yang ingin mengerti "kenapa ditulis begitu"

> **Cara baca dokumen ini:** Baca dari atas ke bawah. Setiap konsep dibangun di atas konsep sebelumnya. Jangan skip bagian awal.

---

# BAGIAN 1: CARA WEB BEKERJA (WAJIB PAHAM DULU)

Sebelum belajar Laravel, kamu harus paham cara web bekerja. Ini fondasinya.

## Apa yang terjadi saat kamu buka website?

```
Browser kamu          Internet          Server (Laragon di PC kamu)
─────────────         ────────          ──────────────────────────
Ketik URL         →   Kirim Request →   PHP + Laravel memproses
framework.test        "Hei server,      lalu kirim balik HTML
                       kasih saya
                       halaman ini"  ←  Kirim Response (HTML)
                                        "Ini halamannya"
```

**Request** = permintaan dari browser ke server  
**Response** = jawaban dari server ke browser

Setiap kali kamu:
- Ketik URL → browser kirim **GET request**
- Submit form → browser kirim **POST request**
- Klik tombol hapus → bisa kirim **DELETE request**

---

## Apa itu MVC? (Model - View - Controller)

Laravel menggunakan pola **MVC**. Bayangkan seperti restoran:

```
CONTROLLER = Pelayan
  → Terima pesanan dari tamu (Request dari browser)
  → Ambil data dari dapur (Model)
  → Sajikan ke meja (kirim ke View)

MODEL = Dapur + Bahan makanan
  → Tempat semua data tersimpan (database)
  → Controller minta data → Model ambil dari database

VIEW = Piring + Tampilan makanan
  → HTML yang dilihat user
  → Data dari Controller dimasukkan ke sini
```

**Alur MVC:**
```
Browser → Route → Controller → Model → Database
                      ↓            ↑
                     View    ← Data kembali
                      ↓
                   Browser (dapat HTML)
```

---

# BAGIAN 2: ROUTE — PENJAGA PINTU MASUK

File: `routes/web.php`

## Apa itu Route?

Route adalah **peta** yang menentukan: "Kalau ada request ke URL ini, jalankan fungsi ini."

```php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

Baca seperti ini:
- `Route::` → Laravel, daftarkan sebuah route
- `get` → hanya untuk request jenis GET (membuka halaman biasa)
- `'/dashboard'` → kalau URL-nya adalah /dashboard
- `[DashboardController::class, 'index']` → jalankan method `index` di class `DashboardController`
- `->name('dashboard')` → beri nama "dashboard" agar bisa dipanggil di kode lain

### Jenis-jenis Route

```php
Route::get('/url', [...]);    // Buka halaman (browser ketik URL)
Route::post('/url', [...]);   // Submit form atau kirim data
Route::put('/url/{id}', [...]);  // Update data
Route::delete('/url/{id}', [...]);  // Hapus data
```

**Kenapa ada 4 jenis?**  
Karena HTTP protocol punya 4 "kata kerja" utama. Browser form HTML hanya bisa GET dan POST, makanya Laravel memakai field tersembunyi `@method('PUT')` atau `@method('DELETE')` untuk tipe lainnya.

### Parameter di URL

```php
Route::get('/kantin/status/{id}', [KantinController::class, 'status']);
//                           ↑
//                   Bagian ini berubah-ubah
//                   /kantin/status/5   → id = 5
//                   /kantin/status/12  → id = 12
```

Parameter `{id}` akan diteruskan ke method controller sebagai argumen:
```php
public function status($id)  // $id berisi 5, atau 12, sesuai URL
{
    ...
}
```

### Group Route dengan Middleware

```php
Route::middleware('check.login')->group(function () {
    // Semua route di sini hanya bisa diakses kalau sudah login
    Route::get('/dashboard', [...]);
    Route::get('/barang', [...]);
});
```

**Kenapa digroup?** Daripada tulis `->middleware('check.login')` di setiap route satu per satu, lebih efisien di-group. Semua route di dalam group otomatis terkena middleware yang sama.

### `->name()` — Nama Route

```php
Route::get('/dashboard', [...])->name('dashboard');
```

Dengan memberi nama, kamu bisa panggil URL ini di kode lain **tanpa hardcode path**:

```php
// Di Controller:
return redirect()->route('dashboard');
// Sama dengan: return redirect('/dashboard');

// Di Blade:
<a href="{{ route('dashboard') }}">Dashboard</a>
// Sama dengan: <a href="/dashboard">Dashboard</a>
```

**Kenapa pakai nama, bukan hardcode URL langsung?**  
Kalau URL berubah dari `/dashboard` ke `/beranda`, kamu hanya ubah di satu tempat (definisi route), bukan di ratusan tempat di kode.

---

# BAGIAN 3: CONTROLLER — OTAK APLIKASI

File: `app/Http/Controllers/`

## Apa itu Controller?

Controller adalah **kelas PHP** yang berisi fungsi-fungsi untuk memproses request.

### Anatomi Controller

```php
<?php                              // ← Wajib ada di setiap file PHP

namespace App\Http\Controllers;    // ← "Nama alamat" file ini di dalam proyek
                                   //   Seperti nama folder: App > Http > Controllers

use App\Models\Barang;             // ← Import kelas Barang agar bisa dipakai di sini
use Illuminate\Http\Request;       // ← Import kelas Request (berisi data dari browser)

class BarangController extends Controller   // ← Kelas baru bernama BarangController
//                        ↑
//              Mewarisi (extends) dari Controller induk
//              Artinya dapat semua fungsi dasar dari Controller
{
    public function index()          // ← Fungsi bernama index
    //     ↑
    //   public = bisa dipanggil dari luar kelas ini (dari route)
    {
        $data = Barang::orderBy('timestamp', 'desc')->get();
        //  ↑           ↑                    ↑       ↑
        //  Variabel    Model Barang          Urut    Ambil semua
        //              (tabel barang)        desc=terbaru dulu

        return view('barang.index', compact('data'));
        //     ↑    ↑               ↑
        //     Kirim tampilan       compact() = bungkus $data
        //          barang/index    ke dalam array ['data' => $data]
        //          .blade.php      agar bisa dipakai di view
    }
}
```

### Kenapa `compact('data')`?

```php
// Cara 1 — panjang:
return view('barang.index', ['data' => $data, 'judul' => $judul]);

// Cara 2 — pakai compact (lebih ringkas):
return view('barang.index', compact('data', 'judul'));
// compact() otomatis buat array dari nama variabel yang kamu tulis
// compact('data') = ['data' => $data]
// compact('data', 'judul') = ['data' => $data, 'judul' => $judul]
```

### Method `store` — Simpan Data dari Form

```php
public function store(Request $request)
//                    ↑
//              $request berisi SEMUA data yang dikirim browser
//              form field, file upload, dll.
{
    // LANGKAH 1: Validasi input
    $request->validate([
        'nama'  => 'required|string|max:50',
        //  ↑        ↑        ↑      ↑
        // nama     wajib   harus   maksimal
        // field    diisi   string  50 karakter
        'harga' => 'required|integer|min:0',
        // harga: wajib, harus angka bulat, minimal 0
    ]);
    // Kalau validasi gagal → Laravel otomatis redirect balik ke form
    // dengan pesan error. Tidak perlu tulis kode tambahan.

    // LANGKAH 2: Simpan ke database
    \DB::statement('INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)', [
    //  ↑                              ↑                         ↑
    //  Jalankan SQL mentah            Tanda tanya = placeholder  Nilai yang
    //                                 (keamanan: cegah SQL       menggantikan
    //                                 injection)                 tanda tanya
        '00000000', $request->nama, $request->harga,
    //      ↑              ↑               ↑
    //  Placeholder    Ambil nilai      Ambil nilai
    //  MySQL trigger  dari form field  dari form field
    //  akan ganti ini 'nama'           'harga'
    ]);

    // LANGKAH 3: Redirect balik dengan pesan sukses
    return back()->with('success', 'Barang berhasil ditambahkan.');
    //     ↑               ↑
    //  Kembali ke     Simpan pesan ke session sementara
    //  halaman        (tampil sekali, lalu hilang)
    //  sebelumnya
}
```

### `$request->nama` vs `$request->input('nama')`

```php
$request->nama           // cara singkat
$request->input('nama')  // cara eksplisit, sama hasilnya
$request->input('nama', 'default')  // dengan nilai default jika tidak ada
```

Keduanya mengambil nilai dari form field bernama `nama`:
```html
<input type="text" name="nama" value="Budi">
<!-- $request->nama akan berisi "Budi" -->
```

---

# BAGIAN 4: MODEL — JEMBATAN KE DATABASE

File: `app/Models/`

## Apa itu Model?

Model adalah **kelas PHP yang mewakili satu tabel database**. Dengan Model, kamu tidak perlu tulis SQL secara manual — cukup panggil method PHP.

### Tanpa Model vs Dengan Model

```php
// TANPA Model (SQL manual):
$barangs = DB::select('SELECT * FROM barang ORDER BY timestamp DESC');

// DENGAN Model (Eloquent ORM):
$barangs = Barang::orderBy('timestamp', 'desc')->get();
// Kedua baris di atas menghasilkan hal yang sama
// Tapi yang Model lebih mudah dibaca dan aman
```

### Anatomi Model Dasar

```php
<?php

namespace App\Models;              // Alamat file ini

use Illuminate\Database\Eloquent\Model;  // Import class Model Laravel

class Barang extends Model         // Kelas Barang mewarisi semua kemampuan Model
{
    protected $table = 'barang';
    // ↑ Beritahu Laravel: tabel database untuk model ini bernama 'barang'
    // Kalau tidak ditulis, Laravel tebak otomatis:
    //   class Barang → tabel 'barangs' (ditambah 's')
    //   class Kategori → tabel 'kategoris' (salah!)
    // Makanya untuk nama tabel yang tidak mengikuti konvensi Inggris,
    // kita tulis manual

    protected $primaryKey = 'id_barang';
    // ↑ Kolom mana yang jadi Primary Key?
    // Default: 'id'. Tapi tabel barang pakai 'id_barang', jadi harus ditulis

    public $incrementing = false;
    // ↑ Apakah PK auto-increment (angka naik otomatis)?
    // Default: true. Tapi id_barang bukan angka auto-increment
    // (dia di-generate trigger MySQL), jadi set false

    protected $keyType = 'string';
    // ↑ Tipe data PK. Default: 'int'
    // id_barang adalah varchar/string ('25052801'), bukan integer

    public $timestamps = false;
    // ↑ Apakah tabel punya kolom created_at dan updated_at?
    // Default: true (Laravel otomatis isi/update kolom ini)
    // Tabel barang tidak punya dua kolom itu, jadi set false

    protected $fillable = ['nama', 'harga'];
    // ↑ Kolom mana saja yang boleh diisi via create() atau update()?
    // KEAMANAN: tanpa $fillable, orang bisa inject kolom berbahaya
    // Contoh: kalau user kirim {'nama': 'x', 'is_admin': true}
    //         tanpa $fillable, is_admin ikut tersimpan!
    // Dengan $fillable, hanya 'nama' dan 'harga' yang diterima
}
```

### Query Builder — Cara Ambil Data

```php
// Ambil SEMUA data
Barang::all();
// SQL: SELECT * FROM barang

// Ambil dengan kondisi
Barang::where('harga', '>', 10000)->get();
// SQL: SELECT * FROM barang WHERE harga > 10000

// Ambil satu data berdasarkan PK
Barang::find('25052801');
// SQL: SELECT * FROM barang WHERE id_barang = '25052801' LIMIT 1
// Return: satu object Barang, atau NULL kalau tidak ketemu

// Ambil satu atau error 404
Barang::findOrFail('25052801');
// Sama seperti find(), tapi kalau tidak ketemu → error 404 otomatis
// Lebih aman untuk halaman detail

// Urutan
Barang::orderBy('timestamp', 'desc')->get();
// SQL: SELECT * FROM barang ORDER BY timestamp DESC

// Kombinasi
Barang::where('harga', '>', 10000)->orderBy('nama')->get();
// SQL: SELECT * FROM barang WHERE harga > 10000 ORDER BY nama ASC
```

### Buat, Update, Hapus Data

```php
// CREATE
Barang::create(['nama' => 'Nasi Goreng', 'harga' => 15000]);
// SQL: INSERT INTO barang (nama, harga) VALUES ('Nasi Goreng', 15000)
// SYARAT: 'nama' dan 'harga' harus ada di $fillable

// UPDATE
Barang::find('25052801')->update(['harga' => 16000]);
// SQL: UPDATE barang SET harga = 16000 WHERE id_barang = '25052801'

// DELETE
Barang::find('25052801')->delete();
// SQL: DELETE FROM barang WHERE id_barang = '25052801'

// findOrFail + update (lebih aman):
Barang::findOrFail($id)->update($request->only('nama', 'harga'));
// $request->only('nama', 'harga') = ambil hanya field nama dan harga dari request
// Tidak mau terima field lain yang mungkin dikirim user
```

---

# BAGIAN 5: RELASI ANTAR TABEL

## Kenapa perlu relasi?

Bayangkan tabel `menu` dan `vendor`:

```
Tabel vendor:                    Tabel menu:
idvendor | nama_vendor           idmenu | nama_menu     | idvendor
---------|------------           -------|---------------|----------
1        | Warung Bu Sari        1      | Nasi Goreng   | 1
2        | Kantin Pak Joko       2      | Soto Ayam     | 1
                                 3      | Roti Bakar    | 2
```

Tabel menu punya kolom `idvendor` yang merujuk ke tabel vendor. Ini **Foreign Key**.

Kalau kamu mau tahu "menu ini milik vendor siapa?", kamu perlu **JOIN** kedua tabel. Relasi di Laravel mempermudah ini.

## hasMany — "Satu vendor punya banyak menu"

```php
// Di Vendor.php:
public function menu()
{
    return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    //           ↑         ↑            ↑            ↑
    //         "punya     Kelas         Kolom FK     Kolom PK
    //          banyak"   yang          di tabel     di tabel
    //                    dicari        anak (menu)  induk (vendor)
}
```

Penggunaan:
```php
$vendor = Vendor::find(1);
$vendor->menu;
// Laravel otomatis jalankan:
// SELECT * FROM menu WHERE idvendor = 1
// Hasilnya: collection of Menu objects
```

## belongsTo — "Menu ini milik satu vendor"

```php
// Di Menu.php:
public function vendor()
{
    return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    //            ↑          ↑              ↑            ↑
    //          "milik       Kelas          Kolom FK     Kolom PK
    //           satu"       yang           di tabel     di tabel
    //                       dicari         ini (menu)   sana (vendor)
}
```

Penggunaan:
```php
$menu = Menu::find(1);
$menu->vendor;
// Laravel otomatis jalankan:
// SELECT * FROM vendor WHERE idvendor = 1
// Hasilnya: satu object Vendor
```

## with() — Eager Loading (Penting untuk Performa)

**Masalah N+1 Query:**
```php
// CARA BURUK:
$menus = Menu::all();          // 1 query
foreach ($menus as $menu) {
    echo $menu->vendor->nama;  // 1 query PER item! = N query tambahan
}
// Total: 1 + N query (kalau ada 100 menu = 101 query!)
```

```php
// CARA BAGUS (eager loading):
$menus = Menu::with('vendor')->get();  // 2 query saja
// Query 1: SELECT * FROM menu
// Query 2: SELECT * FROM vendor WHERE idvendor IN (1, 2, 3, ...)
foreach ($menus as $menu) {
    echo $menu->vendor->nama;  // Tidak ada query tambahan!
}
// Total: selalu 2 query, berapa pun jumlah menu
```

**Nested eager loading:**
```php
Pesanan::with('detail.menu')->get();
// Artinya: load detail, DAN untuk setiap detail, load menu-nya
// 3 query: pesanan + detail + menu
```

---

# BAGIAN 6: VIEW & BLADE — TAMPILAN

File: `resources/views/`

## Apa itu Blade?

Blade adalah **template engine** Laravel. File `.blade.php` adalah file HTML biasa TAPI bisa menyisipkan kode PHP dengan sintaks yang lebih bersih.

### PHP biasa vs Blade

```php
// PHP biasa (verbose):
<?php foreach ($data as $item): ?>
    <tr><td><?php echo $item->nama; ?></td></tr>
<?php endforeach; ?>

// Blade (lebih bersih):
@foreach($data as $item)
    <tr><td>{{ $item->nama }}</td></tr>
@endforeach
```

### Sintaks Blade yang Wajib Diketahui

```blade
{{-- Komentar (tidak muncul di HTML final) --}}

{{ $variabel }}
{{-- Tampilkan variabel, OTOMATIS escape HTML (aman dari XSS) --}}
{{-- Contoh: $nama = "<script>alert('hack')</script>" --}}
{{-- Akan ditampilkan sebagai teks biasa, bukan dieksekusi --}}

{!! $variabel !!}
{{-- Tampilkan variabel TANPA escape (HTML akan dirender) --}}
{{-- Pakai ini hanya untuk konten yang kamu kontrol sendiri --}}
{{-- Contoh: {!! QrCode::generate('123') !!} → SVG --}}

@if(kondisi)
    ...
@elseif(kondisi_lain)
    ...
@else
    ...
@endif

@foreach($data as $item)
    ...
@endforeach

@empty($data)
    <p>Data kosong</p>
@endempty

{{ $loop->index }}   {{-- Index saat ini (mulai dari 0) --}}
{{ $loop->iteration }}  {{-- Iterasi saat ini (mulai dari 1) --}}
{{ $loop->first }}   {{-- true kalau ini iterasi pertama --}}
{{ $loop->last }}    {{-- true kalau ini iterasi terakhir --}}
```

### Layout dan Sections

**Layout (app.blade.php)** adalah "kerangka" yang dipakai semua halaman:

```blade
{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    @include('layouts.partials.head')  {{-- Load file head.blade.php --}}
    @yield('style-page')
    {{-- ↑ "Sisipkan di sini apapun yang ada di @section('style-page') --}}
    {{-- halaman yang pakai layout ini" --}}
</head>
<body>
    @include('layouts.partials.sidebar')
    <main>
        @yield('content')   {{-- Konten halaman diletakkan di sini --}}
    </main>
    @include('layouts.partials.footer')
    @yield('js-page')       {{-- JS halaman diletakkan di sini --}}
</body>
</html>
```

**Halaman biasa (misal barang/index.blade.php):**

```blade
@extends('layouts.app')
{{-- ↑ "Halaman ini menggunakan layout dari layouts/app.blade.php" --}}

@section('title', 'Data Barang')
{{-- ↑ Isi variabel title --}}

@section('style-page')
<style>
    /* CSS khusus halaman ini */
</style>
@endsection
{{-- ↑ Semua antara @section dan @endsection akan dimasukkan --}}
{{-- ke dalam @yield('style-page') di layout --}}

@section('content')
<div class="row">
    ...konten HTML...
</div>
@endsection

@section('js-page')
<script>
    // JS khusus halaman ini
</script>
@endsection
```

### Cara Data dari Controller Sampai ke View

```php
// Controller:
public function index()
{
    $barangs = Barang::all();    // ambil data dari database
    $jumlah  = $barangs->count(); // hitung jumlah
    
    return view('barang.index', compact('barangs', 'jumlah'));
    // compact('barangs', 'jumlah') setara dengan:
    // ['barangs' => $barangs, 'jumlah' => $jumlah]
    // Artinya: kirim variabel $barangs dan $jumlah ke view
}
```

```blade
{{-- View barang/index.blade.php: --}}
<p>Total barang: {{ $jumlah }}</p>
{{-- $jumlah tersedia karena controller mengirimnya --}}

@foreach($barangs as $b)
    <tr>
        <td>{{ $b->id_barang }}</td>
        <td>{{ $b->nama }}</td>
        <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
        {{--        ↑
            number_format(angka, desimal, pemisah_desimal, pemisah_ribuan)
            number_format(15000, 0, ',', '.') → "15.000"
        --}}
    </tr>
@endforeach
```

### CSRF Token — Keamanan Form

```blade
<form method="POST" action="/barang">
    @csrf
    {{-- ↑ Ini WAJIB ada di setiap form POST --}}
    {{-- Blade generate: <input type="hidden" name="_token" value="abc123..."> --}}
    {{-- Server verifikasi token ini untuk memastikan form dikirim dari website ini --}}
    {{-- Tanpa ini, form POST akan ditolak dengan error 419 --}}
    ...
</form>
```

### Method Spoofing untuk PUT dan DELETE

HTML form hanya bisa GET dan POST. Untuk PUT (update) dan DELETE (hapus):

```blade
<form method="POST" action="/barang/{{ $b->id_barang }}">
    @csrf
    @method('DELETE')
    {{-- ↑ Memberitahu Laravel: "Perlakukan ini sebagai DELETE request" --}}
    {{-- Laravel generate: <input type="hidden" name="_method" value="DELETE"> --}}
    <button type="submit">Hapus</button>
</form>
```

---

# BAGIAN 7: SESSION — MEMORI SERVER

## Apa itu Session?

HTTP adalah **stateless** — setiap request adalah request baru, server tidak ingat request sebelumnya. Session adalah cara menyimpan data "antar request".

Bayangkan session seperti **loker**: kamu dapat kunci (session ID, disimpan di cookie browser), dan setiap kamu datang ke server, server buka loker kamu dan lihat isinya.

```
Request 1 (Login):
Browser → Server: "Saya Budi, password 123"
Server → verifikasi → simpan ke session: ['user' => ['name' => 'Budi']]
Server → Browser: "OK, ini kunci loker kamu" (session ID di cookie)

Request 2 (Buka dashboard):
Browser → Server: "Hei, ini kunci loker saya" (kirim cookie)
Server → buka loker → baca session: ['user' => ['name' => 'Budi']]
Server → "Oh, kamu Budi yang sudah login" → tampilkan dashboard
```

### Cara Pakai Session di Laravel

```php
// Simpan ke session
session(['user' => ['name' => 'Budi', 'role' => 'admin']]);
// atau:
session()->put('user', ['name' => 'Budi', 'role' => 'admin']);

// Baca dari session
session('user');            // ambil seluruh array user
session('user.name');       // ambil nilai 'name' dari array 'user' → 'Budi'
session('user.role');       // → 'admin'
session()->get('user');     // cara eksplisit, sama hasilnya

// Cek apakah key ada
session()->has('user');     // true/false

// Hapus dari session
session()->forget('user');  // hapus key 'user'
session()->flush();         // hapus SEMUA data session

// Flash session (ada hanya untuk 1 request, lalu hilang otomatis)
session()->flash('success', 'Data berhasil disimpan');
// Di view: {{ session('success') }} → tampil sekali, lalu hilang
```

### Session di View

```blade
{{-- Tampilkan nama user yang login --}}
{{ session('user.name') }}

{{-- Kondisi berdasarkan role --}}
@if(session('user.role') === 'admin')
    {{-- Menu admin --}}
@endif

{{-- Tampilkan pesan flash --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

---

# BAGIAN 8: MIDDLEWARE — PENJAGA PINTU

File: `app/Http/Middleware/`

## Cara Kerja Middleware

Middleware berjalan **sebelum** request sampai ke controller. Seperti satpam:

```
Browser → [Middleware 1] → [Middleware 2] → Controller
              ↓ (kalau tidak lolos)
           Redirect atau Error
```

### CheckLogin.php — Baca Baris per Baris

```php
<?php

namespace App\Http\Middleware;

use Closure;                              // Import Closure (fungsi callback)
use Illuminate\Http\Request;             // Import Request
use Symfony\Component\HttpFoundation\Response;  // Import Response

class CheckLogin
{
    public function handle(Request $request, Closure $next): Response
    //              ↑       ↑                 ↑
    //           Nama       Data dari         $next = "lanjut ke handler berikutnya"
    //           fungsi     browser           Kalau kita panggil $next($request),
    //                                        request dilanjutkan ke controller
    {
        if (!session()->has('user')) {
        //   ↑  Tanda seru = NOT
        //   Artinya: JIKA session 'user' TIDAK ADA (belum login)
        
            return redirect()->route('login')
            //     ↑                  ↑
            //     Kirim browser      Ke halaman bernama 'login'
            //     ke URL lain
            
                ->with('error', 'Silakan login terlebih dahulu.');
                //  ↑
                // Tambahkan pesan flash ke session
                // Akan muncul di halaman login sebagai notifikasi
        }
        
        return $next($request);
        //     ↑
        //     Kalau sudah login → lanjutkan ke controller yang dituju
    }
}
```

---

# BAGIAN 9: AUTH — CARA LOGIN DAN OTP BEKERJA

## LoginController::login() — Baris per Baris

```php
public function login(Request $request)
{
    // 1. Validasi: email harus format email, password wajib diisi
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    // Kalau gagal validasi → otomatis kembali ke form dengan pesan error
    // Tidak perlu tulis kode penanganan error manual

    // 2. Cari user di database
    $user = User::where('email', $request->email)->first();
    //     ↑              ↑               ↑         ↑
    //   Simpan          Cari di         di kolom   Ambil 1 hasil
    //   hasilnya        tabel users     email      (bukan ->get() yang ambil semua)

    // 3. Verifikasi: user ada DAN password cocok?
    if (!$user || !Hash::check($request->password, $user->password)) {
    //   ↑          ↑
    //   User tidak  Password tidak cocok
    //   ditemukan   Hash::check() bandingkan password plain dengan hash di DB
    
        return back()
        //     ↑ Kembali ke halaman sebelumnya (form login)
        
            ->withErrors(['email' => 'Email atau password salah.'])
            //  ↑ Simpan error dengan key 'email'
            //    Di view: {{ $errors->first('email') }}
            
            ->withInput();
            //  ↑ Isi ulang form dengan input sebelumnya (email tidak hilang)
    }

    // 4. Kalau cocok → kirim OTP
    return $this->kirimOtp($user);
    //           ↑
    //     $this = object ini sendiri
    //     Panggil method kirimOtp di kelas yang sama
}
```

## kirimOtp() — Generate dan Kirim OTP

```php
public function kirimOtp(User $user)
//                        ↑
//                Type hint: parameter ini harus berupa object User
{
    $otp = strtoupper(Str::random(6));
    //     ↑          ↑   ↑
    //   Jadikan     Huruf besar  Laravel helper: buat string acak 6 karakter
    //   uppercase                Hasil: 'a3kx9p' → setelah strtoupper: 'A3KX9P'

    $user->update(['otp' => $otp]);
    // Simpan OTP ke database di kolom 'otp' user ini

    session(['otp_user_id' => $user->id]);
    // Simpan ID user ke session SEMENTARA
    // Kenapa sementara? Karena kita belum set session 'user' (belum verified)
    // Session ini dipakai oleh OtpController untuk tahu user mana yang sedang OTP

    try {
    //  ↑ Coba jalankan kode di dalam blok ini
        Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        //  ↑        ↑                ↑
        //  Kirim    Ke email         Buat email dari class OtpMail
        //  email    user ini         dengan data otp dan nama
    } catch (\Exception $e) {
    //  ↑ Kalau ada error (misal mail server mati)
        // Di development: mail mungkin hanya di-log ke file, tidak benar-benar terkirim
        // OTP tetap tersimpan di DB, jadi masih bisa ditest manual
    }

    return redirect()->route('otp.show');
    // Redirect ke halaman form OTP
}
```

## OtpController::verify() — Verifikasi OTP

```php
public function verify(Request $request)
{
    $request->validate(['otp' => 'required|string|size:6']);
    //                                              ↑
    //                                    Harus tepat 6 karakter

    $userId = session('otp_user_id');
    // Ambil ID user dari session sementara yang dibuat di kirimOtp()

    $user = User::find($userId);
    // Cari user berdasarkan ID

    if (!$user || strtoupper($request->otp) !== strtoupper($user->otp)) {
    //              ↑                              ↑
    //           Input OTP dari form           OTP yang tersimpan di DB
    //           diubah uppercase              diubah uppercase
    //           Perbandingan case-insensitive: 'a3kx9p' = 'A3KX9P'
        return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
    }

    $user->update(['otp' => null]);
    // Hapus OTP dari database → tidak bisa dipakai lagi

    session()->forget('otp_user_id');
    // Hapus session sementara

    $user->load('role');
    // Load relasi role (eager load manual)
    // Kenapa? Karena kita butuh $user->role->nama_role di bawah

    // Set session user yang SESUNGGUHNYA (user resmi sudah login)
    session(['user' => [
        'id'        => $user->id,
        'name'      => $user->name,
        'email'     => $user->email,
        'role'      => $user->role->nama_role,   // 'admin' atau 'vendor'
        'vendor_id' => $user->vendor_id,          // null atau integer
    ]]);

    return redirect()->route('dashboard');
}
```

---

# BAGIAN 10: AJAX DAN AXIOS — CARA FRONTEND BICARA KE BACKEND

## Perbedaan Form Biasa vs AJAX

```
FORM BIASA:
Browser → Submit form → Server proses → Browser RELOAD halaman baru

AJAX/Axios:
Browser → Kirim request di background → Server proses → Browser TIDAK reload
          JavaScript yang request                        JavaScript update
                                                         bagian tertentu di halaman
```

## CSRF Token untuk AJAX

Semua request POST/PUT/DELETE butuh token CSRF untuk keamanan.

```html
<!-- Di head.blade.php atau layout: -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

```javascript
// Setup global (di footer.blade.php agar berlaku untuk semua halaman):
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').content;
//                          ↑
//              Ambil nilai dari tag meta yang kita taruh di head
```

## Axios — Cara Kirim Request dari JavaScript

### GET Request (mengambil data)

```javascript
// Cara 1: await (lebih mudah dibaca)
async function cariBarang(kode) {
    try {
        const res = await axios.get('/pos/cari', {
        //           ↑            ↑   ↑           ↑
        //   tunggu sampai       GET  URL          parameter query
        //   selesai (async)                       (/pos/cari?kode=25052801)
            params: { kode: kode }
        });
        
        // res.data = isi JSON yang dikirim controller
        if (res.data.status === 'success') {
            const barang = res.data.data;  // ambil data barang
            console.log(barang.nama);      // "Nasi Goreng"
        }
    } catch (e) {
        // Error: server error, network mati, dll
        console.error(e);
    }
}

// Cara 2: .then() .catch() (Promise chain)
axios.get('/pos/cari', { params: { kode: kode } })
    .then(function(res) {
        // sukses
        const barang = res.data.data;
    })
    .catch(function(e) {
        // error
    });
```

### POST Request (mengirim data)

```javascript
async function pesan() {
    const nama  = document.getElementById('namaPemesan').value;
    const items = [
        { idmenu: 1, jumlah: 2, catatan: '' },
        { idmenu: 3, jumlah: 1, catatan: 'pedas' },
    ];
    
    const res = await axios.post('/kantin/pesan', {
    //                      ↑    ↑               ↑
    //                    POST  URL             Body (dikirim sebagai JSON)
        nama:  nama,
        items: items
    });
    // Controller terima: $request->nama, $request->input('items')
    
    if (res.data.status === 'success') {
        const snapToken = res.data.data.snap_token;
        window.snap.pay(snapToken, {...});
    }
}
```

### Apa itu async/await?

```javascript
// Tanpa async/await (callback hell):
axios.get('/api/data').then(function(res1) {
    axios.get('/api/more/' + res1.data.id).then(function(res2) {
        axios.get('/api/detail/' + res2.data.id).then(function(res3) {
            // Sudah susah dibaca
        });
    });
});

// Dengan async/await (lebih bersih):
async function loadData() {
    const res1 = await axios.get('/api/data');
    const res2 = await axios.get('/api/more/' + res1.data.id);
    const res3 = await axios.get('/api/detail/' + res2.data.id);
    // Mudah dibaca, berjalan berurutan
}
```

`async` = "fungsi ini akan melakukan operasi async"  
`await` = "tunggu sampai promise ini selesai sebelum lanjut"

---

# BAGIAN 11: PAYMENT GATEWAY — MIDTRANS DARI A SAMPAI Z

## Konsep Dasar

Midtrans adalah **perantara** antara aplikasimu dan bank/dompet digital. Kamu tidak langsung bicara ke bank — Midtrans yang urus.

```
Aplikasimu ←→ Midtrans ←→ Bank BCA / QRIS / GoPay / dll
```

## Flow Lengkap Step by Step

### Step 1: Frontend kirim pesanan ke backend

```javascript
// kantin/order.blade.php
const res = await axios.post('/kantin/pesan', {
    nama:  'Budi',
    no_hp: '08123456789',
    items: [
        { idmenu: 1, jumlah: 2, catatan: '' }
    ]
});
```

### Step 2: Controller buat pesanan di database

```php
// KantinController::pesan()

// a) Hitung total dari DATABASE (bukan percaya hitungan frontend)
//    Kenapa? Karena user bisa manipulasi harga di browser!
$menuIds = collect($items)->pluck('idmenu')->toArray();
//         ↑                ↑
//         Bungkus array    Ambil hanya kolom 'idmenu' dari setiap item
//         biasa jadi       Hasil: [1, 3, 5]
//         Collection

$menus = Menu::whereIn('idmenu', $menuIds)->get()->keyBy('idmenu');
//            ↑                  ↑          ↑     ↑
//           WHERE idmenu        Array IDs  Ambil keyBy = ubah jadi
//           IN (1, 3, 5)                   semua  array dengan key = idmenu
//                                                 Hasil: ['1' => Menu, '3' => Menu]

$total = 0;
foreach ($items as $item) {
    $menu = $menus->get($item['idmenu']);  // Ambil menu dari Collection
    $total += $menu->harga * $item['jumlah'];  // Hitung dari harga di DB
}
```

```php
// b) Generate order ID unik untuk Midtrans
$orderId = 'KANTIN-' . time() . '-' . rand(100, 999);
//                     ↑                ↑
//                  Unix timestamp      Angka random 3 digit
//                  (detik sejak 1970)  Hasil: 'KANTIN-1748433600-542'
```

```php
// c) Request Snap Token dari Midtrans API
\Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
// ↑ Ambil server key dari file .env (jangan hardcode di kode!)

\Midtrans\Config::$isProduction = false;
// false = gunakan server sandbox (uang tidak beneran)
// true  = server produksi (uang beneran)

$snapToken = \Midtrans\Snap::getSnapToken($params);
// ↑ Kirim request ke API Midtrans, dapat token popup sebagai balasan
// Token ini akan dipakai frontend untuk buka popup pembayaran
```

```php
// d) Return token ke frontend
return response()->json([
    'status' => 'success',
    'data'   => [
        'idpesanan'  => $pesanan->idpesanan,
        'snap_token' => $snapToken,
    ]
]);
```

### Step 3: Frontend buka popup Midtrans

```javascript
const { snap_token, idpesanan } = res.data.data;

window.snap.pay(snap_token, {
//  ↑           ↑
//  Fungsi dari Midtrans Snap.js (di-load di head)
//              Token yang baru kita dapat dari backend

    onSuccess: function () {
        // User selesai bayar → redirect ke halaman invoice
        window.location.href = '/kantin/status/' + idpesanan;
    },
    onPending: function () {
        // User pilih virtual account, belum bayar → redirect juga
        window.location.href = '/kantin/status/' + idpesanan;
    },
    onError: function () {
        // Ada error di Midtrans
        alert('Pembayaran gagal');
    },
    onClose: function () {
        // User tutup popup tanpa bayar
        // Tanya mau lanjut bayar atau tidak
    },
});
```

### Step 4: Midtrans kirim notifikasi ke server kita (Webhook)

Setelah user bayar, Midtrans otomatis kirim POST request ke URL yang kita daftarkan:

```php
// KantinController::midtransCallback()
public function midtransCallback(Request $request)
{
    // Verifikasi bahwa request ini benar dari Midtrans
    $orderId     = $request->input('order_id');
    $statusCode  = $request->input('status_code');
    $grossAmount = $request->input('gross_amount');
    $serverKey   = env('MIDTRANS_SERVER_KEY');
    
    $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
    //                  ↑
    //           Gabungkan semua string + server key, lalu hash
    //           Hasil unik yang hanya bisa dibuat kalau tahu server key
    
    if ($signature !== $expectedSig) {
        return response()->json(['ok' => false], 403);
        // Tanda tangan tidak cocok = bukan dari Midtrans = tolak!
    }
    
    $txStatus = $request->input('transaction_status');
    if (in_array($txStatus, ['capture', 'settlement'])) {
    //                        ↑           ↑
    //                   Kartu kredit   Transfer/QRIS berhasil
        Pesanan::where('midtrans_order_id', $orderId)
               ->update(['status_bayar' => 1]);
        // Update status pesanan jadi lunas
    }
}
```

---

# BAGIAN 12: VENDOR ISOLATION — CARA IMPLEMENTASI

## Konsep

Setiap vendor hanya bisa lihat datanya sendiri. Implementasinya ada di 3 tempat:

**1. Saat Registrasi** → vendor_id di-set ke user:
```php
// RegisterController::store()
$vendor = Vendor::create(['nama_vendor' => $request->nama_vendor]);
User::create([..., 'vendor_id' => $vendor->idvendor]);
```

**2. Saat Login** → vendor_id masuk ke session:
```php
// OtpController::verify()
session(['user' => [
    ...
    'vendor_id' => $user->vendor_id,  // null = admin, integer = vendor
]]);
```

**3. Saat Request Data** → filter berdasarkan session:
```php
// VendorController.php
private function vendorId(): ?int
{
    return session('user.vendor_id');
    // ↑ Kembalikan vendor_id dari session
    // Admin:  return null
    // Vendor: return 2 (misalnya)
    // Tanda tanya di ?int = bisa return null atau integer
}

public function index()
{
    $query = Menu::with('vendor');
    
    if ($this->vendorId()) {
    //   ↑ Kalau ini vendor (vendor_id != null)
        $query->where('idvendor', $this->vendorId());
        // Tambahkan filter → hanya menu vendor ini
    }
    // Kalau admin (vendorId() = null) → tidak ada filter → lihat semua
    
    $data = $query->get();
}
```

---

# BAGIAN 13: PDF GENERATION — DOMPDF

## Cara Kerja DomPDF

```
Controller siapkan data
    ↓
Load view Blade → render jadi HTML
    ↓
DomPDF konversi HTML+CSS → File PDF
    ↓
Stream ke browser (tampilkan) atau download
```

## Kode Controller

```php
public function cetakPdf(Request $request)
{
    // 1. Generate barcode untuk setiap barang
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    //                  ↑
    //           Buat object generator barcode format PNG
    
    $items = $selected->map(function ($b) use ($generator) {
    //                       ↑              ↑
    //                  Untuk setiap        Bawa variabel $generator
    //                  item di collection  masuk ke dalam fungsi
    //                  ini ($b = item)
        return [
            ...
            'barcode' => base64_encode(
                $generator->getBarcode($b->id_barang, $generator::TYPE_CODE_128)
                //          ↑           ↑               ↑
                //       Generate      Data yang        Tipe barcode (Code 128)
                //       barcode       di-encode        yang umum dipakai
            ),
            // base64_encode = konversi binary (gambar PNG) ke string teks
            // Dibutuhkan untuk embed gambar di HTML: src="data:image/png;base64,..."
        ];
    });
    
    // 2. Buat PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.pdf-label', [
    //                                  ↑          ↑
    //                            Load file view   Nama view (titik = slash)
    //                            dan render jadi  barang/pdf-label.blade.php
    //                            HTML
        'items'  => $items,
        'startX' => $startX,
        'startY' => $startY,
    ])
    ->setPaper([0, 0, 595.28, 481.89]);
    //           ↑  ↑   ↑       ↑
    //          x1 y1  lebar   tinggi (dalam "points" PDF)
    //          Koordinat kiri-bawah dan kanan-atas
    //          210mm × 170mm → 595.28pt × 481.89pt
    
    return $pdf->stream('label-harga.pdf');
    //          ↑        ↑
    //       Tampilkan  Nama file (muncul di tab browser)
    //       di browser
}
```

## View PDF — Grid Label

```php
<?php
// Hitung posisi awal:
$offset = ($startY - 1) * $cols + ($startX - 1);
// Contoh: startX=2, startY=1
// offset = (1-1)*5 + (2-1) = 0 + 1 = 1
// Artinya: lewati 1 slot kosong dulu

// Buat array 40 slot, semua null (kosong)
$cells = array_fill(0, $total, null);
// array_fill(index_awal, jumlah, nilai) = buat array berisi 40 null

// Isi slot dengan data barang mulai dari posisi offset
for ($i = 0; $i < count($itemArr); $i++) {
    if (($i + $offset) < $total) {
        $cells[$i + $offset] = $itemArr[$i];
    }
}
// Contoh dengan 2 barang, offset=1:
// $cells[0] = null     ← slot kosong (startX=2)
// $cells[1] = barang1  ← mulai dari sini
// $cells[2] = barang2
// $cells[3..39] = null

// Bagi array ke baris-baris (5 item per baris)
$pages = array_chunk($cells, $cols * $rows);
// array_chunk = potong array jadi potongan berukuran n
?>
```

---

# BAGIAN 14: QR CODE DAN SCANNER

## Generate QR Code

```blade
{{-- Di kantin/status.blade.php --}}
{!! QrCode::size(180)->generate((string) $pesanan->idpesanan) !!}
{{--  ↑                  ↑         ↑               ↑
   Facade QrCode      Ukuran    Generate        Data yang di-encode
   dari package       180px     QR Code         ID pesanan (misal: "5")
   simple-qrcode

   (string) = cast ke string karena generate() butuh string, bukan integer
   Output: SVG HTML langsung
   {!! !!} karena kita mau HTML dirender (bukan di-escape)
--}}
```

Ketika vendor scan QR code ini, mereka dapat teks `"5"` (ID pesanan), lalu bisa lookup datanya.

## Scanner QR (html5-qrcode)

```javascript
// vendor/scan.blade.php

// 1. Buat object scanner
const qrScanner = new Html5Qrcode('qrReader');
//                                 ↑
//                          ID div yang jadi "layar" kamera

// 2. Mulai scan
qrScanner.start(
    { facingMode: 'environment' },
    //              ↑ 'environment' = kamera belakang (kamera utama HP)
    //              'user' = kamera depan (selfie)
    
    { fps: 10, qrbox: { width: 220, height: 220 } },
    //  ↑               ↑
    //  Frame per      Kotak scan (area yang di-scan)
    //  second
    
    function (decodedText) {
    //          ↑ Dipanggil setiap kali QR berhasil di-scan
    //            decodedText = isi QR code (misal: "5")
        beep();
        qrScanner.stop();
        fetchPesanan(decodedText);  // ambil data pesanan dari server
    },
    
    function () {}  // error per frame, abaikan
);

// 3. Ambil data pesanan dari server
async function fetchPesanan(id) {
    const res = await axios.get('/vendor/scan/hasil/' + id);
    // GET /vendor/scan/hasil/5
    
    if (res.data.status === 'success') {
        const p = res.data.data;
        // Tampilkan: nama, total, status, rincian item
    }
}
```

---

# BAGIAN 15: UPLOAD FOTO DAN KAMERA

## Foto via File Upload (Metode 2 — yang Umum)

### HTML Form

```html
<form method="POST" action="/customer/tambah2" enctype="multipart/form-data">
<!--                                            ↑
                              WAJIB ada untuk upload file!
                              Tanpa ini, file tidak terkirim ke server -->
    @csrf
    <input type="file" name="foto" accept="image/*">
    <!--                ↑          ↑
                   Nama field     Hanya terima file gambar -->
    <button type="submit">Simpan</button>
</form>
```

### Controller

```php
public function simpan2(Request $request)
{
    $path = null;
    if ($request->hasFile('foto')) {
    //              ↑ Apakah ada file yang di-upload dengan nama 'foto'?
        $path = $request->file('foto')->store('customer', 'public');
        //       ↑                      ↑      ↑           ↑
        //    Ambil file           Simpan ke  Subfolder   Disk 'public'
        //    yang di-upload       storage    'customer'  = storage/app/public/
        //
        // store() return path relatif: 'customer/namafile_random.jpg'
        // File tersimpan di: storage/app/public/customer/namafile_random.jpg
        // URL publik: http://domain.com/storage/customer/namafile_random.jpg
        //             (perlu php artisan storage:link dulu)
    }
    
    Customer::create([
        'nama'      => $request->nama,
        'foto_path' => $path,  // simpan path, bukan file-nya
    ]);
}
```

### Menampilkan Foto

```blade
@if($customer->foto_path)
    <img src="{{ asset('storage/' . $customer->foto_path) }}"
    {{--          ↑
              asset() = generate URL lengkap
              asset('storage/customer/abc.jpg')
              → 'http://framework.test:8080/storage/customer/abc.jpg'
    --}}>
@endif
```

## Foto via Kamera Blob (Metode 1)

### HTML + JavaScript

```html
<video id="video" autoplay></video>   <!-- tampilan kamera langsung -->
<canvas id="canvas"></canvas>         <!-- gambar yang di-capture -->
<button onclick="capture()">Foto</button>
<input type="hidden" id="foto_base64" name="foto_base64">
```

```javascript
// 1. Akses kamera
async function startCamera() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    //                              ↑
    //                     Minta akses kamera
    
    document.getElementById('video').srcObject = stream;
    // Tampilkan feed kamera di element <video>
}

// 2. Capture foto
function capture() {
    const video  = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    //                        ↑         ↑
    //                 Gambar 2D       Copy frame saat ini dari video ke canvas
    
    const base64 = canvas.toDataURL('image/jpeg');
    //                                ↑
    //                      Format gambar
    // base64 = "data:image/jpeg;base64,/9j/4AAQSkZJRgAB..."
    // String panjang yang merepresentasikan gambar
    
    // Ambil bagian base64-nya saja (hapus "data:image/jpeg;base64,")
    const base64Data = base64.split(',')[1];
    document.getElementById('foto_base64').value = base64Data;
    // Simpan ke input hidden → akan ikut terkirim saat form di-submit
}
```

### Controller Simpan Blob

```php
public function simpan1(Request $request)
{
    $fotoBinary = null;
    if ($request->filled('foto_base64')) {
    //              ↑ Apakah field 'foto_base64' ada dan tidak kosong?
        $fotoBinary = base64_decode($request->foto_base64);
        //             ↑
        //          Konversi base64 string kembali ke data binary (gambar)
    }
    
    Customer::create([
        'nama'      => $request->nama,
        'foto_blob' => $fotoBinary,  // simpan binary langsung ke LONGBLOB
    ]);
}
```

---

# BAGIAN 16: KONEKSI FRONTEND KE BACKEND — RANGKUMAN

## Tiga Cara Frontend Berkomunikasi dengan Backend

### Cara 1: Form HTML Biasa (Full Page Reload)
```
HTML Form submit → POST /route → Controller → redirect() → Halaman baru dimuat
```
```html
<form method="POST" action="{{ route('barang.store') }}">
    @csrf
    <input name="nama">
    <button type="submit">Simpan</button>
</form>
```
```php
// Controller return redirect
return back()->with('success', 'Berhasil');
```

### Cara 2: AJAX dengan jQuery (Tanpa Reload, Gaya Lama)
```
JavaScript $.ajax() → POST /route → Controller return JSON → JS update DOM
```
```javascript
$.ajax({
    url: '/pos/cari',
    method: 'GET',
    data: { kode: '25052801' },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function(res) {
        if (res.status === 'success') {
            $('#nama').text(res.data.nama);  // update tampilan
        }
    }
});
```

### Cara 3: Axios (Tanpa Reload, Gaya Modern)
```
async/await axios.get/post → POST /route → Controller return JSON → JS update DOM
```
```javascript
const res = await axios.get('/pos/cari', { params: { kode: kode } });
if (res.data.status === 'success') {
    document.getElementById('nama').textContent = res.data.data.nama;
}
```

### Perbedaan Kapan Pakai Mana?

| Situasi | Pakai |
|---------|-------|
| Form CRUD biasa (tambah, edit, hapus) | Form HTML biasa |
| Search real-time, tanpa reload | Axios atau AJAX |
| POS Kasir (scan → tampil langsung) | Axios |
| Wilayah cascading (pilih provinsi → kota muncul) | Axios atau AJAX |
| Payment (butuh dapat snap_token, lalu open popup) | Axios |

---

# BAGIAN 17: POLA-POLA YANG DIPAKAI BERULANG

## Pola 1: CRUD Modal

Dipakai di: Kategori, Buku, Barang, Vendor Menu

```
Halaman → DataTable → Tombol Edit (data-id, data-nama) → JS isi Modal → Form PUT
                    → Tombol Hapus → Form DELETE
Tombol Tambah → Modal kosong → Form POST
```

**Kunci JavaScript untuk Modal Edit:**
```javascript
$(document).on('click', '.btnEdit', function () {
//               ↑ on() = event delegation
//                 Tangkap klik pada .btnEdit yang mungkin ditambah belakangan (DataTables)
    
    const id   = $(this).data('id');    // ambil data-id dari tombol
    const nama = $(this).data('nama');  // ambil data-nama dari tombol
    
    $('#inputId').val(id);     // isi form field dengan data yang ada
    $('#inputNama').val(nama);
    $('#formEdit').attr('action', '/kategori/' + id);  // set URL form
    
    $('#modalEdit').modal('show');  // buka modal
    // HARUS pakai jQuery .modal() karena Purple Admin template
    // tidak expose `bootstrap` sebagai variabel global
});
```

## Pola 2: JSON Response Konsisten

Semua endpoint AJAX/Axios mengembalikan format yang sama:
```php
return response()->json([
    'status'  => 'success',  // atau 'error'
    'code'    => 200,        // HTTP status code
    'message' => 'Keterangan',
    'data'    => $data,      // data yang dikembalikan (bisa null)
]);
```

Di JavaScript selalu cek:
```javascript
if (res.data.status === 'success') {
    // berhasil
} else {
    // gagal, tampilkan res.data.message
}
```

## Pola 3: Flash Message

Controller kirim pesan:
```php
return back()->with('success', 'Data berhasil disimpan.');
return back()->with('error', 'Terjadi kesalahan.');
return redirect()->route('barang.index')->with('success', 'Berhasil!');
```

View tampilkan:
```blade
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
```

## Pola 4: Validasi dengan Pesan Error

```php
// Controller
$request->validate([
    'email' => 'required|email|unique:users,email',
]);
```

```blade
{{-- View --}}
<input type="email" name="email"
    class="form-control @error('email') is-invalid @enderror">
    {{--                 ↑
          @error('email') → true jika validasi 'email' gagal
          is-invalid = class Bootstrap untuk input merah
    --}}

@error('email')
    <div class="invalid-feedback">{{ $message }}</div>
    {{--                              ↑ Pesan error otomatis dari Laravel --}}
@enderror
```

---

# BAGIAN 18: TROUBLESHOOTING — MASALAH UMUM

## Error 419 (Page Expired)
**Penyebab:** Form POST tanpa `@csrf` atau token CSRF tidak valid.  
**Solusi:** Tambahkan `@csrf` di dalam `<form>`.

## Error 405 (Method Not Allowed)
**Penyebab:** Form yang harusnya PUT/DELETE tidak punya `@method('PUT')`.  
**Solusi:** Tambahkan `@csrf @method('PUT')` di form update.

## Modal Tidak Muncul
**Penyebab di Purple Admin:** Bootstrap tidak di-expose sebagai `window.bootstrap`.  
**Solusi:** Gunakan `$('#modal').modal('show')` (jQuery), bukan `new bootstrap.Modal(...)`.

## DataTables Error "Incorrect column count"
**Penyebab:** Jumlah `<td>` di satu baris tidak sama dengan jumlah `<th>`.  
**Solusi:** Jangan taruh colspan di tbody DataTables. Gunakan modal untuk detail.

## Blade `@json()` Error dengan Arrow Function
```blade
{{-- MASALAH: Blade parser bingung dengan kurung kurawal --}}
var data = @json($collection->map(fn($x) => ['id' => $x->id]));

{{-- SOLUSI: Pre-compute di @php block --}}
@php $dataJs = $collection->map(fn($x) => ['id' => $x->id]); @endphp
var data = @json($dataJs);
```

## `$d->menu->nama_menu` Error (Trying to get property of null)
**Penyebab:** `$d->menu` bernilai null tapi langsung diakses propertinya.  
**Solusi:** Gunakan nullsafe operator: `$d->menu?->nama_menu ?? '-'`

```php
$d->menu?->nama_menu
//      ↑
//  Nullsafe operator: kalau $d->menu null, berhenti di sini (return null)
//  Tidak throw error seperti ->nama_menu
```

---

# RINGKASAN ALUR BELAJAR

Kalau kamu mau buat fitur baru dari nol, ikuti urutan ini:

```
1. Database dulu
   └── Buat migration: php artisan make:migration create_xxx_table
   └── Tulis kolom di migration
   └── php artisan migrate

2. Model
   └── php artisan make:model NamaModel
   └── Set $table, $primaryKey, $fillable, relasi

3. Route
   └── Tambahkan di routes/web.php
   └── GET untuk halaman, POST untuk form

4. Controller
   └── php artisan make:controller NamaController
   └── Buat method: index, store, update, destroy

5. View
   └── Buat file di resources/views/
   └── @extends('layouts.app') + @section('content')
   └── Loop data, buat form, buat modal

6. Test
   └── Buka browser, coba semua fungsi
   └── Cek Network tab di DevTools untuk AJAX
```

---

*Dokumen ini ditulis berdasarkan proyek di: `C:\laragon\www\framework`*  
*Setiap konsep dijelaskan dengan "kenapa" bukan hanya "apa".*
