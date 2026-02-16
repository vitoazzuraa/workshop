<?php

namespace App\Http\Controllers;

use App\Models\Buku; 
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get(); 
        $kategori = Kategori::all();
        
        return view('pages.buku.index', compact('buku', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|max:20',
            'judul' => 'required|max:500',
            'pengarang' => 'required|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }
}
