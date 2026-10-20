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

        // Batasi percobaan login berdasarkan IP.
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

        // PIN disimpan pada file .env, bukan langsung di kode.
        $pinBenar = (string) env('BAPPERIDA_BOOKING_PIN', '');

        if (
            $pinBenar === '' ||
            ! hash_equals($pinBenar, (string) $request->input('pin'))
        ) {
            RateLimiter::hit($key, 60);

            return response()->json([
                'message' => 'PIN tidak valid.',
            ], 422);
        }

        // Hapus hitungan percobaan setelah login berhasil.
        RateLimiter::clear($key);

        // Regenerasi session untuk membantu mencegah session fixation.
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
     * Logout booking dan invalidasi session.
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();

        // Buat token CSRF baru setelah session diinvalidasi.
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout booking berhasil.',
            'csrf_token' => csrf_token(),
        ]);
    }
}
