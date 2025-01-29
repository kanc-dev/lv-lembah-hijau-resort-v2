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
use Illuminate\Support\Facades\DB;

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
            $rooms = Room::with('branch')->where('branch_id', $branchId)->get();
            $guests = Guest::with('branch.rooms', 'events', 'guestcheckins.room')->where('branch_id', $branchId)->get();
        } else {
            $rooms = Room::with('branch')->get();
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
