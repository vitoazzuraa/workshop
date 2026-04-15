<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    private function getVendor()
    {
        return Vendor::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $vendor = $this->getVendor();
        $menu = Menu::where('idvendor', $vendor->idvendor)->get();
        return view('pages.menu.index', compact('menu'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('pages.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $vendor = $this->getVendor();

        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu_images', 'public');
        }

        Menu::create([
            'idvendor'    => $vendor->idvendor,
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'path_gambar' => $path,
        ]);

        return redirect()->route('user.menu.index')->with('success', 'Menu berhasil disimpan.');
    }

    public function edit($idmenu)
    {
        $vendor = $this->getVendor();
        $menu   = Menu::where('idmenu', $idmenu)
            ->where('idvendor', $vendor->idvendor)
            ->firstOrFail();

        $kategori = Kategori::all();
        return view('pages.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, $idmenu)
    {
        $vendor = $this->getVendor();
        $menu   = Menu::where('idmenu', $idmenu)
            ->where('idvendor', $vendor->idvendor)
            ->firstOrFail();

        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $menu->path_gambar;

        if ($request->hasFile('gambar')) {
            if ($menu->path_gambar) {
                Storage::disk('public')->delete($menu->path_gambar);
            }
            $path = $request->file('gambar')->store('menu_images', 'public');
        }

        $menu->update([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'path_gambar' => $path,
        ]);

        return redirect()->route('user.menu.index')->with('success', 'Menu berhasil diupdate.');
    }

    public function destroy($idmenu)
    {
        $vendor = $this->getVendor();
        $menu   = Menu::where('idmenu', $idmenu)
            ->where('idvendor', $vendor->idvendor)
            ->firstOrFail();

        if ($menu->path_gambar) {
            Storage::disk('public')->delete($menu->path_gambar);
        }

        $menu->delete();
        return redirect()->route('user.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
