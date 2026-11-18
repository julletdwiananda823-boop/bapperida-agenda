<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureManagement
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json(['message' => 'Sesi login telah berakhir.'], 401);
            }
            return redirect()->route('admin.login');
        }

        if (
            !in_array($user->role, ['admin', 'pimpinan'], true) ||
            !$user->is_active
        ) {
            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json(['message' => 'Akses hanya untuk Pimpinan dan Admin BAPPERIDA.'], 403);
            }
            abort(403, 'Akses hanya untuk Pimpinan dan Admin BAPPERIDA.');
        }

        return $next($request);
    }
}