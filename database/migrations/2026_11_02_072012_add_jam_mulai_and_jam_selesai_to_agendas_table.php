<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('waktu');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });

        // Memindahkan data dari format lama:
        // 09.00 - 10.00 WIB menjadi 09:00:00 dan 10:00:00.
        $agendas = DB::table('agendas')
            ->select('id', 'waktu')
            ->get();

        foreach ($agendas as $agenda) {
            $waktu = trim((string) $agenda->waktu);

            if (preg_match(
                '/(\d{1,2})[.:](\d{2})\s*-\s*(\d{1,2})[.:](\d{2})/',
                $waktu,
                $matches
            )) {
                $jamMulai = sprintf(
                    '%02d:%02d:00',
                    (int) $matches[1],
                    (int) $matches[2]
                );

                $jamSelesai = sprintf(
                    '%02d:%02d:00',
                    (int) $matches[3],
                    (int) $matches[4]
                );

                DB::table('agendas')
                    ->where('id', $agenda->id)
                    ->update([
                        'jam_mulai' => $jamMulai,
                        'jam_selesai' => $jamSelesai,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai']);
        });
    }
};
