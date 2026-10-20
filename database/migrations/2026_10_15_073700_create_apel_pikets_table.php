<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apel_pikets', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut');
            $table->string('tanggal');
            $table->string('hari')->nullable();
            $table->string('pembina');
            $table->string('pemimpin');
            $table->string('ket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apel_pikets');
    }
};