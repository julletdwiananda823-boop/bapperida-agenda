<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookingAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
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