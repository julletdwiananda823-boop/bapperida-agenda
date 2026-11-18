<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Agenda;
use App\Models\ApelPiket;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Menampilkan halaman utama agenda.
     */
/**
 * Menampilkan halaman utama agenda.
 */
public function index()
{
    // Hanya Pimpinan dan Admin yang memiliki akses pengelolaan.
    $user = Auth::user();

    $isManagement = $user
        && in_array($user->role, ['admin', 'pimpinan'], true)
        && $user->is_active;

    $isStaff = session('staff_authenticated') === true;
    $canAccessInternalAttachments = $isManagement || $isStaff;

    $canViewDisposisi = $isManagement || $isStaff;

    // Ambil agenda beserta bidang, lampiran, dan disposisi.
    $agendas = Agenda::with([
        'attachments',
        'bidangs',
        'disposisis.user',
    ])
        ->orderBy('tanggal_kegiatan', 'asc')
        ->orderBy('jam_mulai', 'asc')
        ->get();

    $apels = ApelPiket::orderBy('tanggal', 'asc')
        ->orderBy('id', 'asc')
        ->get()
        ->values()
        ->map(function ($apel, $index) {
            $tanggal = Carbon::parse($apel->tanggal);

            $apel->hari = $tanggal
                ->copy()
                ->locale('id')
                ->translatedFormat('l');

            $apel->no_urut = $index + 1;

            return $apel;
        });

    $formattedAgendas = $agendas
        ->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_kegiatan)->format('Y-m-d');
        })
        ->map(function ($items, $date) use ($isManagement, $canViewDisposisi, $canAccessInternalAttachments) {
            $first = $items->first();

            return [
                'tanggal' => Carbon::parse($date)
                    ->locale('id')
                    ->translatedFormat('d F Y'),

                'hari' => Carbon::parse($date)
                    ->locale('id')
                    ->translatedFormat('l'),

                'isoDate' => $date,
                'acara' => $first->acara,
                'ruang' => $first->ruang,
                'bidang' => $first->bidang,
                'bidangs' => $first->bidangs->pluck('nama')->values()->all(),

                // Disposisi dapat dilihat oleh Admin, Pimpinan, dan Pegawai yang telah login.
                'disposisi' => $canViewDisposisi
                    ? ($first->disposisi ?? '-')
                    : '',

                'subEvents' => $items->map(function ($item) use ($isManagement, $canViewDisposisi, $canAccessInternalAttachments) {
                    $waktuTampilan = $item->waktu;

                    if ($item->jam_mulai && $item->jam_selesai) {
                        $waktuTampilan =
                            Carbon::parse($item->jam_mulai)->format('H.i')
                            . ' - '
                            . Carbon::parse($item->jam_selesai)->format('H.i')
                            . ' WIB';
                    }

                    return [
                        'id' => $item->id,

                        'tanggal_kegiatan' => Carbon::parse(
                            $item->tanggal_kegiatan
                        )->format('Y-m-d'),

                        'tanggal' => Carbon::parse(
                            $item->tanggal_kegiatan
                        )->locale('id')->translatedFormat('d F Y'),

                        'hari' => Carbon::parse($item->tanggal_kegiatan)
                            ->locale('id')
                            ->translatedFormat('l'),

                        'waktu' => $waktuTampilan,
                        'jam_mulai' => $item->jam_mulai,
                        'jam_selesai' => $item->jam_selesai,

                        'acara' => $item->acara,
                        'ruang' => $item->ruang,
                        'bidang' => $item->bidang,
                        'bidangs' => $item->bidangs->pluck('nama')->values()->all(),

                        // Disposisi.
                        'disposisi' => $canViewDisposisi
                            ? ($item->disposisi ?? '-')
                            : '',

                        // Data disposisi baru dari tabel disposisis.
                        'daftar_disposisi' => $canViewDisposisi
                            ? $item->disposisis->map(function ($disposisi) {
                                return [
                                    'id' => $disposisi->id,
                                    'isi' => $disposisi->isi,
                                    'ditujukan_ke' => $disposisi->ditujukan_ke,
                                    'user_id' => $disposisi->user_id,
                                    'nama_pegawai' => $disposisi->user?->name,
                                    'tenggat' => $disposisi->tenggat?->format('Y-m-d'),
                                    'status' => $disposisi->status,
                                ];
                            })->values()->all()
                            : [],

                        // Lampiran Dokumen: Pengunjung umum hanya melihat 'public', sedangkan Pegawai, Admin, dan Pimpinan melihat keduanya.
                        'attachments' => $item->attachments
                            ->filter(function ($attachment) use ($canAccessInternalAttachments) {
                                return $canAccessInternalAttachments || $attachment->access_type === 'public';
                            })
                            ->map(function ($attachment) {
                                return [
                                    'id' => $attachment->id,
                                    'original_name' => $attachment->original_name,
                                    'file_size' => $attachment->file_size,
                                    'access_type' => $attachment->access_type ?? 'public',
                                    'mime_type' => $attachment->mime_type,
                                ];
                            })
                            ->values()
                            ->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();

    return view('welcome', [
        'formattedAgendas' => $formattedAgendas,
        'apels' => $apels,
    ]);
}

    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer',
            'tanggal_kegiatan' => 'required|date',
            'hari' => 'nullable|string|max:30',
            'waktu' => 'required|string|max:100',
            'jam_mulai' => 'nullable|string|max:10',
            'jam_selesai' => 'nullable|string|max:10',
            'acara' => 'required|string',
            'ruang' => 'required|string|max:255',
            'bidang' => 'required|string|max:255',
            'disposisi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data agenda belum lengkap atau tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $tanggal = Carbon::parse($validated['tanggal_kegiatan']);

            $jamMulai = $request->input('jam_mulai');
            $jamSelesai = $request->input('jam_selesai');

            if (empty($jamMulai) || empty($jamSelesai)) {
                if (preg_match('/(\d{1,2})[.:](\d{2})\s*(?:-|–|—|sampai)\s*(\d{1,2})[.:](\d{2})/i', $validated['waktu'], $matches)) {
                    $jamMulai = sprintf('%02d:%02d:00', (int)$matches[1], (int)$matches[2]);
                    $jamSelesai = sprintf('%02d:%02d:00', (int)$matches[3], (int)$matches[4]);
                }
            } else {
                if (preg_match('/^(\d{1,2})[.:](\d{2})/', $jamMulai, $m)) {
                    $jamMulai = sprintf('%02d:%02d:00', (int)$m[1], (int)$m[2]);
                }
                if (preg_match('/^(\d{1,2})[.:](\d{2})/', $jamSelesai, $m)) {
                    $jamSelesai = sprintf('%02d:%02d:00', (int)$m[1], (int)$m[2]);
                }
            }

            $data = [
                'tanggal_kegiatan' => $tanggal->format('Y-m-d'),
                'hari' => !empty($validated['hari'])
                    ? $validated['hari']
                    : $tanggal->copy()->locale('id')->translatedFormat('l'),
                'waktu' => $validated['waktu'],
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'acara' => $validated['acara'],
                'ruang' => $validated['ruang'],
                'bidang' => $validated['bidang'],
                'disposisi' => $validated['disposisi'] ?? '-',
            ];

            if (!empty($validated['id'])) {
                $agenda = Agenda::find($validated['id']);

                if (!$agenda) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Agenda yang ingin diperbarui tidak ditemukan.',
                    ], 404);
                }

                $agenda->update($data);
                $message = 'Agenda berhasil diperbarui!';
            } else {
                $agenda = Agenda::create($data);
                $message = 'Agenda baru berhasil ditambahkan!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $agenda->fresh(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Agenda gagal disimpan. Periksa konfigurasi model dan database.',
            ], 500);
        }
    }

    /**
     * Menghapus agenda.
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::with('attachments')->find($id);

            if (!$agenda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agenda tidak ditemukan.',
                ], 404);
            }

            foreach ($agenda->attachments as $att) {
                if (!empty($att->file_path) && Storage::disk('local')->exists($att->file_path)) {
                    Storage::disk('local')->delete($att->file_path);
                }
                $att->delete();
            }

            Storage::disk('local')->deleteDirectory('agenda-attachments/' . $agenda->id);

            $agenda->delete();

            return response()->json([
                'success' => true,
                'message' => 'Agenda berhasil dihapus!',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Agenda gagal dihapus.',
            ], 500);
        }
    }

    /**
     * Menyimpan atau memperbarui jadwal apel/piket.
     */
    public function saveApel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer',
            'index' => 'nullable|integer',
            'no_urut' => 'nullable|integer|min:1',
            'tanggal' => 'required|date',
            'hari' => 'nullable|string|max:30',
            'pembina' => 'required|string|max:255',
            'pemimpin' => 'required|string|max:255',
            'ket' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data jadwal apel/piket belum lengkap atau tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            $tanggal = Carbon::parse($validated['tanggal']);

            $data = [
                'tanggal' => $tanggal->format('Y-m-d'),
                'hari' => !empty($validated['hari'])
                    ? $validated['hari']
                    : $tanggal->copy()->locale('id')->translatedFormat('l'),
                'pembina' => $validated['pembina'],
                'pemimpin' => $validated['pemimpin'],
                'ket' => $validated['ket'] ?? '-',
            ];

            if (!empty($validated['id'])) {
                $apel = ApelPiket::find($validated['id']);

                if (!$apel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jadwal apel/piket tidak ditemukan.',
                    ], 404);
                }

                if (isset($validated['no_urut'])) {
                    $data['no_urut'] = $validated['no_urut'];
                }

                $apel->update($data);
                $message = 'Jadwal apel/piket berhasil diperbarui!';
            } else {
                if (isset($validated['no_urut'])) {
                    $noUrut = $validated['no_urut'];
                } else {
                    $noUrut = ((int) ApelPiket::max('no_urut')) + 1;
                }

                $data['no_urut'] = $noUrut;

                $apel = ApelPiket::create($data);
                $message = 'Jadwal apel/piket berhasil ditambahkan!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $apel->fresh(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Jadwal apel/piket gagal disimpan. Periksa log Laravel untuk detail.',
            ], 500);
        }
    }

    /**
     * Menghapus jadwal apel/piket.
     */
    public function deleteApel($id)
    {
        try {
            $apel = ApelPiket::find($id);

            if (!$apel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal apel/piket tidak ditemukan.',
                ], 404);
            }

            $apel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal apel/piket berhasil dihapus!',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Jadwal apel/piket gagal dihapus.',
            ], 500);
        }
    }

    /**
     * Mengambil data agenda untuk laporan.
     */
    public function getReportData()
    {
        try {
            $agendas = Agenda::orderBy('tanggal_kegiatan', 'desc')
                ->orderBy('waktu', 'asc')
                ->get()
                ->map(function ($item) {
                    $tanggal = Carbon::parse($item->tanggal_kegiatan);

                    return [
                        'id' => $item->id,
                        'tanggal' => $tanggal
                            ->copy()
                            ->locale('id')
                            ->translatedFormat('d F Y'),
                        'tanggal_kegiatan' => $tanggal->format('Y-m-d'),
                        'hari' => $item->hari,
                        'waktu' => $item->waktu,
                        'acara' => $item->acara,
                        'ruang' => $item->ruang,
                        'bidang' => $item->bidang,
                        'disposisi' => $item->disposisi ?? '-',
                    ];
                })
                ->values();

            return response()->json($agendas);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Data laporan gagal diambil.',
            ], 500);
        }
    }
}
