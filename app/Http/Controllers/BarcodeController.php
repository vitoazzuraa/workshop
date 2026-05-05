<?php

namespace App\Http\Controllers;
use App\Models\Barang;

class BarcodeController extends Controller
{
    public function scan()
    {
        return view('barcode.scan');
    }

    public function hasil($kode)
    {
        $barang = Barang::find($kode);
        if (!$barang) return response()->json(['status'=>'error','code'=>404,'message'=>'Barang tidak ditemukan.']);
        return response()->json(['status'=>'success','code'=>200,'data'=>$barang]);
    }
}
