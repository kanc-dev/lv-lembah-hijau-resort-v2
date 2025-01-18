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
        $user = Auth::user();
        $branchId = $user->branch_id;

        if (!$branchId) {
            $data['is_admin'] = true;
        } else {
            $data['is_admin'] = false;
        }

        $data['branchs'] = Branch::all();
        $data['bookings'] = Booking::all();
        $data['events'] = Event::all();


        $data['guests_of_branch'] = $this->_getGuestOfBranch();
        $data['occupancy_of_branch'] = $this->showOccupancy();

        // dd($data);
        $data['page_title'] = 'Dashboard';
        return view('pages.home.index', compact('data'));
    }

    private function showOccupancy()
    {
        $today = now()->format('Y-m-d'); // Mendapatkan tanggal hari ini

        // Ambil semua cabang dengan data kamar dan guest_checkins
        $branches = Branch::with(['rooms' => function ($query) {
            $query->select('id', 'branch_id', 'status'); // Ambil status kamar
        }])
            ->get();

        $branchesWithOccupancy = $branches->map(function ($branch) use ($today) {
            // Hitung total kamar dan kamar terisi pada hari ini
            $totalRooms = $branch->rooms->count();
            $occupiedRooms = $branch->rooms->filter(function ($room) use ($today) {
                // Mengambil guest_checkins untuk kamar dan memeriksa apakah kamar terisi pada tanggal hari ini
                return $room->guestCheckins()->where(function ($query) use ($today) {
                    $query->where('tanggal_checkin', '<=', $today)
                        ->where(function ($query) use ($today) {
                            $query->where('tanggal_checkout', '>=', $today)
                                ->orWhereNull('tanggal_checkout');
                        });
                })->exists();
            })->count();

            // Menyimpan data yang akan dikirim ke view
            $branch->occupancy = [
                'occupied' => $occupiedRooms,
                'total' => $totalRooms
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

    // Controller untuk mengambil data tamu per tanggal dan cabang
    // Controller untuk mengambil data tamu per tanggal dan cabang
    public function getBranchGuestData(Request $request)
    {
        $guests = Guest::whereNotNull('tanggal_checkin')
            ->select('tanggal_checkin', 'tanggal_checkout', 'branch_id')
            ->orderBy('tanggal_checkin')
            ->get();

        // Tentukan range tanggal yang akan ditampilkan pada chart (dari tanggal check-in pertama hingga tanggal sekarang)
        $startDate = $guests->min('tanggal_checkin');
        $endDate = now()->format('Y-m-d');  // tanggal hari ini

        // Buat array untuk kategori tanggal
        $categories = [];
        $currentDate = \Carbon\Carbon::parse($startDate);

        while ($currentDate->lte($endDate)) {
            $categories[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Siapkan array untuk data chart
        $chartData = [
            'categories' => $categories,
            'series' => []
        ];

        // Tempatkan data berdasarkan cabang
        foreach ($guests as $guest) {
            $branchName = $guest->branch->name;

            // Rentang tanggal check-in sampai check-out
            $checkinDate = \Carbon\Carbon::parse($guest->tanggal_checkin);
            $checkoutDate = $guest->tanggal_checkout ? \Carbon\Carbon::parse($guest->tanggal_checkout) : \Carbon\Carbon::today();

            // Loop melalui setiap tanggal dari check-in hingga check-out
            foreach ($categories as $date) {
                $dateCarbon = \Carbon\Carbon::parse($date);
                if ($dateCarbon->between($checkinDate, $checkoutDate)) {
                    // Cek apakah series untuk branch ini sudah ada
                    $branchExists = false;
                    foreach ($chartData['series'] as &$branchSeries) {
                        if ($branchSeries['name'] === $branchName) {
                            // Tambahkan data untuk tanggal yang sesuai
                            $dateIndex = array_search($date, $categories);
                            $branchSeries['data'][$dateIndex] = isset($branchSeries['data'][$dateIndex]) ? $branchSeries['data'][$dateIndex] + 1 : 1;
                            $branchExists = true;
                            break;
                        }
                    }

                    // Jika branch belum ada, buat series baru
                    if (!$branchExists) {
                        $branchSeriesData = array_fill(0, count($categories), 0); // Isi dengan 0
                        $dateIndex = array_search($date, $categories);
                        $branchSeriesData[$dateIndex] = 1;

                        $chartData['series'][] = [
                            'name' => $branchName,
                            'data' => $branchSeriesData
                        ];
                    }
                }
            }
        }

        // Kirim response ke frontend
        return response()->json($chartData);
    }

    public function getRoomOccupancy(Request $request)
    {
        // Retrieve all branches
        $branches = Branch::all();

        // Retrieve all guest check-ins, rooms, and check-in/check-out dates
        $guestCheckins = GuestCheckin::with('room.branch')
            ->whereNotNull('tanggal_checkin')
            ->get();

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
}
