<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('pages.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('pages.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|max:100',
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($idkategori)
    {
        $kategori = Kategori::findOrFail($idkategori);
        return view('pages.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $idkategori)
    {
        $kategori = Kategori::findOrFail($idkategori);
        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($idkategori)
    {
        $kategori = Kategori::findOrFail($idkategori);
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
