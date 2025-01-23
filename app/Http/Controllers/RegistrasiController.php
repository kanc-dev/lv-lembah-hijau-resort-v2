<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\Request;

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
            $events = Branch::find($branchId)->events()->get();
        } else {
            $branches = Branch::all();
            $events = Event::all();
        }


        $data['branches'] = $branches;
        $data['events'] = $events;

        return view('pages.registrations.index', compact('data', 'branchId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required',
            'branch_id' => 'required|integer',
            'kantor_cabang' => 'required|string',
            'event_id' => 'required|integer',
            'batch' => 'required|string',
            'kendaraan' => 'required|string',
            'no_polisi' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:guests',
            'tanggal_rencana_checkin' => 'required|date',
            'tanggal_rencana_checkout' => 'required|date|after:tanggal_rencana_checkin',
        ]);

        Guest::create($validatedData);

        $branch = Branch::find($validatedData['branch_id'])->name;

        return redirect()->to('registrasi?unit=' . strtolower($branch) . '')->with('success', 'Data tamu berhasil disimpan.');
    }
}
