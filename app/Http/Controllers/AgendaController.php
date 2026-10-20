<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Agenda;
use App\Models\ApelPiket;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Menampilkan halaman utama agenda.
     */
    public function index()
    {
        $agendas = Agenda::orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('waktu', 'asc')
            ->get();

        $apels = ApelPiket::orderBy('tanggal', 'asc')
            ->orderBy('no_urut', 'asc')
            ->get();

        $formattedAgendas = $agendas
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal_kegiatan)
                    ->format('Y-m-d');
            })
            ->map(function ($items, $date) {
                $first = $items->first();

                return [
                    // Ditambahkan 'Y' agar menampilkan Tahun secara lengkap di tabel
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
                    'disposisi' => $first->disposisi ?? '-',

                    'subEvents' => $items->map(function ($item) {
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
                            'waktu' => $item->waktu,
                            'acara' => $item->acara,
                            'ruang' => $item->ruang,
                            'bidang' => $item->bidang,
                            'disposisi' => $item->disposisi ?? '-',
                        ];
                    })->values()->all(),
                ];
            })->values()->all();

        return view('welcome', [
            'formattedAgendas' => $formattedAgendas,
            'apels' => $apels,
        ]);
    }

    /**
     * Menyimpan agenda baru atau memperbarui agenda.
     */
    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|integer',
            'tanggal_kegiatan' => 'required|date',
            'hari' => 'nullable|string|max:30',
            'waktu' => 'required|string|max:100',
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

            $data = [
                'tanggal_kegiatan' => $tanggal->format('Y-m-d'),
                'hari' => !empty($validated['hari'])
                    ? $validated['hari']
                    : $tanggal->copy()->locale('id')->translatedFormat('l'),
                'waktu' => $validated['waktu'],
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
            $agenda = Agenda::find($id);

            if (!$agenda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Agenda tidak ditemukan.',
                ], 404);
            }

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