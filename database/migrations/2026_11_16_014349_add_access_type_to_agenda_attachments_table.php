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
        Schema::table('agenda_attachments', function (Blueprint $table) {
            $table->enum('access_type', ['public', 'internal'])
                ->default('public')
                ->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_attachments', function (Blueprint $table) {
            $table->dropColumn('access_type');
        });
    }
};
