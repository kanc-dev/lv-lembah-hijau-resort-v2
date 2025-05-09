<?php

namespace App\Http\Controllers;

use App\Exports\RoomReportExport;
use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use App\Models\RoomReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branchId = auth()->user()->branch_id;

        if ($branchId == 0) {
            $rooms = Room::with(['branch', 'guestCheckins.guest.events'])->paginate(10);
            $events = Event::all();
        } else {
            $rooms = Room::with(['branch', 'guestCheckins.guest.events'])->where('branch_id', $branchId)->paginate(10);
            $events = Event::where('branch_id', $branchId)->get();
        }

        $data['rooms'] = $rooms;
        $data['events'] = $events;
        $data['page_title'] = 'Data Kamar';
        // dd($data);

        return view('pages.room.index', compact('data'));
    }

    public function status()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $getRoomStatus = Room::with(['branch', 'event', 'guestCheckins.guest'])
            ->when($branchId, function ($query, $branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->paginate(10) // Tambahkan pagination
            ->through(function ($room) { // Gunakan through() untuk mapping setelah pagination
                $pendingCheckins = $room->guestCheckins->filter(function ($checkin) {
                    return is_null($checkin->tanggal_checkout);
                });

                $activeCheckins = $room->guestCheckins->filter(function ($checkin) {
                    return !is_null($checkin->tanggal_checkin) && is_null($checkin->tanggal_checkout);
                });

                $checkoutStatus = $room->guestCheckins->filter(function ($checkin) {
                    return !is_null($checkin->tanggal_checkout);
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
                    'total_tamu_checkin' => $activeCheckins->count(),
                    'total_tamu_checkout' => $checkoutStatus->count()
                ];
            });

        $getSummaryRoomStatus = Room::with(['branch', 'event', 'guestCheckins.guest'])
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
                    'total_tamu_checkin' => $activeCheckins->count(),
                ];
            });

        $data['rooms'] = $getRoomStatus;
        $data['rooms_summary'] = $getSummaryRoomStatus;

        // dump($data);

        $data['page_title'] = 'Status Kamar';


        return view('pages.room.status', compact('data'));
    }

    public function report()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        $query = RoomReport::query();
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $data['reports'] = $query->paginate(10, ['*'], 'reports_page');
        $data['summary'] = $query
            ->selectRaw('
                report_date,
                branch,
                COUNT(*) as total_kamar,
                COUNT(CASE WHEN terisi > 0 THEN 1 END) as total_kamar_terisi,
                COUNT(CASE WHEN terisi = 0 THEN 1 END) as total_kamar_kosong,
                SUM(kapasitas) as total_kapasitas,
                SUM(CASE WHEN terisi > 0 THEN terisi ELSE 0 END) as total_bed_terisi,
                SUM(CASE WHEN sisa_bed > 0 THEN sisa_bed ELSE 0 END) as total_bed_tersedia,
                SUM(total_tamu_checkin) as total_tamu_checkin,
                SUM(total_tamu_checkout) as total_tamu_checkout
            ')
            ->groupBy('report_date', 'branch')
            ->orderBy('report_date', 'desc')
            ->paginate(10, ['*'], 'summary_page');

        // return response()->json($data['reports']);
        // dump($data['summary']);


        $data['page_title'] = 'Laporan Kamar';
        return view('pages.room.report', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $branchId = $user->branch_id; // Asumsi field branch_id ada di tabel users

        if ($branchId) {
            $branches = Branch::where('id', $branchId)->get();
            $events = Event::where('branch_id', $branchId)->get();
        } else {
            $branches = Branch::all();
            $events = Event::all();
        }

        $data['branches'] = $branches;
        $data['events'] = $events;
        $data['page_title'] = 'Tambah Kamar';
        return view('pages.room.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'kapasitas' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'room_status' => 'required|in:available,unavailable',
        ]);

        // Menyimpan kamar
        Room::create([
            'nama' => $validated['nama_kamar'],
            'tipe' => $validated['tipe'],
            'kapasitas' => $validated['kapasitas'],
            'branch_id' => $validated['branch_id'],
            'event_id' => $request->input('event_id'),
            'status' => $validated['room_status'],
        ]);

        return redirect()->route('room.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $user = Auth::user();
        $branchId = $user->branch_id;
        if ($branchId) {
            $branches = Branch::where('id', $branchId)->get();
            $events = Event::where('branch_id', $branchId)->get();
        } else {
            $branches = Branch::all();
            $events = Event::all();
        }

        $room = Room::with('branch', 'event')->findOrFail($id);

        $data['room'] = $room;
        $data['branches'] = $branches;
        $data['events'] = $events;
        $data['page_title'] = 'Tambah Kamar';
        return view('pages.room.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'kapasitas' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'room_status' => 'required|in:available,unavailable',
        ]);

        // dd($validated);

        $room = Room::findOrFail($id);
        $room->update([
            'nama' => $validated['nama_kamar'],
            'tipe' => $validated['tipe'],
            'kapasitas' => $validated['kapasitas'],
            'branch_id' => $validated['branch_id'],
            'event_id' => $request->input('event_id'),
            'status' => $validated['room_status'],
        ]);

        return redirect()->route('room.index')->with('success', 'Kamar berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Kamar berhasil dihapus');
    }


    // RoomController.php
    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
        ]);

        $checkinDate = $request->input('checkin_date');
        $checkoutDate = $request->input('checkout_date');

        $bookedRoomIds = Booking::where(function ($query) use ($checkinDate, $checkoutDate) {
            $query->whereBetween('tanggal_rencana_checkin', [$checkinDate, $checkoutDate])
                ->orWhereBetween('tanggal_rencana_checkout', [$checkinDate, $checkoutDate])
                ->orWhere(function ($query) use ($checkinDate, $checkoutDate) {
                    $query->where('tanggal_rencana_checkin', '<=', $checkinDate)
                        ->where('tanggal_rencana_checkout', '>=', $checkoutDate);
                });
        })->pluck('rooms')->flatten()->toArray();

        $availableRooms = Room::whereNotIn('id', $bookedRoomIds)->get();

        return response()->json($availableRooms);
    }

    public function bulkPlotEvent(Request $request)
    {

        // $roomIds = explode(',', $request->input('room_ids'));

        // $request->merge(['room_ids' => $roomIds]);

        // dd($request->all());

        $roomIds = explode(',', $request->input('room_ids'));

        $request->merge(['room_ids' => $roomIds]);


        $request->validate([
            'event_id' => 'required|exists:events,id',
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
        ]);

        $eventId = $request->input('event_id');
        $roomIds = $request->input('room_ids');

        Room::whereIn('id', $roomIds)->update(['event_id' => $eventId]);

        return redirect()->back()->with('success', 'Bulk event successfully applied to rooms.');
    }

    public function generateRoomReports(Request $request)
    {
        Artisan::call('generate:room-reports');

        return redirect()->back()->with('success', 'Daily room report generated successfully!');
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $branchId = $request->input('branch_id');

        return Excel::download(new RoomReportExport($request->start_date, $request->end_date, $branchId), 'room_report.xlsx');
    }
}
