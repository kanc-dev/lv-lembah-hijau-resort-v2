<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $rooms = Room::with(['branch', 'guestCheckins.guest.events'])->get();
        } else {
            $rooms = Room::with(['branch', 'guestCheckins.guest.events'])->where('branch_id', $branchId)->get();
        }


        $data['rooms'] = $rooms;
        $data['page_title'] = 'Data Kamar';

        return view('pages.room.index', compact('data'));
    }

    public function status()
    {
        $user = auth()->user();

        // Jika user tidak memiliki branch_id (superadmin)
        if (!$user->branch_id) {
            $rooms = Room::with('branch')
                ->withCount(['guests as total_terisi' => function ($query) {
                    $query->whereDate('tanggal_checkin', '<=', now())
                        ->whereDate('tanggal_checkout', '>=', now());
                }])
                ->get();
            $events = Event::all();
        } else {
            // Jika user memiliki branch_id (PIC)
            $rooms = Room::where('branch_id', $user->branch_id)
                ->withCount(['guests as total_terisi' => function ($query) {
                    // Menghitung tamu yang check-in pada hari ini dan belum checkout
                    $query->whereDate('tanggal_checkin', '<=', now())
                        ->whereDate('tanggal_checkout', '>=', now());
                }])
                ->with('branch')
                ->get();
            $events = Event::where('branch_id', $user->branch_id)->get();
        }

        // Mengolah data kamar dan statusnya
        $roomStatuses = $rooms->map(function ($room) {
            $jumlahTerisi = $room->total_terisi; // Menggunakan total_terisi dari withCount
            $sisaKamar = $room->kapasitas - $jumlahTerisi;

            return [
                'id' => $room->id,
                'unit' => $room->branch->name ?? 'N/A',
                'nama_kamar' => $room->nama,
                'kapasitas' => $room->kapasitas,
                'terisi' => $jumlahTerisi,
                'sisa' => $sisaKamar,
                'status' => $room->status,
            ];
        });

        $data['rooms'] = $roomStatuses;
        $data['page_title'] = 'Status Kamar';

        return view('pages.room.status', compact('data'));
    }


    public function report()
    {
        $data = Room::all();
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
}
