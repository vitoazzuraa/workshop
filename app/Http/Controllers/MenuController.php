<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('id', Auth::id())->get();
        return view('pages.menu.index', compact('menus'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('pages.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu_images', 'public');
        }

        Menu::create([
            'id'          => Auth::id(),
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'path_gambar' => $path
        ]);

        return redirect()->route('user.menu.index')->with('success', 'Menu berhasil disimpan.');
    }

    public function edit($idmenu)
    {
        $menu = Menu::where('idmenu', $idmenu)
                    ->where('id', Auth::id())
                    ->firstOrFail();

        $kategori = Kategori::all();
        return view('pages.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, $idmenu)
    {
        $menu = Menu::where('idmenu', $idmenu)
                    ->where('id', Auth::id())
                    ->firstOrFail();

        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
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
            'path_gambar' => $path
        ]);

        return redirect()->route('user.menu.index')->with('success', 'Menu berhasil diupdate.');
    }

    public function destroy($idmenu)
    {
        $menu = Menu::where('idmenu', $idmenu)
                    ->where('id', Auth::id())
                    ->firstOrFail();

        if ($menu->path_gambar) {
            Storage::disk('public')->delete($menu->path_gambar);
        }

        $menu->delete();
        return redirect()->route('user.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
