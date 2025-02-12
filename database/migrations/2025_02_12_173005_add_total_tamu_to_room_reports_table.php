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
        Schema::table('room_reports', function (Blueprint $table) {
            $table->integer('total_tamu')->nullable();
            $table->integer('total_tamu_checkin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_reports', function (Blueprint $table) {
            $table->dropColumn('total_tamu');
            $table->dropColumn('total_tamu_checkin');
        });
    }
};
