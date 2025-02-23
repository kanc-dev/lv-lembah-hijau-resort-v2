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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->integer('jumlah_peserta');
            $table->json('rooms')->nullable();
            $table->unsignedBigInteger('unit_origin_id');
            $table->unsignedBigInteger('unit_destination_id');
            $table->dateTime('tanggal_rencana_checkin');
            $table->dateTime('tanggal_rencana_checkout');
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('unit_origin_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('unit_destination_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
