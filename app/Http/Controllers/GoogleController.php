<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTPMail;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $userGoogle = Socialite::driver('google')->user();

            $user = User::where('id_google', $userGoogle->id)->first();

            if($user) {
                $targetUser = $user;
            } else {
                $existingUser = User::where('email', $userGoogle->email)->first();

                if($existingUser) {
                    $existingUser->update([
                        'id_google' => $userGoogle->id
                    ]);
                    $targetUser = $existingUser;
                } else {
                    $newUser = User::create([
                        'name' => $userGoogle->name,
                        'email' => $userGoogle->email,
                        'id_google' => $userGoogle->id,
                        'password' => bcrypt(Str::random(16)),
                    ]);
                    $targetUser = $newUser;
                }
            }

            $otpCode = rand(100000, 999999);

            $targetUser->update([
                'otp' => $otpCode
            ]);

            Mail::to($targetUser->email)->send(new SendOTPMail($otpCode));

            Auth::login($targetUser);

            return redirect()->route('otp.index');

        } catch (Exception $e) {
            return redirect('login')->with('error', 'Ada masalah saat login dengan Google.');
        }
    }

    public function otpIndex()
    {
        if (auth()->user()->otp == null) {
            return redirect()->route('home');
        }
        return view('auth.otp');
    }

    public function otpVerify(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $user = auth()->user();

        if ($request->otp === $user->otp) {
            $user->update(['otp' => null]);
            return redirect()->route('home');
        }
        return back()->withErrors(['otp' => 'Kode OTP salah.']);
    }
}
