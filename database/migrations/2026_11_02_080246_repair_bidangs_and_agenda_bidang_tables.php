<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel kosong yang salah nama
        Schema::dropIfExists('bidangs_and_agenda_bidang_tables');

        // Buat tabel master bidang
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        // Buat tabel penghubung agenda dan bidang
        Schema::create('agenda_bidang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agenda_id')
                ->constrained('agendas')
                ->cascadeOnDelete();

            $table->foreignId('bidang_id')
                ->constrained('bidangs')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['agenda_id', 'bidang_id']);
        });

        // Ambil nama bidang yang benar-benar ada di data lama
        $daftarBidang = DB::table('agendas')
            ->pluck('bidang')
            ->flatMap(function ($nilai) {
                return array_filter(
                    array_map('trim', explode(',', $nilai ?? '')),
                    fn ($nama) => $nama !== '' && $nama !== '-'
                );
            })
            ->unique()
            ->values();

        foreach ($daftarBidang as $nama) {
            DB::table('bidangs')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pindahkan relasi dari kolom bidang lama
        $agendas = DB::table('agendas')->select('id', 'bidang')->get();

        foreach ($agendas as $agenda) {
            $namaBidang = array_filter(
                array_map('trim', explode(',', $agenda->bidang ?? '')),
                fn ($nama) => $nama !== '' && $nama !== '-'
            );

            foreach ($namaBidang as $nama) {
                $bidang = DB::table('bidangs')
                    ->where('nama', $nama)
                    ->first();

                if ($bidang) {
                    DB::table('agenda_bidang')->insertOrIgnore([
                        'agenda_id' => $agenda->id,
                        'bidang_id' => $bidang->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_bidang');
        Schema::dropIfExists('bidangs');
    }
};
