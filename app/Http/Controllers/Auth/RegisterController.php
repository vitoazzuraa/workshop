<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor'           => 'required|string|max:100',
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $vendorRole = Role::where('nama_role', 'vendor')->firstOrFail();
        $vendor     = Vendor::create(['nama_vendor' => $request->nama_vendor]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $vendorRole->id,
            'vendor_id' => $vendor->idvendor,
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun vendor berhasil dibuat! Silakan login.');
    }
}
