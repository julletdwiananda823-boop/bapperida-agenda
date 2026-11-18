<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('agendas', 'room_booking_id')) {
            return;
        }

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'agendas'
              AND COLUMN_NAME = 'room_booking_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $foreignKey) {
            Schema::table('agendas', function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey->CONSTRAINT_NAME);
            });
        }

        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('room_booking_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('agendas', 'room_booking_id')) {
            Schema::table('agendas', function (Blueprint $table) {
                $table->foreignId('room_booking_id')
                    ->nullable()
                    ->constrained('room_bookings')
                    ->nullOnDelete();
            });
        }
    }
};
