<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('pages.barang.index', compact('barang'));
    }

    public function create()
    {
        return view('pages.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['timestamp'] = now();

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        return view('pages.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $barang->update($request->all());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }

    public function print(Request $request)
{
    $ids = $request->input('ids', []);
    $startX = (int)$request->input('x', 1);
    $startY = (int)$request->input('y', 1);

    $selectedBarang = Barang::whereIn('id_barang', $ids)->get();

    $skip = (($startY - 1) * 5) + ($startX - 1);

    $labels = array_fill(0, 40, null);

    $currentIndex = $skip;
    foreach ($selectedBarang as $b) {
        if ($currentIndex < 40) {
            $labels[$currentIndex++] = $b;
        }
    }

    $mm = 2.83465;

    $pdf = Pdf::loadView('pdf.tag_harga', compact('labels'))
    ->setPaper([0, 0, 210 * $mm, 165 * $mm], 'portrait');

    return $pdf->stream('tag_harga_108.pdf');
}
}
