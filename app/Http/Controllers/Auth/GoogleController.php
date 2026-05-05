<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal. Coba lagi.']);
        }

        // Cari user berdasarkan id_google atau email
        $user = User::where('id_google', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            $adminRole = Role::where('nama_role', 'admin')->first();
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'id_google' => $googleUser->getId(),
                'password'  => Hash::make(str()->random(16)),
                'role_id'   => $adminRole->id,
            ]);
        } else {
            $user->update(['id_google' => $googleUser->getId()]);
        }

        $loginController = new LoginController();
        return $loginController->kirimOtp($user);
    }
}
