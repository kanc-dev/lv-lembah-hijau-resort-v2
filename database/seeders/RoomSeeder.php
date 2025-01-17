<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['branch_id' => 1,  'nama' => 'Room 101', 'tipe' => 'Standard', 'harga' => '500000', 'status' => 'available', 'kapasitas' => 2],
            ['branch_id' => 1,  'nama' => 'Room 102', 'tipe' => 'Standard', 'harga' => '500000', 'status' => 'available', 'kapasitas' => 3],
            ['branch_id' => 2,  'nama' => 'Room 201', 'tipe' => 'Deluxe', 'harga' => '800000', 'status' => 'available', 'kapasitas' => 2],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
