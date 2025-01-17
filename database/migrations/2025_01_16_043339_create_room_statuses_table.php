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
        Schema::create('room_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('tanggal'); // Tanggal status
            $table->string('status'); // Status kamar: booked, occupied, available
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('cascade'); // Relasi ke booking
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade'); // Relasi ke acara
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_statuses');
    }
};
