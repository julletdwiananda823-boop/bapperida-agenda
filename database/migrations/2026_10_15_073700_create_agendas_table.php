<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BookingAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'string', 'max:100'],
            'nama' => ['required', 'string', 'max:150'],
        ]);

        $pinBenar = (string) env('BAPPERIDA_BOOKING_PIN', '');

        if ($pinBenar === '' || ! hash_equals($pinBenar, $request->pin)) {
            return response()->json([
                'message' => 'PIN tidak valid.',
            ], 422);
        }

        $request->session()->regenerate();

        $request->session()->put([
            'booking_authenticated' => true,
            'booking_pemesan' => trim($request->nama),
            'booking_owner_token' => bin2hex(random_bytes(32)),
        ]);

        return response()->json([
            'message' => 'Login booking berhasil.',
            'pemesan' => trim($request->nama),
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'booking_authenticated',
            'booking_pemesan',
            'booking_owner_token',
        ]);

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout booking berhasil.',
        ]);
    }
}