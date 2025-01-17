<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guests = [
            [
                'nama' => 'John Doe',
                'jenis_kelamin' => 'L',
                'branch_id' => Branch::where('name', 'Bandung')->first()->id,
                'batch' => '1',
                'kendaraan' => 'Mobil',
                'no_polisi' => 'D 1234 ABC',
                'no_hp' => '08123456789',
                'email' => 'johndoe@example.com',
                'tanggal_rencana_checkin' => '2025-01-20',
                'tanggal_rencana_checkout' => '2025-01-25',
                'tanggal_checkin' => null,
                'tanggal_checkout' => null,
                'room_id' => Room::first()->id,
                'event_id' => Event::first()->id,
            ],
        ];

        foreach ($guests as $guest) {
            Guest::create($guest);
        }
    }
}
