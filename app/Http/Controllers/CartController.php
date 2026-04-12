<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
    public function add(Request $request) {
        if (Auth::check()) {
            return back()->with('error', 'Penjual tidak diizinkan melakukan pembelian!');
        }

        $menu = Menu::findOrFail($request->id_menu);
        $cart = session()->get('cart', []);

        if(isset($cart[$request->id_menu])) {
            $cart[$request->id_menu]['quantity'] += $request->quantity;
        } else {
            $cart[$request->id_menu] = [
                "name" => $menu->nama_menu,
                "quantity" => $request->quantity,
                "price" => $menu->harga,
                "id_user" => $menu->id
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Menu berhasil ditambah ke keranjang!');
    }

    public function remove(Request $request) {
        $cart = session()->get('cart');
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
        return back();
    }
}
