<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150)->unique();
            $table->string('lokasi', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('ruangans')->insert([
            [
                'nama' => 'Ruang Rapat Batu Dinding Bappeda',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ruang Rapat Linsum Kawai Bappeda',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ruang Rapat Batu Bedil Bappeda',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::table('agendas', function (Blueprint $table) {
            $table->foreignId('ruangan_id')
                ->nullable()
                ->after('ruang')
                ->constrained('ruangans')
                ->nullOnDelete();
        });

        Schema::table('room_bookings', function (Blueprint $table) {
            $table->foreignId('ruangan_id')
                ->nullable()
                ->after('ruang')
                ->constrained('ruangans')
                ->nullOnDelete();
        });

        DB::statement("
            UPDATE agendas a
            INNER JOIN ruangans r ON TRIM(a.ruang) = r.nama
            SET a.ruangan_id = r.id
        ");

        DB::statement("
            UPDATE room_bookings b
            INNER JOIN ruangans r ON TRIM(b.ruang) = r.nama
            SET b.ruangan_id = r.id
        ");
    }

    public function down(): void
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropColumn('ruangan_id');
        });

        Schema::table('agendas', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropColumn('ruangan_id');
        });

        Schema::dropIfExists('ruangans');
    }
};