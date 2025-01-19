<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::now()->startOfDay();

        $user = Auth::user();
        $branchId = $user->branch_id;


        if ($branchId) {
            $data['is_branch'] = true;
            $data['is_admin'] = false;
            $branches = Branch::where('id', $branchId)->get();
        } else {
            $data['is_branch'] = false;
            $data['is_admin'] = true;
            $branches = Branch::all();
        }

        // Ambil data terkait cabang atau data akumulasi
        $branchesWithOccupancy = $branches->map(function ($branch) {
            $totalRooms = $branch->rooms->count();
            $occupiedRooms = $branch->rooms->filter(function ($room) {
                return $room->guestCheckins()->where('tanggal_checkout', '>=', now()->format('Y-m-d'))->exists();
            })->count();
            return [
                'branch' => $branch,
                'occupancy' => [
                    'total' => $totalRooms,
                    'occupied' => $occupiedRooms,
                    'empty' => $totalRooms - $occupiedRooms
                ]
            ];
        });

        // Akumulasi data untuk semua cabang jika admin
        if ($data['is_admin']) {
            $totalRooms = $branchesWithOccupancy->sum(function ($branchData) {
                return $branchData['occupancy']['total'];
            });
            $occupiedRooms = $branchesWithOccupancy->sum(function ($branchData) {
                return $branchData['occupancy']['occupied'];
            });
            $emptyRooms = $totalRooms - $occupiedRooms;
            $totalGuests = $branchesWithOccupancy->sum(function ($branchData) {
                return $branchData['branch']->rooms->sum(function ($room) {
                    return $room->guestCheckins()->count();
                });
            });
        } else {
            // Jika pengguna bukan admin, ambil data untuk cabang yang dipilih saja
            $branchData = $branchesWithOccupancy->first();
            $totalRooms = $branchData['occupancy']['total'];
            $occupiedRooms = $branchData['occupancy']['occupied'];
            $emptyRooms = $branchData['occupancy']['empty'];
            $totalGuests = $branchData['branch']->rooms->sum(function ($room) {
                return $room->guestCheckins()->count();
            });
        }

        $bookings = Booking::all();
        if ($data['is_branch']) {
            $data['bookings'] = $bookings->where('origin_branch_id', $branchId);
        } else {
            $data['bookings'] = $bookings;
        }

        $events = Event::all();
        if ($data['is_branch']) {
            $data['events'] = $events->where('branch_id', $branchId);
        } else {
            $data['events'] = $events;
        }

        $guests = Guest::all();
        if ($data['is_branch']) {
            $data['guests'] = $guests->where('branch_id', $branchId);
        } else {
            $data['guests'] = $guests;
        }

        $rooms = Room::all();
        if ($data['is_branch']) {
            $data['rooms'] = $rooms->where('branch_id', $branchId);
        } else {
            $data['rooms'] = $rooms;
        }


        $data['occupancy_of_branch'] = $this->_getBranchesWithOccupancy();
        $data['total_branch_active'] = $branchesWithOccupancy->count();

        $data['total_booking_active'] =  $data['bookings']->count();
        $data['total_event_active'] = $data['events']->count();
        $data['total_guest_active'] = $this->_getRoomOccupancy($branchId)['total_guests'];
        $data['total_room_active'] = $this->_getRoomOccupancy($branchId)['total_rooms'];
        $data['total_room_capacity'] = $this->_getRoomOccupancy($branchId)['total_capacity'];
        $data['total_room_occupied'] = $this->_getRoomOccupancy($branchId)['occupied_percentage'];
        $data['total_room_empty'] = $this->_getRoomOccupancy($branchId)['empty_percentage'];

        $data['event_booking'] = $this->_getUpcomingBookings($branchId);

        $data['branch_occupancy'] = $branchesWithOccupancy;
        $data['branches'] = $branchesWithOccupancy;
        $data['branchs'] = $branches;
        $data['total_rooms'] = $totalRooms;
        $data['occupied_rooms'] = $occupiedRooms;
        $data['empty_rooms'] = $emptyRooms;
        $data['total_guests'] = $totalGuests;
        $data['bookings'] = Booking::all();
        $data['events'] = Event::all();
        $data['guests_of_branch'] = $this->_getGuestOfBranch();

        // dd($data);
        // dd($data['occupancy_of_branch']);

        $data['page_title'] = 'Dashboard';
        return view('pages.home.index', compact('data', 'branches',));
    }


    private function _getBranchesWithOccupancy()
    {
        $today = now()->format('Y-m-d');

        $branches = Branch::with(['rooms' => function ($query) {
            $query->select('id', 'branch_id', 'status');
        }])->get();

        $branchesWithOccupancy = $branches->map(function ($branch) use ($today) {
            $totalRooms = $branch->rooms->count();
            $occupiedRooms = $branch->rooms->filter(function ($room) use ($today) {
                return $room->guestCheckins()->where(function ($query) use ($today) {
                    $query->where('tanggal_checkin', '<=', $today)
                        ->where(function ($query) use ($today) {
                            $query->where('tanggal_checkout', '>=', $today)
                                ->orWhereNull('tanggal_checkout');
                        });
                })->exists();
            })->count();

            $emptyRooms = $totalRooms - $occupiedRooms;

            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'created_at' => $branch->created_at,
                'updated_at' => $branch->updated_at,
                'occupancy' => [
                    'occupied' => $occupiedRooms,
                    'empty' => $emptyRooms,
                    'total' => $totalRooms,
                    'percentage_occupied' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0,
                    'percentage_empty' => $totalRooms > 0 ? round(($emptyRooms / $totalRooms) * 100, 2) : 0,
                    'percentage_total' => 100,
                ],
            ];
        });

        return $branchesWithOccupancy;
    }


    private function _getRoomOccupancy($branchId = null)
    {
        $query = DB::table('rooms')
            ->leftJoin('guest_checkins', 'rooms.id', '=', 'guest_checkins.room_id')
            ->selectRaw(
                'COUNT(DISTINCT rooms.id) as total_rooms,
             SUM(rooms.kapasitas) as total_capacity,
             COUNT(guest_checkins.id) as total_guests'
            );

        if ($branchId) {
            $query->where('rooms.branch_id', $branchId);
        }

        $data = $query->first();

        $totalRooms = $data->total_rooms ?? 0;
        $totalCapacity = $data->total_capacity ?? 0;
        $totalGuests = $data->total_guests ?? 0;

        $occupiedPercentage = $totalCapacity > 0 ? ($totalGuests / $totalCapacity) * 100 : 0;
        $emptyPercentage = 100 - $occupiedPercentage;

        return $data = [
            'total_rooms' => $totalRooms,
            'total_capacity' => $totalCapacity,
            'total_guests' => $totalGuests,
            'occupied_percentage' => $occupiedPercentage,
            'empty_percentage' => $emptyPercentage
        ];
    }


    private function _getUpcomingBookings($branchId = null)
    {
        $today = Carbon::now()->startOfDay();

        $query = Booking::with(['event', 'originBranch', 'destinationBranch'])
            ->where('tanggal_rencana_checkin', '>=', $today)
            ->orderBy('tanggal_rencana_checkin', 'asc');

        if ($branchId) {
            $query->where('unit_origin_id', $branchId);
        }

        $bookings = $query->limit(5)->get();
        return $bookings;
    }



    private function _showOccupancy()
    {
        $today = now()->format('Y-m-d'); // Mendapatkan tanggal hari ini
        $yesterday = now()->subDay()->format('Y-m-d'); // Mendapatkan tanggal hari sebelumnya

        // Ambil semua cabang dengan data kamar dan guest_checkins
        $branches = Branch::with(['rooms' => function ($query) {
            $query->select('id', 'branch_id', 'status'); // Ambil status kamar
        }])
            ->get();

        $branchesWithOccupancy = $branches->map(function ($branch) use ($today, $yesterday) {
            // Hitung total kamar dan kamar terisi pada hari ini
            $totalRooms = $branch->rooms->count();
            $occupiedRoomsToday = $branch->rooms->filter(function ($room) use ($today) {
                return $room->guestCheckins()->where(function ($query) use ($today) {
                    $query->where('tanggal_checkin', '<=', $today)
                        ->where(function ($query) use ($today) {
                            $query->where('tanggal_checkout', '>=', $today)
                                ->orWhereNull('tanggal_checkout');
                        });
                })->exists();
            })->count();

            // Hitung kamar terisi pada hari sebelumnya
            $occupiedRoomsYesterday = $branch->rooms->filter(function ($room) use ($yesterday) {
                return $room->guestCheckins()->where(function ($query) use ($yesterday) {
                    $query->where('tanggal_checkin', '<=', $yesterday)
                        ->where(function ($query) use ($yesterday) {
                            $query->where('tanggal_checkout', '>=', $yesterday)
                                ->orWhereNull('tanggal_checkout');
                        });
                })->exists();
            })->count();

            // Menghitung perbandingan persentase okupansi hari ini dan kemarin
            $occupancyPercentageToday = $totalRooms > 0 ? ($occupiedRoomsToday / $totalRooms) * 100 : 0;
            $occupancyPercentageYesterday = $totalRooms > 0 ? ($occupiedRoomsYesterday / $totalRooms) * 100 : 0;

            // Menghitung selisih persentase
            $percentageDifference = $occupancyPercentageToday - $occupancyPercentageYesterday;

            // Menyimpan data yang akan dikirim ke view
            $branch->occupancy = [
                'occupied' => $occupiedRoomsToday,
                'total' => $totalRooms,
                'percentage_today' => $occupancyPercentageToday,
                'percentage_difference' => $percentageDifference,  // Selisih persentase hari ini vs hari kemarin
                'yesterday' => $yesterday,  // Menambahkan tanggal sebelumnya
            ];

            return $branch;
        });

        $data = $branchesWithOccupancy;

        return $data;
    }



    private function _getGuestOfBranch()
    {
        $data = Branch::withCount('guests')->get();
        return $data;
    }


    public function __getBranchGuestData(Request $request)
    {
        $data = Guest::whereNotNull('tanggal_checkin') // Mengambil tamu yang sudah checkin
            ->where('tanggal_checkin', '<=', now()) // Hanya tamu yang checkin hingga hari ini
            ->where(function ($query) {
                $query->whereNull('tanggal_checkout')
                    ->orWhere('tanggal_checkout', '>=', now()); // Menambahkan tamu yang belum checkout
            })
            ->get()
            ->groupBy('branch_id'); // Mengelompokkan berdasarkan branch_id

        // Inisialisasi array untuk menyimpan data chart
        $chartData = [
            'categories' => [],
            'series' => []
        ];

        // Loop setiap branch_id untuk menyiapkan series di chart
        foreach ($data as $branchId => $guests) {
            $branchName = $guests->first()->branch->name; // Nama branch berdasarkan data pertama di setiap kelompok

            $seriesData = [];
            $dates = collect(); // Menyimpan tanggal yang terlibat

            // Menyiapkan data untuk series berdasarkan tanggal check-in tamu
            foreach ($guests as $guest) {
                $checkinDate = Carbon::parse($guest->tanggal_checkin);
                $checkoutDate = $guest->tanggal_checkout ? Carbon::parse($guest->tanggal_checkout) : Carbon::now();

                // Menambahkan tanggal ke dalam list
                while ($checkinDate <= $checkoutDate) {
                    $dates->push($checkinDate->format('Y-m-d'));
                    $checkinDate->addDay();
                }
            }

            // Menghapus duplikat tanggal dan mengurutkan
            $dates = $dates->unique()->sort();

            // Memasukkan tanggal ke kategori chart
            $chartData['categories'] = $dates->values()->toArray();

            // Menghitung jumlah tamu untuk setiap tanggal dan memasukkannya ke dalam series
            foreach ($chartData['categories'] as $date) {
                $count = 0;
                foreach ($guests as $guest) {
                    $checkinDate = Carbon::parse($guest->tanggal_checkin);
                    $checkoutDate = $guest->tanggal_checkout ? Carbon::parse($guest->tanggal_checkout) : Carbon::now();

                    // Menambah tamu jika tanggal berada dalam rentang check-in dan check-out
                    if ($checkinDate <= Carbon::parse($date) && $checkoutDate >= Carbon::parse($date)) {
                        $count++;
                    }
                }
                $seriesData[] = $count;
            }

            // Menambahkan series untuk branch ke chartData
            $chartData['series'][] = [
                'name' => $branchName,
                'data' => $seriesData
            ];
        }

        return response()->json($chartData);
    }

    public function getBranchGuestData(Request $request)
    {
        // Ambil data branch
        $branches = Branch::pluck('name'); // Semua branch
        $guestCheckins = GuestCheckin::with('guest.branch')
            ->whereNotNull('tanggal_checkin')
            ->get();

        // Tentukan range tanggal
        $startDate = $guestCheckins->min('tanggal_checkin') ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->addDays(3)->format('Y-m-d'); // Tambahkan beberapa hari ke range tanggal

        // Buat array kategori tanggal
        $categories = [];
        $currentDate = \Carbon\Carbon::parse($startDate);

        while ($currentDate->lte($endDate)) {
            $categories[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Siapkan data chart
        $chartData = [
            'categories' => $categories,
            'series' => [],
        ];

        // Siapkan data kosong untuk setiap branch
        foreach ($branches as $branch) {
            $chartData['series'][] = [
                'name' => $branch,
                'data' => array_fill(0, count($categories), 0),
            ];
        }

        // Isi data checkin
        foreach ($guestCheckins as $checkin) {
            $branchName = $checkin->guest->branch->name;

            $checkinDate = \Carbon\Carbon::parse($checkin->tanggal_checkin);
            $checkoutDate = $checkin->tanggal_checkout
                ? \Carbon\Carbon::parse($checkin->tanggal_checkout)
                : now();

            foreach ($categories as $index => $date) {
                $dateCarbon = \Carbon\Carbon::parse($date);
                if ($dateCarbon->between($checkinDate, $checkoutDate)) {
                    // Temukan branch di series
                    foreach ($chartData['series'] as &$branchSeries) {
                        if ($branchSeries['name'] === $branchName) {
                            $branchSeries['data'][$index]++;
                        }
                    }
                }
            }
        }

        return response()->json($chartData);
    }




    public function getRoomOccupancy(Request $request)
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        // Jika user memiliki branch_id, ambil data untuk branch tersebut saja
        if ($branchId) {
            $branches = Branch::where('id', $branchId)->get();
            $guestCheckins = GuestCheckin::with('room.branch')
                ->whereNotNull('tanggal_checkin')
                ->whereHas('room', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })
                ->get();
        } else {
            // Jika tidak ada branch_id pada user, ambil semua branch
            $branches = Branch::all();
            $guestCheckins = GuestCheckin::with('room.branch')
                ->whereNotNull('tanggal_checkin')
                ->get();
        }

        // Debugging: Check if data is being retrieved
        Log::info('Guest Checkins:', $guestCheckins->toArray());

        // Ensure that there are guest check-ins
        if ($guestCheckins->isEmpty()) {
            return response()->json(['message' => 'No guest check-ins found']);
        }

        // Determine the start and end dates for the chart
        $startDate = $guestCheckins->min('tanggal_checkin');
        $endDate = max(now()->format('Y-m-d'), $guestCheckins->max('tanggal_checkout'));

        // Debugging: Log the start and end dates
        Log::info('Start Date: ' . $startDate);
        Log::info('End Date: ' . $endDate);

        // Generate an array of dates for the x-axis of the chart
        $categories = [];
        $currentDate = \Carbon\Carbon::parse($startDate);

        while ($currentDate->lte($endDate)) {
            $categories[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Debugging: Log the categories (dates for the chart)
        Log::info('Categories: ', $categories);

        // Initialize an array to store the occupancy data for each branch
        $chartData = [
            'categories' => $categories,
            'series' => []
        ];

        // Iterate over each branch
        foreach ($branches as $branch) {
            $branchSeriesData = array_fill(0, count($categories), 0); // Initialize the occupancy count for each date

            // Get the rooms belonging to this branch
            $branchRooms = Room::where('branch_id', $branch->id)->get();

            // Debugging: Check rooms for each branch
            Log::info('Branch ' . $branch->name . ' Rooms:', $branchRooms->toArray());

            // Iterate through each guest check-in
            foreach ($guestCheckins as $guestCheckin) {
                // Only consider the check-ins that belong to this branch
                if ($guestCheckin->room->branch_id !== $branch->id) {
                    continue;
                }

                $checkinDate = \Carbon\Carbon::parse($guestCheckin->tanggal_checkin);
                $checkoutDate = $guestCheckin->tanggal_checkout
                    ? \Carbon\Carbon::parse($guestCheckin->tanggal_checkout) // If checkout exists, use it
                    : now(); // If no checkout, assume the room is occupied until today

                // Loop through each date from check-in to check-out
                while ($checkinDate->lte($checkoutDate)) {
                    $dateIndex = array_search($checkinDate->format('Y-m-d'), $categories);

                    if ($dateIndex !== false) {
                        // Increment the occupancy for this date and branch
                        $branchSeriesData[$dateIndex]++;
                    }

                    $checkinDate->addDay();
                }
            }

            // Add the occupancy data for this branch to the chart data
            $chartData['series'][] = [
                'name' => $branch->name,
                'data' => $branchSeriesData
            ];
        }

        // Return the chart data as a JSON response
        return response()->json($chartData);
    }


    public function getRoomOccupancyAccumulated(Request $request)
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();
        $branchId = $user->branch_id;

        // Ambil semua guest check-ins dan filter berdasarkan branch_id jika ada
        $guestCheckins = GuestCheckin::with('room.branch')
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('room', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            })
            ->whereNotNull('tanggal_checkin')
            ->get();

        // Pastikan data check-in tersedia
        if ($guestCheckins->isEmpty()) {
            return response()->json(['message' => 'No guest check-ins found'], 404);
        }

        // Tentukan tanggal awal dan akhir
        $startDate = $guestCheckins->min('tanggal_checkin');
        $endDate = max(now()->format('Y-m-d'), $guestCheckins->max('tanggal_checkout'));

        // Buat kategori tanggal untuk x-axis
        $categories = [];
        $currentDate = \Carbon\Carbon::parse($startDate);

        while ($currentDate->lte($endDate)) {
            $categories[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Inisialisasi array untuk data akumulasi
        $occupancyData = array_fill(0, count($categories), 0);

        // Iterasi melalui setiap guest check-in
        foreach ($guestCheckins as $guestCheckin) {
            $checkinDate = \Carbon\Carbon::parse($guestCheckin->tanggal_checkin);
            $checkoutDate = $guestCheckin->tanggal_checkout
                ? \Carbon\Carbon::parse($guestCheckin->tanggal_checkout)
                : now();

            // Hitung occupancy dari tanggal check-in hingga check-out
            while ($checkinDate->lte($checkoutDate)) {
                $dateIndex = array_search($checkinDate->format('Y-m-d'), $categories);

                if ($dateIndex !== false) {
                    $occupancyData[$dateIndex]++;
                }

                $checkinDate->addDay();
            }
        }

        // Siapkan data untuk chart
        $chartData = [
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Total Occupancy',
                    'data' => $occupancyData,
                ],
            ],
        ];

        // Kembalikan data dalam format JSON
        return response()->json($chartData);
    }


    public function getRoomOccupancyPieChart(Request $request)
    {
        // Ambil semua branch dengan jumlah kamar terisi hari ini
        $today = now()->format('Y-m-d');

        // Ambil jumlah kamar terisi per branch
        $branchOccupancy = GuestCheckin::selectRaw('branches.name as branch_name, COUNT(guest_checkins.id) as occupied_rooms')
            ->join('guests', 'guest_checkins.guest_id', '=', 'guests.id')
            ->join('branches', 'guests.branch_id', '=', 'branches.id')
            ->whereDate('guest_checkins.tanggal_checkin', '<=', $today)
            ->where(function ($query) use ($today) {
                $query->whereNull('guest_checkins.tanggal_checkout')
                    ->orWhereDate('guest_checkins.tanggal_checkout', '>=', $today);
            })
            ->groupBy('branches.name')
            ->get();

        // Total kamar yang terisi
        $totalOccupiedRooms = $branchOccupancy->sum('occupied_rooms');

        // Hitung persentase per branch
        $pieData = $branchOccupancy->map(function ($item) use ($totalOccupiedRooms) {
            return [
                'name' => $item->branch_name,
                'y' => $totalOccupiedRooms > 0
                    ? round(($item->occupied_rooms / $totalOccupiedRooms) * 100, 2)
                    : 0,
            ];
        });

        // Jika semua branch harus tampil meski kosong
        $allBranches = Branch::pluck('name');
        $pieChartData = $allBranches->map(function ($branch) use ($pieData) {
            $data = $pieData->firstWhere('name', $branch);
            return [
                'name' => $branch,
                'y' => $data['y'] ?? 0,
            ];
        });

        return response()->json($pieChartData);
    }

    public function getEventTimelineData()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

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


    public function __getOccupancyChartData()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        if (!$branchId) {
            // Pengguna adalah admin, ambil data untuk semua cabang
            $branches = Branch::all();
        } else {
            // Pengguna memiliki cabang tertentu, ambil data untuk cabang tersebut
            $branches = Branch::where('id', $branchId)->get();
        }

        // Persiapkan data untuk Pie Chart
        $data = [];

        foreach ($branches as $branch) {
            $totalRooms = $branch->rooms->count();
            $occupiedRooms = $branch->rooms->filter(function ($room) {
                return $room->guestCheckins()->where('tanggal_checkout', '>=', now()->format('Y-m-d'))->exists();
            })->count();
            $emptyRooms = $totalRooms - $occupiedRooms;

            // Hitung persentase terisi dan kosong
            $occupiedPercentage = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
            $emptyPercentage = 100 - $occupiedPercentage;

            $data[] = [
                'branch' => $branch->name,
                'occupied' => $occupiedPercentage,
                'empty' => $emptyPercentage
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getOccupancyChartData()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

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

    public function getBranchOccupancyChartData()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        if (!$branchId) {
            // Admin: ambil data untuk semua cabang
            $branches = Branch::all();
        } else {
            // Pengguna memiliki cabang tertentu
            $branches = Branch::where('id', $branchId)->get();
        }

        $branchData = [];

        foreach ($branches as $branch) {
            $totalRooms = $branch->rooms->count();
            $occupiedRooms = $branch->rooms->filter(function ($room) {
                return $room->guestCheckins()->where('tanggal_checkout', '>=', now()->format('Y-m-d'))->exists();
            })->count();

            $emptyRooms = $totalRooms - $occupiedRooms;

            $occupiedPercentage = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
            $emptyPercentage = 100 - $occupiedPercentage;

            $branchData[] = [
                'id' => $branch->id,
                'branch' => $branch->name,
                'occupied' => $occupiedPercentage,
                'empty' => $emptyPercentage
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $branchData
        ]);
    }
}
