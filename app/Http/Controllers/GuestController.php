<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use App\Models\GuestCheckin;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $branchId = $user->branch_id ?? null;

        $guests_query = Guest::with('branch.rooms', 'events', 'guestcheckins.room');

        if ($branchId) {
            $guests_query->where('branch_id', $branchId);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $guests_query->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('no_hp', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('no_polisi', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('filter_gender') && $request->filter_gender != '') {
            $guests_query->where('jenis_kelamin', $request->filter_gender);
        }

        if ($request->has('filter_status') && !empty($request->filter_status)) {
            $guests_query->where(function ($query) use ($request) {
                foreach ($request->filter_status as $status) {
                    switch ($status) {
                        case 'plotted':
                            $query->whereHas('guestcheckins', function ($q) {
                                $q->whereNotNull('room_id');
                            });
                            break;
                        case 'checked_in':
                            $query->whereHas('guestcheckins', function ($q) {
                                $q->whereNotNull('tanggal_checkin');
                            });
                            break;
                        case 'checked_out':
                            $query->whereHas('guestcheckins', function ($q) {
                                $q->whereNotNull('tanggal_checkout');
                            });
                            break;
                    }
                }
            });
        }

        if ($request->has('filter_event') && $request->filter_event != '') {
            $guests_query->whereHas('events', function ($q) use ($request) {
                $q->where('booking_id', $request->filter_event);
            });
        }

        if ($request->has('filter_unit') && $request->filter_unit != '') {
            $guests_query->where('branch_id', $request->filter_unit);
        }

        $guests = $guests_query->paginate(10);

        $rooms_query = Room::with('branch')
            ->join('event_ploting_rooms', 'rooms.id', '=', 'event_ploting_rooms.room_id')
            ->select('rooms.*')
            ->selectRaw('(rooms.kapasitas - COALESCE((SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id AND guest_checkins.tanggal_checkout IS NULL), 0)) as bed_sisa')
            ->selectRaw('(SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id AND guest_checkins.tanggal_checkout IS NULL) as bed_terisi')
            ->where('status', 'available')
            ->whereRaw('(rooms.kapasitas - (SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id AND guest_checkins.tanggal_checkout IS NULL)) > 0');



        if ($branchId) {
            $rooms_query->where('branch_id', $branchId);
        }

        $rooms = $rooms_query->get();

        if($branchId) {
            $events = Booking::with('event')->where('unit_destination_id', $branchId)->get();
            $branches = Branch::where('id', $branchId)->get();
        } else {
            $events = Booking::with('event')->get();
            $branches = Branch::all();
        }

        // dump($rooms);

        // dd($rooms);
        // $data['rooms'] = Room::getAvailable($branchId)->get();

        $data['guests'] = $guests;
        $data['rooms'] = $rooms;
        $data['bookings'] = $events;
        $data['branches'] = $branches;
        $data['page_title'] = 'Data Tamu';
        // dd($data);

        return view('pages.guest.index', compact('data'));
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
            $bookings = Booking::with('event')->where('unit_origin_id', $branchId)->get();
            $rooms = Room::where('branch_id', $branchId)->get();
        } else {
            $branches = Branch::all();
            $bookings = Booking::with('event')->get();
            $rooms = Room::all();
        }
        $data['rooms'] = $rooms;
        $data['branches'] = $branches;
        $data['bookings'] = $bookings;

        // dd($data);
        $data['page_title'] = 'Tambah Tamu';
        return view('pages.guest.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $booking = Booking::findOrFail($request->event_id);
        // dd($booking);

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

        $guest->events()->attach($booking->event_id, ['booking_id' => $request->event_id]);
        Alert::success('Success', 'Data Tamu Berhasil Ditambahkan');
        return redirect()->route('guest.index');
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
        $data['selected_events'] = $guest->events()->pluck('events.id')->toArray();
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
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'kendaraan' => 'required|string|max:255',
            'no_polisi' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'event_id' => 'required|exists:events,id',
            'batch' => 'required|string|max:255',
            'kantor_cabang' => 'required|string|max:255',
            'tanggal_rencana_checkin' => 'required|date',
            'tanggal_rencana_checkout' => 'required|date',
        ]);

        $guest = Guest::find($id);

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
        ]);

        $guest->events()->sync([$request->event_id]);

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
            $room = Room::findOrFail($request->input('room_id'));
            $jumlahTamuCheckin = GuestCheckin::where('room_id', $room->id)->count();

            if ($room->branch_id != $guest->branch_id) {
                Alert::error('Error', 'unit Kamar tidak sesuai dengan data tamu.');
                return redirect()->back();
            }

            if ($room->status == 'unavailable') {
                Alert::error('Error', 'Kamar tidak tersedia.');
                return redirect()->back();
            }


            if ($jumlahTamuCheckin >= $room->kapasitas) {
                Alert::error('Error', 'Kamar sudah penuh.');
                return redirect()->back();
            }

            GuestCheckin::updateOrCreate(
                ['guest_id' => $guestId],
                ['room_id' => $room->id]
            );

            Alert::success('Berhasil!', 'Kamar berhasil dipilih untuk tamu.');
        return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function setCheckinDate(Request $request, $guestId)
    {
        $request->validate([
            'checkin_date' => 'required|date',
        ]);

        try {
            $checkinDate = \Carbon\Carbon::parse($request->input('checkin_date'))->toDateString();

            $guest = GuestCheckin::where('guest_id', $guestId)->first();

            if (!$guest) {
                Alert::error('Error', 'Harapkan memilih kamar terlebih dahulu untuk check-in.');
                return redirect()->back();
            }

            if ($guest->tanggal_checkin) {
                Alert::error('Error', 'Tamu sudah melakukan check-in.');
                return redirect()->back();
            }

            GuestCheckin::updateOrCreate(
                ['guest_id' => $guestId],
                [
                    'tanggal_checkin' => $checkinDate,
                ]
            );

            Alert::success('Success', 'Check-in berhasil dilakukan.');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function setCheckoutDate(Request $request, $guestId)
    {
        $request->validate([
            'checkout_date' => 'required|date',
        ]);

        try {
            $checkinDate = \Carbon\Carbon::parse($request->input('checkout_date'))->toDateString();

            $guest = GuestCheckin::where('guest_id', $guestId)->first();

            if (!$guest) {
                Alert::error('Error', 'Harapkan memilih kamar  dan check-in terlebih dahulu untuk check-out.');
                return redirect()->back();
            }

            if (!$guest->tanggal_checkin) {
                Alert::error('Error', 'Tamu belum melakukan check-in.');
                return redirect()->back();
            }

            if ($guest->tanggal_checkout) {
                Alert::error('Error', 'Tamu sudah melakukan check-out.');
                return redirect()->back();
            }

            $guest->update([
                'tanggal_checkout' => $checkinDate,
            ]);

            Alert::success('Success', 'Check-out berhasil dilakukan. Kapasitas kamar diperbarui.');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function bulkPlotRooms(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guests' => 'required|array',
            'guests.*' => 'exists:guests,id', // Pastikan ID tamu valid
        ]);

        // Ambil data kamar
        $room = Room::find($validated['room_id']);

        // Validasi kapasitas kamar
        if ($room->kapasitas < count($validated['guests'])) {
            return response()->json(['message' => 'Jumlah tamu melebihi kapasitas kamar.'], 400);
        }

        // Ambil tamu berdasarkan ID yang dipilih
        $guests = Guest::whereIn('id', $validated['guests'])->get();

        // Proses penugasan tamu ke kamar
        foreach ($guests as $guest) {
            // Cek apakah tamu sudah ada di guest_checkins dengan room_id yang sama
            $existingCheckin = GuestCheckin::where('guest_id', $guest->id)->first();

            if ($existingCheckin) {
                // Jika tamu sudah ada, update room_id-nya
                $existingCheckin->update([
                    'room_id' => $room->id,
                ]);
            } else {
                // Jika tamu belum ada, buat entri baru
                GuestCheckin::create([
                    'guest_id' => $guest->id,
                    'room_id' => $room->id,
                ]);
            }
        }

        return response()->json(['message' => 'Kamar berhasil diploting']);
    }


    public function bulkCheckin(Request $request)
    {
        $guestIds = $request->input('guest_ids');

        if (empty($guestIds)) {
            return response()->json(['message' => 'Data tamu tidak valid.'], 400);
        }

        foreach ($guestIds as $guestId) {
            $guest = Guest::find($guestId);
            if ($guest) {
                GuestCheckin::updateOrCreate(
                    ['guest_id' => $guestId],
                    ['tanggal_checkin' => now()]
                );
            }
        }

        return response()->json(['message' => 'Check-in berhasil dilakukan.']);
    }

    public function bulkCheckout(Request $request)
    {
        $guestIds = $request->input('guest_ids');

        if (empty($guestIds)) {
            return response()->json(['message' => 'Data tamu tidak valid.'], 400);
        }

        foreach ($guestIds as $guestId) {
            $guest = Guest::find($guestId);

            if ($guest) {
                $checkin = GuestCheckin::where('guest_id', $guestId)->first();

                if ($checkin && !$checkin->tanggal_checkout) {
                    $checkin->update(['tanggal_checkout' => now()]);
                }
            }
        }

        return response()->json(['message' => 'Checkout berhasil dilakukan.']);
    }
}
