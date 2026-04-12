<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class KatalogMenuController extends Controller
{
    public function index(Request $request)
    {
        $users = User::has('menu')->get();

        $menus = Menu::when($request->user_id, function($query) use ($request) {
            return $query->where('id', $request->user_id);
        })->get();

        return view('customer.index', compact('users', 'menus'));
    }
}
