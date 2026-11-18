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
     * Pastikan pengguna sudah login melalui PIN booking atau merupakan Admin/Pimpinan aktif.
     */
    private function ensureBookingLogin(Request $request): void
    {
        $user = $request->user();
        if ($user && in_array($user->role, ['admin', 'pimpinan'], true) && $user->is_active) {
            return;
        }

        abort_unless(
            $request->session()->get('booking_authenticated') === true
            && is_string($request->session()->get('booking_pemesan'))
            && is_string($request->session()->get('booking_owner_token')),
            401,
            'Silakan login terlebih dahulu untuk menggunakan booking.'
        );
    }

    /**
     * Ubah format jam HH:mm atau HH:mm:ss menjadi menit.
     */
    private function parseTime(string $time): ?int
    {
        if (!preg_match('/^(\d{2}):(\d{2})(?::\d{2})?$/', trim($time), $m)) {
            return null;
        }

        $hour = (int) $m[1];
        $minute = (int) $m[2];

        if ($hour > 23 || $minute > 59) {
            return null;
        }

        return ($hour * 60) + $minute;
    }

    /**
     * Tampilkan jadwal booking.
     * Token kepemilikan tidak dikirim ke browser.
     */
    public function index(Request $request)
    {
        $query = RoomBooking::query()
            ->select([
                'id',
                'ruang',
                'tanggal',
                'jam_mulai',
                'jam_selesai',
                'acara',
                'pemesan',
            ]);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->query('tanggal'));
        } else {
            $query->whereDate('tanggal', '>=', now()->subDays(7)->toDateString());
        }

        $bookings = $query
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return response()->json(['data' => $bookings]);
    }

    /**
     * Simpan booking dan cegah bentrok dengan booking maupun agenda.
     */
    public function store(Request $request)
    {
        $this->ensureBookingLogin($request);

        $validated = $request->validate([
            'ruang' => [
                'required',
                'string',
                'in:' . implode(',', $this->rooms),
            ],
            'tanggal' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i',
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
            'acara' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $user = $request->user();
        $isAdminOrPimpinan = $user
            && in_array($user->role, ['admin', 'pimpinan'], true)
            && $user->is_active;

        $ownerToken = $request->session()->get('booking_owner_token')
            ?? ($isAdminOrPimpinan ? bin2hex(random_bytes(32)) : null);
        $pemesan = $request->session()->get('booking_pemesan')
            ?? ($isAdminOrPimpinan ? ($user->name ?? 'Admin BAPPERIDA') : null);

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
                $newStart = $this->parseTime($validated['jam_mulai']);
                $newEnd = $this->parseTime($validated['jam_selesai']);

                if (
                    $newStart === null ||
                    $newEnd === null ||
                    $newEnd <= $newStart
                ) {
                    throw ValidationException::withMessages([
                        'jam_mulai' => ['Format jam booking tidak valid.'],
                    ]);
                }

                // Cek bentrok dengan booking lain.
                $existingBookings = RoomBooking::query()
                    ->where('ruang', $validated['ruang'])
                    ->whereDate('tanggal', $validated['tanggal'])
                    ->get(['jam_mulai', 'jam_selesai', 'acara']);

                foreach ($existingBookings as $existing) {
                    $existingStart = $this->parseTime(
                        (string) $existing->jam_mulai
                    );

                    $existingEnd = $this->parseTime(
                        (string) $existing->jam_selesai
                    );

                    if ($existingStart === null || $existingEnd === null) {
                        continue;
                    }

                    if ($newStart < $existingEnd && $newEnd > $existingStart) {
                        throw ValidationException::withMessages([
                            'jam_mulai' => [
                                'Ruangan sudah dibooking untuk acara '
                                . $existing->acara . ' pada jam '
                                . $existing->jam_mulai . '–'
                                . $existing->jam_selesai . '.',
                            ],
                        ]);
                    }
                }

                // Cek bentrok dengan agenda.
                $agendas = DB::table('agendas')
                    ->whereDate('tanggal_kegiatan', $validated['tanggal'])
                    ->where('ruang', $validated['ruang'])
                    ->get([
                        'jam_mulai',
                        'jam_selesai',
                        'waktu',
                        'acara',
                    ]);

                foreach ($agendas as $agenda) {
                    $agendaStart = $this->parseTime(
                        (string) $agenda->jam_mulai
                    );

                    $agendaEnd = $this->parseTime(
                        (string) $agenda->jam_selesai
                    );

                    // Periksa data agenda lama melalui kolom waktu.
                    if ($agendaStart === null || $agendaEnd === null) {
                        if (
                            preg_match(
                                '/(\d{1,2})[.:](\d{2})\s*-\s*(\d{1,2})[.:](\d{2})/',
                                (string) $agenda->waktu,
                                $matches
                            )
                        ) {
                            $agendaStart = ((int) $matches[1] * 60)
                                + (int) $matches[2];

                            $agendaEnd = ((int) $matches[3] * 60)
                                + (int) $matches[4];
                        } else {
                            continue;
                        }
                    }

                    if ($agendaEnd <= $agendaStart) {
                        continue;
                    }

                    if ($newStart < $agendaEnd && $newEnd > $agendaStart) {
                        $waktuAgenda = $agenda->waktu ?: (
                            substr((string) $agenda->jam_mulai, 0, 5)
                            . ' - '
                            . substr((string) $agenda->jam_selesai, 0, 5)
                        );

                        throw ValidationException::withMessages([
                            'jam_mulai' => [
                                'Booking tidak dapat disimpan karena bertabrakan dengan agenda '
                                . $agenda->acara . ' (' . $waktuAgenda . ').',
                            ],
                        ]);
                    }
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
     * Batalkan booking dari sesi yang membuatnya atau oleh Admin/Pimpinan.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $isAdminOrPimpinan = $user
            && in_array($user->role, ['admin', 'pimpinan'], true)
            && $user->is_active;

        if (!$isAdminOrPimpinan) {
            $this->ensureBookingLogin($request);
        }

        $booking = RoomBooking::findOrFail($id);
        $sessionToken = $request->hasSession() ? $request->session()->get('booking_owner_token') : null;

        $isOwner = is_string($booking->owner_token)
            && is_string($sessionToken)
            && hash_equals($booking->owner_token, $sessionToken);

        if (!$isAdminOrPimpinan && !$isOwner) {
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
