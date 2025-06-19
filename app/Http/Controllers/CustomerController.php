<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{
    // Redirect ke Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    // Callback dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah email sudah terdaftar
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(str()->random(16)),
                    'role' => 'user', // Role customer
                ]);

                // Buat data customer
                Customer::create([
                    'user_id' => $user->id,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                ]);
            } else {
                // Cek apakah user sudah memiliki data customer
                $customer = Customer::where('user_id', $user->id)->first();

                if (!$customer) {
                    // Jika belum ada data customer, buat baru
                    Customer::create([
                        'user_id' => $user->id,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                    ]);
                } else {
                    // Update token Google jika perlu
                    $customer->update([
                        'google_token' => $googleUser->token
                    ]);
                }
            }

            // Login user
            Auth::login($user);

            return redirect('/')
                ->with('success', 'Berhasil login dengan Google!');
        } catch (Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google. ' . $e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna
        $request->session()->invalidate(); // Hapus session
        $request->session()->regenerateToken(); // Regenerate token CSRF
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}
