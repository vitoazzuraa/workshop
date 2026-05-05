<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $data = Customer::latest()->get();
        return view('customer.index', compact('data'));
    }

    public function tambah1()
    {
        return view('customer.tambah1');
    }

    public function simpan1(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);

        // Decode base64 → binary untuk disimpan sebagai LONGBLOB
        $fotoBinary = null;
        if ($request->filled('foto_base64')) {
            $fotoBinary = base64_decode($request->foto_base64);
        }

        Customer::create([
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'foto_blob' => $fotoBinary,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (foto blob).');
    }

    public function tambah2()
    {
        return view('customer.tambah2');
    }

    public function simpan2(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('customer', 'public');
        }

        Customer::create([
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'foto_path' => $path,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (foto file).');
    }
}
