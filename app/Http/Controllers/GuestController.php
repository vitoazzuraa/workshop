<?php

namespace App\Http\Controllers;

use App\Models\Guest;

class GuestController extends Controller
{
    public function index()
    {
        $data = Guest::withCount('pesanan')
            ->withSum('pesanan', 'total')
            ->latest()
            ->get();

        return view('guest.index', compact('data'));
    }
}
