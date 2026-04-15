<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor; 
use Illuminate\Http\Request;

class KatalogMenuController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Vendor::has('menu')->get();

        $menu = Menu::when($request->idvendor, function($query) use ($request) {
            return $query->where('idvendor', $request->idvendor);
        })->get();

        return view('customer.index', compact('vendor', 'menu'));
    }
}