<?php

namespace App\Http\Controllers;

use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomBookingController extends Controller
{
    private array $rooms = [
        'Ruang Rapat Batu Dinding Bappeda',
        'Ruang Rapat Linsum Kawai Bappeda',
        'Ruang Rapat Batu Bedil Bappeda',
    ];

    /**
     * Pastikan pengguna sudah login melalui PIN booking.
     */
    private function ensureBookingLogin(Request $request): void
    {
        abort_unless(
            $request->session()->get('booking_authenticated') === true
            && is_string($request->session()->get('booking_pemesan'))
            && is_string($request->session()->get('booking_owner_token')),
            401,
            'Silakan login terlebih dahulu untuk menggunakan booking.'
        );
    }

    /**
     * Tampilkan jadwal booking.
     * Token kepemilikan tidak pernah dikirim ke browser.
     */
    public function index()
    {
        $bookings = RoomBooking::query()
            ->select([
                'id',
                'ruang',
                'tanggal',
                'jam_mulai',
                'jam_selesai',
                'acara',
                'pemesan',
            ])
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return response()->json(['data' => $bookings]);
    }

    /**
     * Simpan booking baru dan cegah bentrok waktu.
     */
    public function store(Request $request)
    {
        $this->ensureBookingLogin($request);

        $validated = $request->validate([
            'ruang' => ['required', 'string', 'in:' . implode(',', $this->rooms)],
            'tanggal' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'acara' => ['required', 'string', 'max:255'],
        ]);

        $ownerToken = $request->session()->get('booking_owner_token');
        $pemesan = $request->session()->get('booking_pemesan');

        // MySQL advisory lock membuat pemeriksaan bentrok untuk
        // ruangan dan tanggal yang sama berjalan bergantian.
        $lockName = 'booking_' . sha1(
            $validated['ruang'] . '|' . $validated['tanggal']
        );

        $lock = DB::selectOne(
            'SELECT GET_LOCK(?, 10) AS acquired',
            [$lockName]
        );

        if ((int) ($lock->acquired ?? 0) !== 1) {
            return response()->json([
                'message' => 'Sistem sedang memproses booking ruangan ini. Silakan coba kembali.',
            ], 409);
        }

        try {
            $booking = DB::transaction(function () use (
                $validated,
                $ownerToken,
                $pemesan
            ) {
                $conflict = RoomBooking::query()
                    ->where('ruang', $validated['ruang'])
                    ->whereDate('tanggal', $validated['tanggal'])
                    ->where('jam_mulai', '<', $validated['jam_selesai'])
                    ->where('jam_selesai', '>', $validated['jam_mulai'])
                    ->exists();

                if ($conflict) {
                    throw ValidationException::withMessages([
                        'jam_mulai' => [
                            'Ruangan sudah dibooking pada waktu tersebut. Silakan pilih jam lain.',
                        ],
                    ]);
                }

                return RoomBooking::create([
                    'ruang' => $validated['ruang'],
                    'tanggal' => $validated['tanggal'],
                    'jam_mulai' => $validated['jam_mulai'],
                    'jam_selesai' => $validated['jam_selesai'],
                    'acara' => $validated['acara'],
                    'pemesan' => $pemesan,
                    'owner_token' => $ownerToken,
                ]);
            });

            return response()->json([
                'message' => 'Booking ruangan berhasil disimpan.',
                'data' => [
                    'id' => $booking->id,
                    'ruang' => $booking->ruang,
                    'tanggal' => $booking->tanggal,
                    'jam_mulai' => $booking->jam_mulai,
                    'jam_selesai' => $booking->jam_selesai,
                    'acara' => $booking->acara,
                    'pemesan' => $booking->pemesan,
                ],
            ], 201);
        } finally {
            DB::select('SELECT RELEASE_LOCK(?)', [$lockName]);
        }
    }

    /**
     * Batalkan booking hanya dari sesi yang membuatnya.
     */
    public function destroy(Request $request, $id)
    {
        $this->ensureBookingLogin($request);

        $booking = RoomBooking::findOrFail($id);
        $sessionToken = $request->session()->get('booking_owner_token');

        if (
            ! is_string($booking->owner_token) ||
            ! is_string($sessionToken) ||
            ! hash_equals($booking->owner_token, $sessionToken)
        ) {
            abort(
                403,
                'Kamu hanya dapat membatalkan booking dari sesi yang membuatnya.'
            );
        }

        $booking->delete();

        return response()->json([
            'message' => 'Booking berhasil dibatalkan.',
        ]);
    }
}