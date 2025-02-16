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
        $branches = [1, 2];
        $roomTypes = ['A', 'B', 'C'];
        $basePrices = ['A' => 500000, 'B' => 800000, 'C' => 1200000];

        foreach ($branches as $branchId) {
            for ($i = 1; $i <= 10; $i++) {
                $roomType = $roomTypes[array_rand($roomTypes)];
                $room = [
                    'branch_id' => $branchId,
                    'nama' => "Room {$branchId}0{$i}",
                    'tipe' => $roomType,
                    'harga' => 0,
                    'status' => 'available',
                    'kapasitas' => rand(2, 3),
                ];

                Room::create($room);
            }
        }
    }
}
