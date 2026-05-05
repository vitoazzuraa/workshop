<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function show()
    {
        if (!session()->has('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $userId = session('otp_user_id');
        $user   = User::find($userId);

        if (!$user || strtoupper($request->otp) !== strtoupper($user->otp)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        $user->update(['otp' => null]);
        session()->forget('otp_user_id');

        $user->load('role');
        session(['user' => [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role'      => $user->role->nama_role,
            'vendor_id' => $user->vendor_id,
        ]]);

        return redirect()->route('dashboard');
    }
}
