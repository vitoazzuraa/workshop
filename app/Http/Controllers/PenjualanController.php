<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;

class PenjualanController extends Controller
{
    public function index()
    {
        $data = Penjualan::with('detail.barang')->orderBy('timestamp','desc')->get();
        return view('penjualan.index', compact('data'));
    }
}
