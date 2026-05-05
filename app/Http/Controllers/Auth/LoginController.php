<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        return $this->kirimOtp($user);
    }

    public function kirimOtp(User $user)
    {
        $otp = strtoupper(Str::random(6));
        $user->update(['otp' => $otp]);

        session(['otp_user_id' => $user->id]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            // Di dev mode mail mungkin log-only, OTP tetap tersimpan di DB
        }

        return redirect()->route('otp.show');
    }

    public function logout(Request $request)
    {
        session()->forget(['user', 'otp_user_id']);
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
