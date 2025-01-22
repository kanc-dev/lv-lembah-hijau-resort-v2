<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\RoomReport;
use Illuminate\Console\Command;

class GenerateRoomReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:room-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rooms = Room::with(['branch', 'event', 'guestCheckins.guest'])->get();

        $reports = $rooms->map(function ($room) {
            $activeCheckins = $room->guestCheckins->filter(function ($checkin) {
                return is_null($checkin->tanggal_checkout);
            });

            return [
                'room_id' => $room->id,
                'branch_id' => $room->branch_id,
                'event_id' => $room->event_id,
                'branch' => $room->branch->name ?? 'N/A',
                'nama' => $room->nama,
                'tipe' => $room->tipe,
                'status' => $room->status,
                'kapasitas' => $room->kapasitas,
                'terisi' => $activeCheckins->count(),
                'sisa_bed' => $room->kapasitas - $activeCheckins->count(),
                'event' => $room->event->nama_kelas ?? 'N/A',
                'tamu' => $activeCheckins->map(function ($checkin) {
                    return [
                        'nama' => $checkin->guest->nama,
                        'checkin' => $checkin->tanggal_checkin,
                        'checkout' => $checkin->tanggal_checkout,
                    ];
                })->values()->toJson(),
                'report_date' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        RoomReport::insert($reports->toArray());

        $this->info('Room status has been saved to room_reports table.');
    }
}
