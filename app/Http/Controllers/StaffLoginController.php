<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class StaffLoginController extends Controller
{
    /**
     * Menampilkan akses login Pegawai.
     */
    public function showLogin()
    {
        if (session('staff_authenticated') === true) {
            return redirect()->route('home');
        }

        return redirect()->route('home', ['action' => 'login_staff']);
    }

    /**
     * Login Pegawai menggunakan satu PIN bersama.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'pin' => ['required', 'string', 'min:4', 'max:100'],
        ]);

        $key = 'staff-login:' . Str::lower((string) $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $pesan = 'Terlalu banyak percobaan login. Silakan coba lagi setelah '
                . RateLimiter::availableIn($key)
                . ' detik.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $pesan,
                ], 429);
            }

            return redirect()->route('home')->withErrors([
                'pin' => $pesan,
            ]);
        }

        // Periksa PIN hash dari database terlebih dahulu.
        $pinHash = DB::table('app_settings')
            ->where('key', 'staff_pin_hash')
            ->value('value');

        if (!empty($pinHash)) {
            $pinValid = Hash::check(
                (string) $credentials['pin'],
                (string) $pinHash
            );
        } else {
            // Tetap mendukung PIN lama dari .env.
            $pinBenar = (string) config('services.bapperida_staff_pin', '');

            $pinValid = $pinBenar !== '' &&
                hash_equals($pinBenar, (string) $credentials['pin']);
        }

        // Jika PIN salah.
        if (!$pinValid) {
            RateLimiter::hit($key, 60);

            $pesan = 'PIN Pegawai salah atau belum dikonfigurasi.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $pesan,
                ], 422);
            }

            return redirect()->route('home')->withErrors([
                'pin' => $pesan,
            ]);
        }

        RateLimiter::clear($key);

        // Pegawai hanya memperoleh sesi akses Pegawai.
        $request->session()->regenerate();

        $request->session()->put('staff_authenticated', true);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('home'),
            ]);
        }

        return redirect()->route('home');
    }

    /**
     * Logout Pegawai tanpa mengganggu sesi Admin/Pimpinan.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('staff_authenticated');

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
