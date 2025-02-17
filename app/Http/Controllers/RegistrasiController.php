<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RegistrasiController extends Controller
{
    public function index(Request $request)
    {
        $branch_list = [
            'bandung' => 1,
            'yogyakarta' => 2,
            'surabaya' => 3,
            'padang' => 4,
            'makassar' => 5
        ];
        $branch = $request->unit;
        $branchId = $branch_list[strtolower($branch)] ?? null;
        if ($branchId) {
            $branches = Branch::where('id', $branchId)->get();
            $bookings = Booking::with('event')->where('branch_id', $branchId)->get();
        } else {
            $branches = Branch::all();
            $bookings = Booking::with('event')->get();
        }


        $data['branches'] = $branches;
        $data['bookings'] = $bookings;

        return view('pages.registrations.index', compact('data', 'branchId'));
    }

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


        $branch = Branch::find($request->branch_id)->name;
        Alert::success('Success', 'Data berhasil disimpan.');
        return redirect()->to('registrasi?unit=' . strtolower($branch) . '')->with('success', 'Data berhasil disimpan.');
    }
}
