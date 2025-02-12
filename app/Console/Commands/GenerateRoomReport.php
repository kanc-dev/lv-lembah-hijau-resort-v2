<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\RoomReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();
        $branchId = $user->branch_id ?? null;


        $query = Room::with(['branch', 'event', 'guestCheckins.guest']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
            $this->info("Generating reports for branch ID: $branchId");
        } else {
            $this->info("Generating reports for all branches");
        }

        $rooms = $query->get();

        if ($rooms->isEmpty()) {
            $this->info('No rooms found for the given criteria.');
            return;
        }


        $rooms->map(function ($room) {
            $activeCheckins = $room->guestCheckins->filter(function ($checkin) {
                return is_null($checkin->tanggal_checkout);
            });

            $reportData = [
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
                'total_tamu' => $activeCheckins->count(),
                'total_tamu_checkin' => $activeCheckins->whereNotNull('tanggal_checkin')->count(),
                'report_date' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $existingReport = RoomReport::where('room_id', $room->id)
                ->where('report_date', $reportData['report_date'])
                ->first();

            if ($existingReport) {
                $existingReport->update($reportData);
                $this->info('Report updated for room ID ' . $room->id);
            } else {
                RoomReport::create($reportData);
                $this->info('New report added for room ID ' . $room->id);
            }
        });
        $this->info('Room reports generated successfully.');
    }
}
