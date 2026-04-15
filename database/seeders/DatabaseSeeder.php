<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['nama_role' => 'vendor']);
        Role::create(['nama_role' => 'admin']);

        $userVendor = User::create([
            'name' => 'Budi Pemilik Kantin',
            'email' => 'vendor@test.com',
            'password' => Hash::make('123'),
            'idrole' => 1,
        ]);

        $vendor = Vendor::create([
            'user_id' => $userVendor->id,
            'nama_vendor' => 'Kantin Sejahtera',
        ]);

        $daftar_makanan = [
            ['nama' => 'Nasi Ayam Bakar', 'harga' => 15000],
            ['nama' => 'Mie Goreng Telur', 'harga' => 12000],
            ['nama' => 'Mie Ayam', 'harga' => 10000],
            ['nama' => 'Sate', 'harga' => 10000],
            ['nama' => 'Es Teh', 'harga' => 7000],
        ];

        foreach ($daftar_makanan as $m) {
            Menu::create([
                'idvendor'  => $vendor->idvendor,
                'nama_menu' => $m['nama'],
                'harga'     => $m['harga'],
                'path_gambar' => null,
            ]);
        }

        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@test.com',
            'password' => Hash::make('123'),
            'idrole' => 2,
        ]);
    }
}