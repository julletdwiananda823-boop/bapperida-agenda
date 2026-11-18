<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->foreignId('room_booking_id')
                ->nullable()
                ->after('id')
                ->constrained('room_bookings')
                ->nullOnDelete()
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropForeign(['room_booking_id']);
            $table->dropUnique(['room_booking_id']);
            $table->dropColumn('room_booking_id');
        });
    }
};
