<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $data     = Buku::with('kategori')->orderBy('judul')->get();
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('buku.index', compact('data', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'       => 'required|string|max:20',
            'judul'      => 'required|string|max:500',
            'pengarang'  => 'required|string|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);
        Buku::create($request->only('kode', 'judul', 'pengarang', 'idkategori'));
        return back()->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode'       => 'required|string|max:20',
            'judul'      => 'required|string|max:500',
            'pengarang'  => 'required|string|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);
        Buku::findOrFail($id)->update($request->only('kode', 'judul', 'pengarang', 'idkategori'));
        return back()->with('success', 'Buku berhasil diubah.');
    }

    public function destroy($id)
    {
        Buku::findOrFail($id)->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }
}
