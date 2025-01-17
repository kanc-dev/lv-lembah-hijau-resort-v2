<?php

namespace Database\Seeders;

use App\Models\RoomStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomStatus::create([
            'room_id' => 1,
            'tanggal' => '2025-01-20',
            'status' => 'booked',
            'booking_id' => 1,
            'event_id' => 1,
        ]);
    }
}
