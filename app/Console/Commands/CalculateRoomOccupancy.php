<?php

namespace App\Console\Commands;

use App\Models\GuestCheckin;
use App\Models\Room;
use App\Models\RoomOccupancyHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateRoomOccupancy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:room-occupancy';

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
        $branches = Room::select('branch_id')->distinct()->get();

        foreach ($branches as $branch) {
            $branchId = $branch->branch_id;

            $totalRooms = Room::where('branch_id', $branchId)->count();
            $totalCapacity = Room::where('branch_id', $branchId)->sum('kapasitas');

            $occupiedCapacity = GuestCheckin::where('tanggal_checkin', '<=', Carbon::today())
                ->where(function ($query) {
                    $query->whereNull('tanggal_checkout')
                        ->orWhere('tanggal_checkout', '>=', Carbon::today());
                })
                ->whereHas('room', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })
                ->count();

            $occupancyPercentage = $totalCapacity > 0
                ? ($occupiedCapacity / $totalCapacity) * 100
                : 0;

            $todayOccupancy = RoomOccupancyHistory::where('branch_id', $branchId)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($todayOccupancy) {
                $todayOccupancy->update([
                    'total_rooms' => $totalRooms,
                    'total_capacity' => $totalCapacity,
                    'occupied_capacity' => $occupiedCapacity,
                    'available_capacity' => max($totalCapacity - $occupiedCapacity, 0),
                    'occupancy_percentage' => round($occupancyPercentage, 2),
                ]);
            } else {
                RoomOccupancyHistory::create([
                    'branch_id' => $branchId,
                    'tanggal' => Carbon::today(),
                    'total_rooms' => $totalRooms,
                    'total_capacity' => $totalCapacity,
                    'occupied_capacity' => $occupiedCapacity,
                    'available_capacity' => max($totalCapacity - $occupiedCapacity, 0),
                    'occupancy_percentage' => round($occupancyPercentage, 2),
                ]);
            }
        }

        $this->info('Room occupancy history calculated and saved successfully.');
    }
}
