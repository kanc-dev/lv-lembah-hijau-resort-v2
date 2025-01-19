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

        $guests = Guest::with('branch.rooms', 'events', 'guestcheckins.room')->get();
        $data['guests'] = $guests;
        $data['page_title'] = 'Data Tamu';
        // dd($data['guests']);
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

    public function setCheckinDate(Request $request, Guest $guest)
    {
        $request->validate([
            'checkin_date' => 'required|date',
        ]);

        // Update the check-in date
        $guest->tanggal_checkin = $request->input('checkin_date');
        $guest->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in date updated successfully.',
            'checkin_date' => $guest->tanggal_checkin,
        ]);
    }

    // Method to update check-out date
    public function setCheckoutDate(Request $request, Guest $guest)
    {
        $request->validate([
            'checkout_date' => 'required|date',
        ]);

        // Update the check-out date
        $guest->tanggal_checkout = $request->input('checkout_date');
        $guest->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-out date updated successfully.',
            'checkout_date' => $guest->tanggal_checkout,
        ]);
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
