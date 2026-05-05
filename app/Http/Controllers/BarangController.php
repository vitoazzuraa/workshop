<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $data = Barang::orderBy('timestamp', 'desc')->get();
        return view('barang.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
        ]);
        // id_barang di-generate trigger MySQL, kirim placeholder
        \DB::statement('INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)', [
            '00000000', $request->nama, $request->harga,
        ]);
        return back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
        ]);
        Barang::findOrFail($id)->update($request->only('nama', 'harga'));
        return back()->with('success', 'Barang berhasil diubah.');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }

    public function cetakForm()
    {
        $data = Barang::orderBy('timestamp', 'desc')->get();
        return view('barang.cetak', compact('data'));
    }

    public function cetakPdf(Request $request)
    {
        $ids = $request->input('id_barang', []);
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu barang.');
        }

        $startX = max(1, min(5, (int) $request->input('start_x', 1)));
        $startY = max(1, min(8, (int) $request->input('start_y', 1)));

        $barangs = Barang::whereIn('id_barang', $ids)->get()->keyBy('id_barang');
        // Urutkan sesuai urutan yang dipilih
        $selected = collect($ids)->map(fn($id) => $barangs->get($id))->filter();

        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

        // Tambahkan barcode base64 ke setiap barang
        $items = $selected->map(function ($b) use ($generator) {
            return [
                'id_barang' => $b->id_barang,
                'nama'      => $b->nama,
                'harga'     => $b->harga,
                'barcode'   => base64_encode($generator->getBarcode($b->id_barang, $generator::TYPE_CODE_128)),
            ];
        })->values();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.pdf-label', [
            'items'  => $items,
            'startX' => $startX,
            'startY' => $startY,
        ])->setPaper([0, 0, 595.28, 481.89]); // 210mm × 170mm in points

        return $pdf->stream('label-harga.pdf');
    }
}
