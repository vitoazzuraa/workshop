<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $data = Kategori::orderBy('nama_kategori')->get();
        return view('kategori.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        Kategori::create($request->only('nama_kategori'));
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        Kategori::findOrFail($id)->update($request->only('nama_kategori'));
        return back()->with('success', 'Kategori berhasil diubah.');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
