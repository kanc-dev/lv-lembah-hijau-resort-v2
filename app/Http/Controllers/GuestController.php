<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Room;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guests = Guest::all();
        $data['guests'] = $guests;
        $data['page_title'] = 'Data Tamu';
        return view('pages.guest.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['rooms'] = Room::all();
        $data['branches'] = Branch::all();
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

        // Create a new guest record
        Guest::create([
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
}
