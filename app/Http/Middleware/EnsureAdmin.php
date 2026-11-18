<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
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

        if ($user->role !== 'admin' || !$user->is_active) {
            if ($request->expectsJson() || $request->isXmlHttpRequest()) {
                return response()->json(['message' => 'Akses hanya untuk Admin BAPPERIDA.'], 403);
            }
            abort(403, 'Akses hanya untuk Admin BAPPERIDA.');
        }

        return $next($request);
    }
}