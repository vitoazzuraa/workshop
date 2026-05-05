<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $adminRole  = Role::firstOrCreate(['nama_role' => 'admin'],  ['deskripsi' => 'Administrator penuh']);
        $vendorRole = Role::firstOrCreate(['nama_role' => 'vendor'], ['deskripsi' => 'Pengelola menu kantin']);

        // User admin
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name'     => 'Admin',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
        ]);


        // 3 Kategori
        $novel    = Kategori::firstOrCreate(['nama_kategori' => 'Novel']);
        $biografi = Kategori::firstOrCreate(['nama_kategori' => 'Biografi']);
        Kategori::firstOrCreate(['nama_kategori' => 'Komik']);

        // 3 Buku
        Buku::firstOrCreate(['kode' => 'NV-01'], [
            'judul'      => 'Home Sweet Loan',
            'pengarang'  => 'Almira Bastari',
            'idkategori' => $novel->idkategori,
        ]);
        Buku::firstOrCreate(['kode' => 'BO-01'], [
            'judul'      => 'Mohammad Hatta, Untuk Negeriku',
            'pengarang'  => 'Taufik Abdullah',
            'idkategori' => $biografi->idkategori,
        ]);
        Buku::firstOrCreate(['kode' => 'NV-02'], [
            'judul'      => 'Keajaiban Toko Kelontong Namiya',
            'pengarang'  => 'Keigo Higashino',
            'idkategori' => $novel->idkategori,
        ]);

        // 10 Barang (via raw INSERT agar trigger id_barang aktif)
        $barangList = [
            ['Nasi Goreng Spesial', 15000],
            ['Mie Ayam Bakso', 12000],
            ['Es Teh Manis', 5000],
            ['Jus Alpukat', 10000],
            ['Roti Bakar Coklat', 8000],
            ['Soto Ayam', 14000],
            ['Gado-Gado', 11000],
            ['Air Mineral 600ml', 3000],
            ['Pisang Goreng', 6000],
            ['Lontong Sayur', 9000],
        ];
        foreach ($barangList as [$nama, $harga]) {
            $exists = DB::table('barang')->where('nama', $nama)->exists();
            if (!$exists) {
                DB::statement('INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)', ['00000000', $nama, $harga]);
            }
        }

        // 2 Vendor kantin (dibuat sebelum user vendor agar vendor_id tersedia)
        $vendor1 = Vendor::firstOrCreate(['nama_vendor' => 'Warung Bu Sari']);
        $vendor2 = Vendor::firstOrCreate(['nama_vendor' => 'Kantin Pak Joko']);

        // User vendor — masing-masing terikat ke vendornya sendiri
        User::firstOrCreate(['email' => 'vendor@example.com'], [
            'name'      => 'Bu Sari',
            'password'  => Hash::make('password'),
            'role_id'   => $vendorRole->id,
            'vendor_id' => $vendor1->idvendor,
        ]);
        User::firstOrCreate(['email' => 'vendor2@example.com'], [
            'name'      => 'Pak Joko',
            'password'  => Hash::make('password'),
            'role_id'   => $vendorRole->id,
            'vendor_id' => $vendor2->idvendor,
        ]);

        // Menu per vendor
        $menus = [
            [$vendor1->idvendor, 'Nasi Goreng Spesial', 15000],
            [$vendor1->idvendor, 'Mie Ayam Bakso', 12000],
            [$vendor1->idvendor, 'Soto Ayam', 14000],
            [$vendor2->idvendor, 'Roti Bakar Coklat', 8000],
            [$vendor2->idvendor, 'Jus Alpukat', 10000],
            [$vendor2->idvendor, 'Es Teh Manis', 5000],
        ];
        foreach ($menus as [$idvendor, $nama, $harga]) {
            \App\Models\Menu::firstOrCreate(['nama_menu' => $nama, 'idvendor' => $idvendor], ['harga' => $harga]);
        }

        $this->call(WilayahSeeder::class);
    }
}
