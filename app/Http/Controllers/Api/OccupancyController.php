<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OccupancyResource;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\GuestCheckin;
use App\Models\Room;
use App\Models\RoomOccupancyHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OccupancyController extends Controller
{
    private function _getRoomStatus($branchId)
    {
        $getRoomStatus = Room::with(['branch', 'event', 'guestCheckins.guest'])
            ->when($branchId, function ($query, $branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->get() // Tambahkan pagination
            ->map(function ($room) { // Gunakan through() untuk mapping setelah pagination
                $pendingCheckins = $room->guestCheckins->filter(function ($checkin) {
                    return is_null($checkin->tanggal_checkout);
                });

                $activeCheckins = $room->guestCheckins->filter(function ($checkin) {
                    return !is_null($checkin->tanggal_checkin) && is_null($checkin->tanggal_checkout);
                });

                return [
                    'id' => $room->id,
                    'branch' => $room->branch->name ?? 'N/A',
                    'nama' => $room->nama,
                    'tipe' => $room->tipe,
                    'status' => $room->status,
                    'kapasitas' => $room->kapasitas,
                    'terisi' => $activeCheckins->count(),
                    'sisa_bed' => $room->kapasitas - $activeCheckins->count(),
                    'event' => $room->event->nama_kelas ?? null,
                    'events' => $pendingCheckins->map(function ($checkin) {
                        return $checkin->guest->events ?? [];
                    }),
                    'tamu' => $pendingCheckins->map(function ($checkin) {
                        return [
                            'nama' => $checkin->guest ? $checkin->guest->nama : 'N/A',
                            'checkin' => $checkin->tanggal_checkin,
                            'checkout' => $checkin->tanggal_checkout,
                        ];
                    }),
                    'total_tamu' => $pendingCheckins->count(),
                    'total_tamu_checkin' => $activeCheckins->count()
                ];
            });

        return $getRoomStatus;
    }

    private function _getRoomStatusOccupancy($branchId)
    {
        $roomReports = $this->_getRoomStatus($branchId);

        $totalRooms = $roomReports->count();
        $totalOccupied = $roomReports->sum('terisi');
        $totalCapacity = $roomReports->sum('kapasitas');
        $totalEmpty = $totalCapacity - $totalOccupied;

        $percentageOccupied = $totalCapacity > 0 ? ($totalOccupied / $totalCapacity) * 100 : 0;
        $percentageEmpty = $totalCapacity > 0 ? ($totalEmpty / $totalCapacity) * 100 : 0;

        $data = [
            'total_rooms' => $totalRooms,
            'total_occupied' => $totalOccupied,
            'total_empty' => $totalEmpty,
            'percentage_occupied' => $percentageOccupied,
            'percentage_empty' => $percentageEmpty,
        ];

        return $data;
    }

    public function getRoomEmptyOccupied(Request $request)
    {
        $branchId = $request->input('branch_id');

        $occupancyData = $this->_getRoomStatusOccupancy($branchId);

        $data = [
            'occupied' => round($occupancyData['percentage_occupied'], 2),
            'empty' => round($occupancyData['percentage_empty'], 2),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    private function _getRoomOccupancy($branchId = null)
    {
        $query = DB::table('rooms')
            ->leftJoin('guest_checkins', 'rooms.id', '=', 'guest_checkins.room_id')
            ->select(
                DB::raw('COUNT(guest_checkins.id) as total_guests'),
                'rooms.id',
                'rooms.branch_id', // Kolom lain yang ingin Anda tampilkan
                'rooms.nama',
                'rooms.kapasitas', // Kolom lainnya yang ingin Anda tampilkan
                'rooms.status'
            )
            ->where('rooms.status', 'available')
            ->groupBy('rooms.id', 'rooms.branch_id', 'rooms.nama', 'rooms.kapasitas', 'rooms.status') // Semua kolom rooms yang digunakan di SELECT
            ->get();


        $data = [];
        if ($branchId) {
            $data['total_rooms'] = $query->where('branch_id', $branchId)->count();
            $data['total_capacity'] = $query->where('branch_id', $branchId)->sum('kapasitas');
            $data['total_guests'] = $query->where('branch_id', $branchId)->sum('total_guests');
        } else {
            $data['total_rooms'] = $query->count();
            $data['total_capacity'] = $query->sum('kapasitas');
            $data['total_guests'] = $query->sum('total_guests');
        }


        $totalRooms =  $data['total_rooms'] ?? 0;
        $totalCapacity = $data['total_capacity'] ?? 0;
        $totalGuests = $data['total_guests'] ?? 0;

        $occupiedPercentage = $totalCapacity > 0 ? ($totalGuests / $totalCapacity) * 100 : 0;
        $emptyPercentage = 100 - $occupiedPercentage;

        return [
            'total_rooms' => $totalRooms,
            'total_capacity' => $totalCapacity,
            'total_guests' => $totalGuests,
            'occupied_percentage' => $occupiedPercentage,
            'empty_percentage' => $emptyPercentage
        ];
    }

    public function _getRoomEmptyOccupied(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (!$branchId) {
            // Admin: ambil data untuk semua cabang
            $branches = Branch::all();
        } else {
            // Pengguna memiliki cabang tertentu
            $branches = Branch::where('id', $branchId)->get();
        }

        // Akumulasi total kamar dan kamar terisi
        $totalRooms = 0;
        $occupiedRooms = 0;

        foreach ($branches as $branch) {
            $totalRooms += $branch->rooms->count();
            $occupiedRooms += $branch->rooms->filter(function ($room) {
                return $room->guestCheckins()->where('tanggal_checkout', '>=', now()->format('Y-m-d'))->exists();
            })->count();
        }

        // Hitung kamar kosong
        $emptyRooms = $totalRooms - $occupiedRooms;

        // Hitung persentase terisi dan kosong
        $occupiedPercentage = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
        $emptyPercentage = 100 - $occupiedPercentage;

        $data = [
            'occupied' => $occupiedPercentage,
            'empty' => $emptyPercentage
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getRoomHistory(Request $request)
    {
        $branchId = $request->input('branch_id');
        if ($branchId) {
            $branches = Branch::where('id', $branchId)->get();
            $guestCheckins = GuestCheckin::with('room.branch')
                ->whereNotNull('tanggal_checkin')
                ->whereHas('room', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })
                ->get();
        } else {
            $branches = Branch::all();
            $guestCheckins = GuestCheckin::with('room.branch')
                ->whereNotNull('tanggal_checkin')
                ->get();
        }

        Log::info('Guest Checkins:', $guestCheckins->toArray());

        if ($guestCheckins->isEmpty()) {
            return response()->json(['message' => 'No guest check-ins found']);
        }

        $startDate = $guestCheckins->min('tanggal_checkin');
        $endDate = max(now()->format('Y-m-d'), $guestCheckins->max('tanggal_checkout'));

        Log::info('Start Date: ' . $startDate);
        Log::info('End Date: ' . $endDate);

        $categories = [];
        $currentDate = \Carbon\Carbon::parse($startDate);

        while ($currentDate->lte($endDate)) {
            $categories[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        Log::info('Categories: ', $categories);

        $chartData = [
            'categories' => $categories,
            'series' => []
        ];

        foreach ($branches as $branch) {
            $branchSeriesData = array_fill(0, count($categories), 0);

            $branchRooms = Room::where('branch_id', $branch->id)->get();

            Log::info('Branch ' . $branch->name . ' Rooms:', $branchRooms->toArray());

            foreach ($guestCheckins as $guestCheckin) {
                if ($guestCheckin->room->branch_id !== $branch->id) {
                    continue;
                }

                $checkinDate = \Carbon\Carbon::parse($guestCheckin->tanggal_checkin);
                $checkoutDate = $guestCheckin->tanggal_checkout
                    ? \Carbon\Carbon::parse($guestCheckin->tanggal_checkout)
                    : now();

                while ($checkinDate->lte($checkoutDate)) {
                    $dateIndex = array_search($checkinDate->format('Y-m-d'), $categories);

                    if ($dateIndex !== false) {
                        $branchSeriesData[$dateIndex]++;
                    }

                    $checkinDate->addDay();
                }
            }

            $chartData['series'][] = [
                'name' => $branch->name,
                'data' => $branchSeriesData
            ];
        }

        return response()->json($chartData);
    }

    public function getEventSchedule(Request $request)
    {
        $branchId = $request->input('branch_id');

        // Jika user memiliki branch_id, ambil data untuk branch tersebut saja
        if ($branchId) {
            $branchOrigin = Branch::with(['originBookings.destinationBranch'])
                ->where('id', $branchId)
                ->get();

            $bookings = Booking::with('event', 'originBranch', 'destinationBranch')
                ->whereHas('originBranch', function ($query) use ($branchId) {
                    $query->where('id', $branchId);
                })
                ->get();
        } else {
            // Jika tidak ada branch_id pada user, ambil semua branch
            $branchOrigin = Branch::with(['originBookings.destinationBranch'])->get();

            $bookings = Booking::with('event', 'originBranch', 'destinationBranch')
                ->get();
        }

        $startDate = $bookings->min('tanggal_rencana_checkin');
        $endDate = $bookings->max('tanggal_rencana_checkin');

        // Filter booking berdasarkan tanggal antara startDate dan endDate
        $bookings = Booking::with('event', 'originBranch', 'destinationBranch')
            ->whereBetween('tanggal_rencana_checkin', [$startDate, $endDate])
            ->get();

        $result = $branchOrigin->map(function ($branch) use ($bookings, $startDate, $endDate) {
            $chartData = [];

            foreach ($branch->originBookings as $event) {
                $eventStartDate = new \Carbon\Carbon($event->tanggal_rencana_checkin);
                $eventEndDate = new \Carbon\Carbon($event->tanggal_rencana_checkout);

                $chartData[] = [
                    'x' => $event->destinationBranch->name,
                    'y' => [
                        $eventStartDate->getTimestamp() * 1000,
                        $eventEndDate->getTimestamp() * 1000
                    ],
                    'event_name' => $event->event->nama_kelas
                ];
            }

            return [
                'name' => $branch->name,
                'data' => $chartData
            ];
        });

        return response()->json([
            'series' => $result->values()
        ]);
    }

    public function getCalendarData(Request $request)
    {
        $branchId = $request->branch_id ?? '';
        $query = RoomOccupancyHistory::with('branch');



        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $occupancyHistories = $query->get();

        $branchColors = [
            1 => '#FFCCCB',
            2 => '#CCFFCC',
            3 => '#CCE5FF',
            4 => '#FFFFCC',
            5 => '#FFCCE5',
        ];

        // Format data
        $formattedData = $occupancyHistories->map(function ($history) use ($branchColors, $branchId) {
            $branch_id = $history->branch->id;
            $color = $branchColors[$branch_id] ?? '#CCCCCC';

            $title = '';
            if (!$branchId) {
                $title = substr($history->branch->name, 0, 1) . " - " . $history->occupancy_percentage . "%";
            } else {
                $title = $history->occupancy_percentage . "%";
            }



            return [
                'id' => $history->id,
                'start' => Carbon::parse($history->tanggal)->format('Y-m-d'),
                'title' => $title,
                'color' => $color,
                'textColor' => '#333333',
                'description' => "Total Rooms: {$history->total_rooms}, Capacity: {$history->total_capacity}, Occupied: {$history->occupied_capacity}, Available: {$history->available_capacity}",
            ];
        });

        return new OccupancyResource(true, 'Success', $formattedData);
    }
}
