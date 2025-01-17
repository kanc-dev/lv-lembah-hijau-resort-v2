<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'event_id' => 1,  // Gunakan event_id yang sesuai
            'jumlah_peserta' => 10,
            'rooms' => json_encode([1, 2]),  // Menyimpan ID kamar dalam format JSON
            'unit_origin_id' => 1,  // ID unit asal, merujuk pada tabel 'branches'
            'unit_destination_id' => 1,  // ID unit tujuan, merujuk pada tabel 'branches'
            'tanggal_rencana_checkin' => '2025-01-20',
            'tanggal_rencana_checkout' => '2025-01-25',
        ]);
    }
}
