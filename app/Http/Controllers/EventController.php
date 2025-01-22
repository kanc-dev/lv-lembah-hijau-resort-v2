<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
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

        $user = Auth::user();
        $branchId = $user->branch_id;

        if ($branchId) {
            $events = Event::with('branch')->where('branch_id', $branchId)->get();
        } else {
            $events = Event::with('branch')->get();
        }
        $data['events'] = $events;
        $data['page_title'] = 'Data Event';
        return view('pages.event.index', compact('data'));
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
        } else {
            $branches = Branch::all();
        }

        $data['branches'] = $branches;
        $data['page_title'] = 'Tambah Event';
        return view('pages.event.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'branch_id' => 'numeric',
        ]);

        Event::create($request->all());

        return redirect()->route('event.index')->with('success', 'Event created successfully');
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'branch_id' => 'numeric',
        ]);

        $event = Event::create([
            'nama_kelas' => $request->nama_kelas,
            'deskripsi' => $request->deskripsi,
            'branch_id' => $request->branch_id
        ]);

        return response()->json($event);
    }


    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $data['event'] = $event;
        $data['page_title'] = 'Edit Event';
        return view('pages.event.edit', compact('data'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'branch_id' => 'numeric',
        ]);

        $event->update($request->all());

        return redirect()->route('event.index')->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('event.index')->with('success', 'Event deleted successfully');
    }
}
