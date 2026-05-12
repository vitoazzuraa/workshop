<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use Illuminate\Http\Request;

class KunjunganTokoController extends Controller
{
    public function index()
    {
        $tokos = LokasiToko::all();
        return view('kunjungan.index', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:50',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric|min:0',
        ]);

        $count   = LokasiToko::count() + 1;
        $barcode = 'TK' . str_pad($count, 6, '0', STR_PAD_LEFT);

        while (LokasiToko::find($barcode)) {
            $count++;
            $barcode = 'TK' . str_pad($count, 6, '0', STR_PAD_LEFT);
        }

        LokasiToko::create([
            'barcode'   => $barcode,
            'nama_toko' => $request->nama_toko,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
        ]);

        return back()->with('success', 'Toko ' . $request->nama_toko . ' berhasil ditambahkan (barcode: ' . $barcode . ').');
    }

    public function destroy($barcode)
    {
        LokasiToko::findOrFail($barcode)->delete();
        return back()->with('success', 'Toko berhasil dihapus.');
    }

    public function cetakBarcode($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $png       = $generator->getBarcode($toko->barcode, $generator::TYPE_CODE_128);

        return response($png, 200)->header('Content-Type', 'image/png');
    }

    public function hasilScan($barcode)
    {
        $toko = LokasiToko::find($barcode);
        if (!$toko) {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Toko tidak ditemukan.']);
        }
        return response()->json(['status' => 'success', 'code' => 200, 'data' => $toko]);
    }
}
