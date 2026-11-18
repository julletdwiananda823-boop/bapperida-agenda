<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (
                in_array($user->role, ['admin', 'pimpinan'], true) &&
                $user->is_active
            ) {
                return redirect()->route('home');
            }

            Auth::logout();
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'role' => ['required', 'in:admin,pimpinan'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $key = 'admin-login:' . Str::lower((string) $request->ip());

        // BATASI PERCOBAAN LOGIN
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $pesan = 'Terlalu banyak percobaan login. Silakan coba lagi setelah '
                . RateLimiter::availableIn($key)
                . ' detik.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $pesan,
                ], 429);
            }

            return back()
                ->withErrors(['email' => $pesan])
                ->withInput($request->only('email', 'role'));
        }

        // PERIKSA EMAIL, PASSWORD, DAN STATUS AKUN
        $loginBerhasil = Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ]);

        // JIKA EMAIL ATAU PASSWORD SALAH
        if (!$loginBerhasil) {
            RateLimiter::hit($key, 60);

            $pesan = 'Email atau password salah. Silakan coba kembali.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $pesan,
                ], 422);
            }

            return back()
                ->withErrors(['email' => $pesan])
                ->withInput($request->only('email', 'role'));
        }

        $user = Auth::user();

        // PASTIKAN JENIS LOGIN SESUAI DENGAN ROLE AKUN
        if ($user->role !== $credentials['role']) {
            Auth::logout();
            $request->session()->regenerate();

            RateLimiter::hit($key, 60);

            $pesan = 'Akun ini tidak sesuai dengan jenis login yang dipilih.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $pesan,
                ], 422);
            }

            return redirect()
                ->route('home')
                ->withErrors(['email' => $pesan]);
        }

        // LOGIN BERHASIL
        RateLimiter::clear($key);

        $request->session()->regenerate();

        // ADMIN DAN PIMPINAN MENDAPATKAN AKSES BOOKING
        $request->session()->put([
            'booking_authenticated' => true,
            'booking_pemesan' => $user->name,
            'booking_owner_token' => bin2hex(random_bytes(32)),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('home'),
            ]);
        }

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
