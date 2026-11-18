<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agenda_id')
                ->constrained('agendas')
                ->cascadeOnDelete();

            $table->text('isi')->nullable();
            $table->string('ditujukan_ke', 255)->nullable();
            $table->date('tenggat')->nullable();

            $table->string('status', 50)->default('belum_diproses');

            $table->timestamps();

            $table->index('status');
            $table->index('tenggat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};