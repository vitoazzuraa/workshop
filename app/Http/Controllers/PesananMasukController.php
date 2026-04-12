<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class PesananMasukController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('id', Auth::id())
                          ->where('status_bayar', 'lunas')
                          ->with(['guest', 'detailPesanan.menu'])
                          ->latest()
                          ->get();

        return view('pages.pesanan.index', compact('pesanan'));
    }
}
