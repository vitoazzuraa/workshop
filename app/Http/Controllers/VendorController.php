<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    private function vendorId(): ?int
    {
        return session('user.vendor_id');
    }

    public function index()
    {
        $query = Menu::with('vendor');
        if ($this->vendorId()) {
            $query->where('idvendor', $this->vendorId());
        }
        $data    = $query->get();
        $vendors = $this->vendorId() ? collect() : Vendor::all();
        return view('vendor.menu.index', compact('data', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
            'foto'      => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('menu', 'public');
        }

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'idvendor'    => $this->vendorId() ?? $request->idvendor,
            'path_gambar' => $path,
        ]);

        return back()->with('success', 'Menu ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        if ($this->vendorId() && $menu->idvendor !== $this->vendorId()) {
            abort(403);
        }

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
            'foto'      => 'nullable|image|max:2048',
        ]);

        $data = $request->only('nama_menu', 'harga');
        if ($request->hasFile('foto')) {
            if ($menu->path_gambar) {
                Storage::disk('public')->delete($menu->path_gambar);
            }
            $data['path_gambar'] = $request->file('foto')->store('menu', 'public');
        }

        $menu->update($data);
        return back()->with('success', 'Menu diubah.');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($this->vendorId() && $menu->idvendor !== $this->vendorId()) {
            abort(403);
        }
        if ($menu->path_gambar) {
            Storage::disk('public')->delete($menu->path_gambar);
        }
        $menu->delete();
        return back()->with('success', 'Menu dihapus.');
    }

    public function pesanan()
    {
        $query = Pesanan::where('status_bayar', 1)
            ->with('detail.menu')
            ->orderBy('timestamp', 'desc');

        if ($this->vendorId()) {
            $vid = $this->vendorId();
            $query->whereHas('detail.menu', fn($q) => $q->where('idvendor', $vid));
        }

        $data = $query->get();
        return view('vendor.pesanan.index', compact('data'));
    }

    public function scan()
    {
        return view('vendor.scan');
    }

    public function scanHasil($id)
    {
        $pesanan = Pesanan::with('detail.menu')->find($id);
        if (!$pesanan) {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Pesanan tidak ditemukan.']);
        }

        if ($this->vendorId()) {
            $vid      = $this->vendorId();
            $hasItem  = $pesanan->detail->contains(fn($d) => $d->menu && $d->menu->idvendor === $vid);
            if (!$hasItem) {
                return response()->json(['status' => 'error', 'code' => 403, 'message' => 'Pesanan bukan milik vendor ini.']);
            }
        }

        return response()->json(['status' => 'success', 'code' => 200, 'data' => $pesanan]);
    }
}
