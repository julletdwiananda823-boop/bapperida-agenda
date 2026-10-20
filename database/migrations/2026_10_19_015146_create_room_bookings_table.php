<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('ruang', 150);
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('acara', 255);

            // ID pegawai pemesan; nullable sementara jika
            // sistem login pegawai belum memakai tabel users Laravel.
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();

            $table->index(['ruang', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
