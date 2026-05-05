<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('user.role');

        if ($role === 'vendor') {
            $vendorId = session('user.vendor_id');
            $stats = [
                'menu'          => Menu::where('idvendor', $vendorId)->count(),
                'pesanan_masuk' => Pesanan::where('status_bayar', 0)
                    ->whereHas('detail.menu', fn($q) => $q->where('idvendor', $vendorId))->count(),
                'pesanan_lunas' => Pesanan::where('status_bayar', 1)
                    ->whereHas('detail.menu', fn($q) => $q->where('idvendor', $vendorId))->count(),
            ];
        } else {
            $stats = [
                'kategori' => Kategori::count(),
                'buku'     => Buku::count(),
                'barang'   => Barang::count(),
                'menu'     => Menu::count(),
                'pesanan_lunas' => Pesanan::where('status_bayar', 1)->count(),
            ];
        }

        return view('dashboard.index', compact('stats', 'role'));
    }
}
