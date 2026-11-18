<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Siapa yang melakukan aksi
            $table->string('user_name', 150)->nullable();

            // Jenis aksi
            $table->string('action', 50);

            // Jenis data
            $table->string('module', 50);

            // ID data yang berubah
            $table->unsignedBigInteger('record_id')->nullable();

            // Ringkasan aktivitas
            $table->string('description');

            // Detail perubahan (JSON)
            $table->json('changes')->nullable();

            $table->timestamps();

            $table->index(['module', 'record_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};