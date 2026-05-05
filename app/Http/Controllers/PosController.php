<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function indexAjax()
    {
        return view('pos.index-ajax');
    }

    public function indexAxios()
    {
        return view('pos.index-axios');
    }

    public function cari(Request $request)
    {
        $barang = Barang::find($request->kode);
        if (!$barang) return response()->json(['status'=>'error','code'=>404,'message'=>'Barang tidak ditemukan.']);
        return response()->json(['status'=>'success','code'=>200,'data'=>$barang]);
    }

    public function bayar(Request $request)
    {
        $items = $request->input('items', []);
        if (empty($items)) return response()->json(['status'=>'error','code'=>400,'message'=>'Keranjang kosong.']);
        $total = collect($items)->sum('subtotal');
        $penjualan = Penjualan::create(['total'=>$total]);
        foreach ($items as $item) {
            PenjualanDetail::create(['id_penjualan'=>$penjualan->id_penjualan,'id_barang'=>$item['id_barang'],'jumlah'=>$item['jumlah'],'subtotal'=>$item['subtotal']]);
        }
        return response()->json(['status'=>'success','code'=>200,'message'=>'Transaksi berhasil disimpan.','data'=>['id_penjualan'=>$penjualan->id_penjualan]]);
    }
}
