<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Branch;
use App\Models\Event;
use App\Models\GuestCheckin;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $branchId = $user->branch_id; // Asumsi field branch_id ada di tabel users

        if ($branchId) {
            $rooms = Room::where('branch_id', $branchId)->get();
            $guests = Guest::with('branch.rooms', 'events', 'guestcheckins.room')->where('branch_id', $branchId)->get();
        } else {
            $rooms = Room::all();
            $guests = Guest::with('branch.rooms', 'events', 'guestcheckins.room')->get();
        }
        $data['guests'] = $guests;
        $data['rooms'] = $rooms;
        $data['page_title'] = 'Data Tamu';

        return view('pages.guest.index', compact('data'));
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
        } else {
            $branches = Branch::all();
        }
        $data['rooms'] = Room::all();
        $data['branches'] = $branches;
        $data['events'] = Event::all();
        $data['page_title'] = 'Tambah Tamu';
        return view('pages.guest.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:1',
            'branch_id' => 'required|exists:branches,id',
            'batch' => 'required|string|max:255',
            'kendaraan' => 'required|string|max:255',
            'no_polisi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'kantor_cabang' => 'required|string|max:255',
            'tanggal_rencana_checkin' => 'required|date',
            'tanggal_rencana_checkout' => 'required|date',
            'event_id' => 'required|exists:events,id',
        ]);

        // Create a new guest record
        $guest = Guest::create([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'branch_id' => $request->branch_id,
            'batch' => $request->batch,
            'kendaraan' => $request->kendaraan,
            'no_polisi' => $request->no_polisi,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'kantor_cabang' => $request->kantor_cabang,
            'tanggal_rencana_checkin' => $request->tanggal_rencana_checkin,
            'tanggal_rencana_checkout' => $request->tanggal_rencana_checkout,
        ]);

        // Associate the guest with the event through the event_guest pivot table
        $guest->events()->attach($request->event_id);

        return redirect()->route('guest.index')->with('success', 'Guest created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        $data['guest'] = $guest;
        $data['rooms'] = Room::all();
        $data['branches'] = Branch::all();
        $data['events'] = Event::all();
        $data['page_title'] = 'Edit Tamu';
        return view('pages.guest.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:1',
            'branch_id' => 'required|exists:branches,id', // Validate branch_id
            'batch' => 'required|string|max:255',
            'kendaraan' => 'required|string|max:255',
            'no_polisi' => 'required|string|max:255',
            'kantor_cabang' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'tanggal_rencana_checkin' => 'required|date',
            'tanggal_rencana_checkout' => 'required|date',
            'event_id' => 'required|exists:events,id', // Validate event_id
        ]);

        $guest = Guest::findOrFail($id);

        // Update the guest record
        $guest->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'branch_id' => $request->branch_id,
            'batch' => $request->batch,
            'kendaraan' => $request->kendaraan,
            'no_polisi' => $request->no_polisi,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'kantor_cabang' => $request->kantor_cabang,
            'tanggal_rencana_checkin' => $request->tanggal_rencana_checkin,
            'tanggal_rencana_checkout' => $request->tanggal_rencana_checkout,
            'room_id' => $request->room_id,
            'event_id' => $request->event_id,
        ]);

        return redirect()->route('guest.index')->with('success', 'Guest updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->route('guest.index')->with('success', 'Guest deleted successfully');
    }

    public function plotRoom(Request $request, $guestId)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        try {
            $guest = Guest::findOrFail($guestId);

            // Temukan kamar yang dipilih
            $room = Room::findOrFail($request->input('room_id'));

            // Pastikan kamar tidak dalam status unavailable
            if ($room->status == 'unavailable') {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar tidak tersedia.',
                ], 400);
            }

            // Periksa apakah kapasitas kamar sudah penuh
            if ($room->terisi >= $room->kapasitas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamar sudah penuh.',
                ], 400);
            }

            // Tambah 1 tamu ke kolom terisi
            $room->terisi += 1;

            // Jika terisi == kapasitas, ubah status kamar menjadi 'unavailable'
            if ($room->terisi == $room->kapasitas) {
                $room->status = 'unavailable';
            }

            // Simpan perubahan pada kamar
            $room->event_id = $guest->event_id;
            $room->save();

            // Simpan kamar yang dipilih ke guest_checkins
            GuestCheckin::updateOrCreate(
                ['guest_id' => $guestId],
                ['room_id' => $room->id]
            );

            return response()->json([
                'success' => true,
                'message' => 'Kamar berhasil dipilih untuk tamu.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function setCheckinDate(Request $request, $guestId)
    {
        $request->validate([
            'checkin_date' => 'required|date',
        ]);

        try {
            $guest = Guest::findOrFail($guestId);
            $checkinDate = \Carbon\Carbon::parse($request->input('checkin_date'))->toDateString();

            $checkin = GuestCheckin::updateOrCreate(
                ['guest_id' => $guestId],
                [
                    'tanggal_checkin' => $checkinDate,
                    // 'room_id' => $guest->room_id ?? null,
                ]
            );

            return redirect()->back()->with('success', 'Check-in berhasil dilakukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function setCheckoutDate(Request $request, $guestId)
    {
        $request->validate([
            'checkout_date' => 'required|date',
        ]);

        try {
            $guest = Guest::findOrFail($guestId);
            $checkinDate = \Carbon\Carbon::parse($request->input('checkout_date'))->toDateString();

            // Temukan record check-in tamu
            $checkin = GuestCheckin::where('guest_id', $guestId)->first();

            if (!$checkin) {
                return redirect()->back()->with('error', 'Tamu tidak ditemukan untuk check-out.');
            }

            // Ambil kamar yang digunakan tamu
            $room = Room::findOrFail($checkin->room_id);

            // Update tanggal check-out di GuestCheckin
            $checkin->update([
                'tanggal_checkout' => $checkinDate,
            ]);

            // Kurangi 1 dari kolom terisi
            $room->terisi -= 1;

            // Jika terisi < kapasitas, ubah status kamar menjadi 'available'
            if ($room->terisi < $room->kapasitas) {
                $room->status = 'available';
            }

            // Simpan perubahan pada kamar
            $room->save();

            return redirect()->back()->with('success', 'Check-out berhasil dilakukan. Kapasitas kamar diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function getAvailableRooms($guestId)
    {
        $guest = Guest::findOrFail($guestId);
        $rooms = Room::where('branch_id', $guest->branch_id)->get();

        return response()->json($rooms);
    }

    public function checkIn(Request $request, $guestId)
    {
        $request->validate([
            'checkin_date' => 'required|date',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $guest = Guest::findOrFail($guestId);
        $checkin = new GuestCheckin();
        $checkin->guest_id = $guest->id;
        $checkin->room_id = $request->room_id;
        $checkin->tanggal_checkin = $request->checkin_date;
        $checkin->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'checkin_date' => $checkin->tanggal_checkin,
        ]);
    }

    public function checkOut(Request $request, $guestId)
    {
        $request->validate([
            'checkout_date' => 'required|date',
        ]);

        // Cari check-in tamu
        $guestCheckin = GuestCheckin::where('guest_id', $guestId)
            ->whereNull('tanggal_checkout')  // Pastikan ini adalah check-in aktif
            ->first();

        if ($guestCheckin) {
            // Update tanggal_checkout
            $guestCheckin->tanggal_checkout = $request->checkout_date;
            $guestCheckin->save();

            return response()->json([
                'success' => true,
                'message' => 'Check-out date set successfully.',
                'checkout_date' => $guestCheckin->tanggal_checkout
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No active check-in found for this guest.'
        ]);
    }
}
