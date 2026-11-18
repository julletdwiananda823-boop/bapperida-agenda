<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookingAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Admin dan Pimpinan yang aktif otomatis boleh Booking.
        $user = $request->user();

        if (
            $user &&
            in_array($user->role, ['admin', 'pimpinan'], true) &&
            $user->is_active
        ) {
            return $next($request);
        }

        // Pengguna lain harus memiliki sesi Booking yang valid.
        if (
            $request->session()->get('booking_authenticated') !== true ||
            !$request->session()->has('booking_pemesan') ||
            !$request->session()->has('booking_owner_token')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login booking terlebih dahulu.',
            ], 401);
        }

        return $next($request);
    }
}
