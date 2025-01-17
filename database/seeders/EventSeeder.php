<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            ['nama_kelas' => 'Leadership Training', 'deskripsi' => 'Pelatihan untuk pemimpin cabang'],
            ['nama_kelas' => 'Team Building', 'deskripsi' => 'Meningkatkan kerjasama tim'],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
