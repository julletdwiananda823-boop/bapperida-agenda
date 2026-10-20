<?php

namespace App\Http\Middleware;

use App\Models\RoomBooking;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventRoomConflict
{
    public function handle(Request $request, Closure $next): Response
    {
        // Middleware ini hanya untuk endpoint booking,
        // bukan untuk form penyimpanan agenda.
        if ($request->path() !== 'bookings' || !$request->isMethod('POST')) {
            return $next($request);
        }

        $validated = $request->validate([
            'ruang' => ['required', 'string'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
        ]);

        $conflict = RoomBooking::where('ruang', $validated['ruang'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam atau ruangan lain.',
            ], 422);
        }

        return $next($request);
    }
}
