<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['event', 'originBranch', 'destinationBranch'])->get();
        $data['bookings'] = $bookings;
        $data['page_title'] = 'Data Booking';
        return view('pages.booking.index', compact('data'));
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

        $events = Event::all();
        $rooms = Room::with('branch')->get();

        $data['rooms'] = $rooms;
        $data['events'] = $events;
        $data['branches'] = $branches;
        $data['branch_destination'] = Branch::all();
        $data['page_title'] = 'Tambah Booking';
        return view('pages.booking.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'jumlah_peserta' => 'required|integer',
            // 'rooms' => 'required|array',
            // 'rooms.*' => 'exists:rooms,id',
            'unit_origin_id' => 'required|exists:branches,id',
            'unit_destination_id' => 'required|exists:branches,id',
            'tanggal_rencana_checkin' => 'required|date',
            'tanggal_rencana_checkout' => 'required|date',
        ]);

        Booking::create($request->all());
        return redirect()->route('booking.index')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $data = [
            'events' => Event::all(),
            'branches' => Branch::all(),
            'rooms' => Room::all(),
            'booking' => $booking
        ];
        return view('pages.booking.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        // Validate incoming request
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'jumlah_peserta' => 'required|integer',
            // 'rooms' => 'required|array',
            // 'unit_origin_id' => 'required|exists:branches,id',
            'unit_destination_id' => 'required|exists:branches,id',
            'tanggal_rencana_checkin' => 'required|date',
            'tanggal_rencana_checkout' => 'required|date',
        ]);

        // Get the current booking data
        $booking->event_id = $request->input('event_id');
        $booking->jumlah_peserta = $request->input('jumlah_peserta');
        // $booking->unit_origin_id = $request->input('unit_origin_id');
        $booking->unit_destination_id = $request->input('unit_destination_id');
        $booking->tanggal_rencana_checkin = $request->input('tanggal_rencana_checkin');
        $booking->tanggal_rencana_checkout = $request->input('tanggal_rencana_checkout');

        // Update rooms, converting to JSON format
        // $booking->rooms = json_encode($request->input('rooms'));

        // Save the changes
        $booking->save();

        return redirect()->route('booking.index')->with('success', 'Booking updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully.');
    }
}
