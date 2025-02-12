<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\Room;
use App\Models\RoomOccupancyHistory;
use App\Models\RoomReport;
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
         $branchId = $user->branch_id ?? '';
         $roleId = $user->role_id ?? '';

         if (!$branchId) {
             $branchName = request()->segment(2);

             $branchMapping = [
                 'bandung' => 1,
                 'yogyakarta' => 2,
                 'surabaya' => 3,
                 'padang' => 4,
                 'makassar' => 5,
             ];
             $branchId = $branchMapping[$branchName] ?? '';
         }


         if ($branchId) {
            $data['branch_list'] = Branch::where('id', $branchId)->get();
            $data['is_branch'] = true;
            $data['is_admin'] = false;
            $branches = Branch::where('id', $branchId)->get();
         } else {
            $data['branch_list'] = Branch::all();
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


         $data['total_branch_active'] = $data['branch_list']->count();
         $data['total_booking_active'] =  $data['bookings']->count();
         $data['total_event_active'] = $data['events']->count();
         $data['total_guest_active'] = $this->_getRoomStatus($branchId)->sum('total_tamu_checkin');
         $data['total_room_active'] = $this->_getRoomStatus($branchId)->count();
         $data['total_room_capacity'] = $this->_getRoomStatus($branchId)->sum('kapasitas');
         $data['total_room_occupied'] = $this->_getRoomStatusOccupancy($branchId)['percentage_occupied'];
         $data['total_room_empty'] = $this->_getRoomStatusOccupancy($branchId)['percentage_empty'];
         $data['list_okupansi_branch'] = $this->_getRoomStatusCalculateAllBranch($branchId);
         $data['event_booking'] = $this->_getUpcomingBookings($branchId);
         $data['guests_of_branch'] = $this->_getGuestOfBranch();
         $data['calendar_data_occupancy'] = $this->getCalendarDataOccupancy();
         $data['branches'] = $branchesWithOccupancy;
         $data['branchs'] = $branches;


         $data['page_title'] = 'Dashboard';
         return view('pages.home.index', compact('data', 'branches', 'branchId'));
     }

    public function indexx()
    {
        $today = Carbon::now()->startOfDay();

        $user = Auth::user();
        $branchId = $user->branch_id ?? '';

        if (!$branchId) {
            $branchName = request()->segment(2);

            $branchMapping = [
                'bandung' => 1,
                'yogyakarta' => 2,
                'surabaya' => 3,
                'padang' => 4,
                'makassar' => 5,
            ];
            $branchId = $branchMapping[$branchName] ?? '';
        }


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
        $data['total_guest_active'] = $this->_getRoomOccupancy($branchId)['total_guests'];

        $data['total_booking_active'] =  $data['bookings']->count();
        $data['total_event_active'] = $data['events']->count();
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
        $data['calendar_data_occupancy'] = $this->getCalendarDataOccupancy();
        $data['branch_list'] = Branch::all();

        // dd($data['calendar_data_occupancy']);
        // dd($data['occupancy_of_branch']);


        $data['page_title'] = 'Dashboard';
        return view('pages.home.index', compact('data', 'branches', 'branchId'));
    }

    public function branch($branchName = null)
    {
        $today = Carbon::now()->startOfDay();

        // Daftar branch_id untuk mapping nama ke ID
        $branchMapping = [
            'bandung' => 1,
            'yogyakarta' => 2,
            'surabaya' => 3,
            'padang' => 4,
            'makassar' => 5,
        ];


        // Cek apakah nama branch valid
        $selectedBranchId = $branchName ? ($branchMapping[$branchName] ?? '') : '';
        $branchId = $selectedBranchId ?? '';

        if (!$selectedBranchId) {
            abort(404, 'Branch not found');
        }

        // Logika untuk menentukan akses admin atau branch
        $data['is_branch'] = $branchId ?? false;
        $data['is_admin'] = !$data['is_branch'];


        $branches = Branch::where('id', $selectedBranchId)->get();

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

        // Akumulasi data
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

        // Data tambahan
        $data['branch_occupancy'] = $branchesWithOccupancy;
        $data['branches'] = $branches;
        $data['branchs'] = $branches;
        $data['branch_list'] = Branch::all();
        $data['total_rooms'] = $totalRooms;
        $data['occupied_rooms'] = $occupiedRooms;
        $data['empty_rooms'] = $emptyRooms;
        $data['total_guests'] = $totalGuests;

        $data['page_title'] = 'Dashboard ' . ucfirst($branchName);


        // Return view sesuai nama cabang
        return view("pages.home.$branchName", compact('data', 'branches', 'branchId'));
    }

    private function _getRoomStatus($branchId)
    {
        $getRoomStatus = Room::with(['branch', 'event', 'guestCheckins.guest'])
            ->when($branchId, function ($query, $branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->get()
            ->map(function ($room) {
                $pendingCheckins = $room->guestCheckins->filter(function ($checkin) {
                    return !is_null($checkin->guest_id);
                });
                $activeCheckins = $room->guestCheckins->filter(function ($checkin) {
                    return !is_null($checkin->tanggal_checkin);
                });


                return [
                    'id' => $room->id,
                    'branch_id' => $room->branch_id,
                    'branch' => $room->branch->name ?? 'N/A',
                    'nama' => $room->nama,
                    'tipe' => $room->tipe,
                    'status' => $room->status,
                    'kapasitas' => $room->kapasitas,
                    'terisi' => $activeCheckins->count(),
                    'sisa_bed' => $room->kapasitas - $activeCheckins->count(),
                    'event' => $room->event->nama_kelas ?? null,
                    'events' => $room->guestCheckins->map(function ($checkin) {
                       return $checkin->guest->events ?? [];
                    }),
                    'tamu' => $pendingCheckins->map(function ($checkin) {
                        return [
                            'nama' => $checkin->guest->nama,
                            'checkin' => $checkin->tanggal_checkin,
                            'checkout' => $checkin->tanggal_checkout,
                        ];
                    }),
                    'total_tamu' => $pendingCheckins->count(),
                    'total_tamu_checkin' => $activeCheckins->count(),
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

    private function _getRoomStatusCalculateAllBranch($branchId = null)
    {
        $roomData = $this->_getRoomStatus($branchId);

        $branchMapping = $roomData->groupBy('branch_id')->map(function ($rooms, $branchId) {
            $branchName = $rooms->first()['branch'] ?? 'N/A';

            $kapasitas = $rooms->sum('kapasitas');
            $terisi = $rooms->sum('terisi');
            $sisa_bed = $rooms->sum('sisa_bed');

            $occupancyPercentage = $kapasitas > 0 ? round(($terisi / $kapasitas) * 100, 2) : 0;
            $sisaBedPercentage = $kapasitas > 0 ? round(($sisa_bed / $kapasitas) * 100, 2) : 0;

            return [
                'branch_id'           => $branchId,
                'branch'              => $branchName,
                'kapasitas'           => $kapasitas,
                'terisi'              => $terisi,
                'sisa_bed'            => $sisa_bed,
                'total_tamu'          => $rooms->sum('total_tamu'),
                'total_tamu_checkin'  => $rooms->sum('total_tamu_checkin'),
                'total_kamar'         => $rooms->count(),
                'occupancy_percentage' => $occupancyPercentage,
                'sisa_bed_percentage'  => $sisaBedPercentage,
            ];
        })->values();

        return $branchMapping;
    }

    public function getRoomOccupancyPieChart()
    {
        $roomStatusData = $this->_getRoomStatusCalculateAllBranch();


        $formattedData = $roomStatusData->map(function ($branch) {
            return [
                'name' => $branch['branch'],
                'y' => $branch['occupancy_percentage']
            ];
        });

        $pieChartData = $formattedData->values();

        return response()->json($pieChartData);
    }

    public function getRoomOccupancyAccumulatePieChart()
    {
        $user = Auth::user();
        $branchId = $user->branch_id ?? '';

    }

    ///////////////////////////////////////////////



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


    private function _getUpcomingBookings($branchId = null)
    {
        $today = Carbon::now()->startOfDay();

        $query = Booking::with(['event', 'originBranch', 'destinationBranch'])
            ->where('tanggal_rencana_checkout', '>=', $today)
            ->orderBy('tanggal_rencana_checkin', 'asc');

        if ($branchId) {
            $query->where('unit_origin_id', $branchId);
        }

        $bookings = $query->get();

        return $bookings;
    }

    private function _getGuestOfBranch()
    {
        $data = Branch::withCount('guests')->get();
        return $data;
    }


    public function __getBranchGuestData(Request $request)
    {
        $data = Guest::whereNotNull('tanggal_checkin')
            ->where('tanggal_checkin', '<=', now())
            ->where(function ($query) {
                $query->whereNull('tanggal_checkout')
                    ->orWhere('tanggal_checkout', '>=', now());
            })
            ->get()
            ->groupBy('branch_id');


        $chartData = [
            'categories' => [],
            'series' => []
        ];

        foreach ($data as $branchId => $guests) {
            $branchName = $guests->first()->branch->name;

            $seriesData = [];
            $dates = collect();


            foreach ($guests as $guest) {
                $checkinDate = Carbon::parse($guest->tanggal_checkin);
                $checkoutDate = $guest->tanggal_checkout ? Carbon::parse($guest->tanggal_checkout) : Carbon::now();


                while ($checkinDate <= $checkoutDate) {
                    $dates->push($checkinDate->format('Y-m-d'));
                    $checkinDate->addDay();
                }
            }


            $dates = $dates->unique()->sort();

            $chartData['categories'] = $dates->values()->toArray();

            foreach ($chartData['categories'] as $date) {
                $count = 0;
                foreach ($guests as $guest) {
                    $checkinDate = Carbon::parse($guest->tanggal_checkin);
                    $checkoutDate = $guest->tanggal_checkout ? Carbon::parse($guest->tanggal_checkout) : Carbon::now();

                    if ($checkinDate <= Carbon::parse($date) && $checkoutDate >= Carbon::parse($date)) {
                        $count++;
                    }
                }
                $seriesData[] = $count;
            }

            $chartData['series'][] = [
                'name' => $branchName,
                'data' => $seriesData
            ];
        }

        return response()->json($chartData);
    }

    public function getBranchGuestData(Request $request)
    {
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

    public function getRoomOccupancyAccumulated(Request $request)
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();
        $branchId = $user->branch_id;

        if (!$branchId) {
            $branchName = request()->segment(2);

            $branchMapping = [
                'bandung' => 1,
                'yogyakarta' => 2,
                'surabaya' => 3,
                'padang' => 4,
                'makassar' => 5,
            ];
            $branchId = $branchMapping[$branchName];
        }

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


    public function getEventTimelineData()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        if (!$branchId) {
            $branchName = request()->segment(2);

            $branchMapping = [
                'bandung' => 1,
                'yogyakarta' => 2,
                'surabaya' => 3,
                'padang' => 4,
                'makassar' => 5,
            ];
            $branchId = $branchMapping[$branchName];
        }

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

        // Ambil nama cabang dari URL
        $branchName = request()->segment(2);

        // Mapping nama cabang ke branch_id
        $branchMapping = [
            'bandung' => 1,
            'yogyakarta' => 2,
            'surabaya' => 3,
            'padang' => 4,
            'makassar' => 5,
        ];

        if (!$branchId && $branchName) {
            if (isset($branchMapping[$branchName])) {
                $branchId = $branchMapping[$branchName];
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cabang tidak valid.',
                ], 404);
            }
        }

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
            $branchName = request()->segment(2);

            $branchMapping = [
                'bandung' => 1,
                'yogyakarta' => 2,
                'surabaya' => 3,
                'padang' => 4,
                'makassar' => 5,
            ];
            $branchId = $branchMapping[$branchName];
        }

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

    public function getCalendarDataOccupancy(Request $request = null)
    {

        // $request = $request ?? new Request();
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
        $formattedData = $occupancyHistories->map(function ($history) use ($branchColors) {
            $branchId = $history->branch->id;
            $color = $branchColors[$branchId] ?? '#CCCCCC';


            return [
                'id' => $history->id,
                'start' => Carbon::parse($history->tanggal)->format('Y-m-d'),
                'title' => $history->branch->name . " - " . $history->occupancy_percentage . "%",
                'color' => $color,
                'textColor' => '#333333',
                'description' => "Total Rooms: {$history->total_rooms}, Capacity: {$history->total_capacity}, Occupied: {$history->occupied_capacity}, Available: {$history->available_capacity}",
            ];
        });

        return response()->json($formattedData);
    }
}
