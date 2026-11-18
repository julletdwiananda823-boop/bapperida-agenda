<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class BookingAuthController extends Controller
{
    /**
     * Login menggunakan PIN booking.
     */
    public function login(Request $request)
    {
        $request->merge([
            'nama' => trim((string) $request->input('nama', '')),
        ]);

        $request->validate([
            'pin' => ['required', 'string', 'max:100'],
            'nama' => ['required', 'string', 'max:150'],
        ]);

        $key = 'booking-login:' . Str::lower(
            (string) $request->ip()
        );

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Terlalu banyak percobaan login. Silakan coba lagi setelah '
                    . RateLimiter::availableIn($key)
                    . ' detik.',
            ], 429);
        }

        $pinBenar = (string) config('services.bapperida_booking_pin', '');

        if (
            $pinBenar === '' ||
            ! hash_equals($pinBenar, (string) $request->input('pin'))
        ) {
            RateLimiter::hit($key, 60);

            return response()->json([
                'message' => 'PIN tidak valid.',
            ], 422);
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();

        $request->session()->put([
            'booking_authenticated' => true,
            'booking_pemesan' => $request->input('nama'),
            'booking_owner_token' => bin2hex(random_bytes(32)),
        ]);

        return response()->json([
            'message' => 'Login booking berhasil.',
            'pemesan' => $request->input('nama'),
            'csrf_token' => csrf_token(),
        ]);
    }

    /**
     * Logout Booking tanpa mengeluarkan akun Admin.
     */
    public function logout(Request $request)
    {
        // Hapus sesi Booking saja.
        $request->session()->forget([
            'booking_authenticated',
            'booking_pemesan',
            'booking_owner_token',
        ]);

        // Perbarui token CSRF tanpa menghapus sesi Admin.
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout booking berhasil.',
            'csrf_token' => csrf_token(),
        ]);
    }
}
