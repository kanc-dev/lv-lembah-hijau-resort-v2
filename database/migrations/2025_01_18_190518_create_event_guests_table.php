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
        Schema::create('event_guest', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('booking_id');
            $table->timestamps();

            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_guests');
    }
};
