<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        return view('pages.kasir.index');
    }

    public function cariBarang(Request $req)
    {
        $barang = Barang::where('id_barang', $req->id_barang)->first();

        if ($barang) {
            return response()->json([
                'status' => 'success',
                'data'   => $barang
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }

    public function bayar(Request $req)
    {
        $penjualan = Penjualan::create([
            'timestamp' => now(),
            'total'     => $req->total
        ]);

        foreach ($req->items as $item) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang'    => $item['id_barang'],
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal']
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi berhasil disimpan'
        ]);
    }
}
