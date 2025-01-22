<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('room_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('branch')->nullable();
            $table->string('nama');
            $table->string('tipe');
            $table->string('status');
            $table->integer('kapasitas');
            $table->integer('terisi');
            $table->integer('sisa_bed');
            $table->string('event')->nullable();
            $table->json('tamu');
            $table->date('report_date');
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_reports');
    }
};
