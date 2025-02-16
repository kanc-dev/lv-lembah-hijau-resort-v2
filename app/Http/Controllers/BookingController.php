<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Branch;
use App\Models\Event;
use App\Models\EventPlotingRoom;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $branchId = $user->branch_id;

        $query = Booking::with(['event', 'originBranch', 'destinationBranch', 'eventPlotingRooms.room']);


        if ($branchId) {
            $query->where('unit_origin_id', $branchId);
        }

        if ($request->has('nama_kelas') && !empty($request->nama_kelas)) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->nama_kelas . '%');
            });
        }

        $bookings = $query->paginate(10);

        $data['bookings'] = $bookings;
        $data['page_title'] = 'Data Reservasi';
        return view('pages.booking.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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

        $rooms = Room::with('branch')->get();

        $data['rooms'] = $rooms;
        $data['events'] = $events;
        $data['branches'] = $branches;
        $data['branch_destination'] = Branch::all();
        $data['page_title'] = 'Tambah Reservasi';
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
        Alert::success('Success', 'Reservasi berhasil dibuat.');
        return redirect()->route('booking.index');
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

        Alert::success('Success', 'Reservasi berhasil diperbarui.');
        return redirect()->route('booking.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        Alert::success('Success', 'Reservasi berhasil dihapus.');
        return redirect()->route('booking.index');
    }

    public function plotRooms($id) {
        $booking = Booking::with('eventPlotingRooms')->findOrFail($id);

        // Ambil tanggal booking saat ini
        $checkin = $booking->tanggal_rencana_checkin;
        $checkout = $booking->tanggal_rencana_checkout;

        // Ambil ID kamar yang sudah dipilih untuk booking ini
        $selectedRoomIds = $booking->eventPlotingRooms->pluck('room_id')->toArray();

        // Ambil kamar yang tersedia (tidak bentrok dengan booking lain) **kecuali kamar yang sudah dipilih**
        $rooms = Room::where('branch_id', $booking->unit_destination_id)
            ->where(function ($query) use ($checkin, $checkout, $selectedRoomIds) {
                $query->whereDoesntHave('eventPlotingRooms', function ($query) use ($checkin, $checkout) {
                    $query->whereHas('booking', function ($q) use ($checkin, $checkout) {
                        $q->where(function ($q2) use ($checkin, $checkout) {
                            $q2->whereBetween('tanggal_rencana_checkin', [$checkin, $checkout])
                               ->orWhereBetween('tanggal_rencana_checkout', [$checkin, $checkout])
                               ->orWhere(function ($q3) use ($checkin, $checkout) {
                                   $q3->where('tanggal_rencana_checkin', '<=', $checkin)
                                      ->where('tanggal_rencana_checkout', '>=', $checkout);
                               });
                        });
                    });
                })
                // Tambahkan kondisi ini agar kamar yang sudah dipilih tetap muncul
                ->orWhereIn('id', $selectedRoomIds);
            })
            ->get();

        $data['rooms'] = $rooms;
        $data['selected_rooms'] = $selectedRoomIds;
        $data['page_title'] = 'Plotting Kamar';

        return view('pages.booking.plot-rooms', compact('data', 'booking'));
    }



    public function storePlotRooms(Request $request, $id) {
        $request->validate([
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
        ]);

        $booking = Booking::findOrFail($id);

        EventPlotingRoom::where('booking_id', $id)->delete();

        foreach ($request->rooms as $roomId) {
            EventPlotingRoom::create([
                'booking_id' => $id,
                'room_id' => $roomId
            ]);
        }

        Alert::success('Success', 'Kamar berhasil dipilih.');
        return redirect()->route('booking.index');
    }
}
