<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventPlotingRooms()
    {
        return $this->hasMany(EventPlotingRoom::class, 'room_id');
    }


    public function events()
    {
        return $this->hasManyThrough(
            Event::class,
            GuestCheckin::class,
            'room_id',   // Foreign key di guest_checkins
            'id',        // Primary key di events
            'id',        // Primary key di rooms
            'guest_id'   // Foreign key di guest_checkins
        )->join('event_guest', 'event_guest.event_id', '=', 'events.id')
            ->join('guests', 'guests.id', '=', 'event_guest.guest_id')
            ->select('events.*');
    }

    public function guests()
    {
        return $this->hasMany(Guest::class, 'room_id');
    }

    public function guestCheckins(): HasMany
    {
        return $this->hasMany(GuestCheckin::class, 'room_id');
    }

    public function getSisaBedAttribute()
    {
        return $this->kapasitas - $this->guestCheckins()->count();
    }

    public function getIsAvailableAttribute()
    {
        return $this->getSisaBedAttribute() > 0 && $this->status === 'available';
    }

    public static function getAvailable($branchId = null)
    {
        $query = self::with('branch')
            ->select('rooms.*')
            ->selectRaw('(rooms.kapasitas - (SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id)) as sisa_bed')
            ->where('status', 'available')
            ->whereRaw('(rooms.kapasitas - (SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id)) > 0');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query;
    }

    public function scopeAvailable($query)
    {
        return $query->withCount('guestCheckins')
            ->where('status', 'available')
            ->whereRaw('kapasitas > guest_checkins_count');
    }

    public static function getAvailableRooms($branchId = null, $per_page = null)
    {
        $query = self::withCount('guestCheckins')
            ->where('status', 'available');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($per_page) {
            $query->paginate($per_page);
        } else {
            $query->get();
        }

        $rooms = $query->map(function ($room) {
            $room->sisa_kamar = $room->kapasitas - $room->guest_checkins_count;
            return $room;
        })
            ->filter(function ($room) {
                return $room->sisa_kamar > 0 && $room->status === 'available';
            });

        return $rooms;
    }

    public function getRoomStatus($branchId = null)
    {
        $query = DB::table('rooms')
            ->leftJoin('guest_checkins', 'rooms.id', '=', 'guest_checkins.room_id')
            ->leftJoin('guests', 'guest_checkins.guest_id', '=', 'guests.id')
            ->leftJoin('events', 'rooms.event_id', '=', 'events.id')
            ->select(
                'rooms.id as room_id',
                'rooms.nama as room_name',
                'rooms.tipe as room_type',
                'rooms.kapasitas as total_capacity',
                'rooms.status as room_status',
                'rooms.branch_id',
                'rooms.event_id',
                DB::raw('COUNT(guest_checkins.id) as occupied_beds'),
                DB::raw('rooms.kapasitas - COUNT(guest_checkins.id) as remaining_beds'),
                'events.nama_kelas as event_name',
                DB::raw('GROUP_CONCAT(DISTINCT guests.nama SEPARATOR ", ") as guests'),
                DB::raw('GROUP_CONCAT(DISTINCT guest_checkins.tanggal_checkin SEPARATOR ", ") as checkin_times'),
                DB::raw('GROUP_CONCAT(DISTINCT guest_checkins.tanggal_checkout SEPARATOR ", ") as checkout_times')
            )
            ->groupBy('rooms.id', 'rooms.nama', 'rooms.tipe', 'rooms.kapasitas', 'rooms.status', 'rooms.branch_id', 'rooms.event_id', 'events.nama_kelas');

        if ($branchId) {
            $query->where('rooms.branch_id', $branchId);
        }

        return $query->get();
    }


    public static function getEventPlotingRooms($request)
    {
        $guestId = $request->guest_id;
        $bookingId = $request->booking_id;

        $rooms_query = Room::with('branch')
            ->select('rooms.*')
            ->selectRaw('(rooms.kapasitas - COALESCE((SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id AND guest_checkins.tanggal_checkout IS NULL), 0)) as bed_sisa')
            ->selectRaw('(SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id AND guest_checkins.tanggal_checkout IS NULL) as bed_terisi')
            ->where('status', 'available')
            ->whereRaw('(rooms.kapasitas - (SELECT COUNT(*) FROM guest_checkins WHERE guest_checkins.room_id = rooms.id AND guest_checkins.tanggal_checkout IS NULL)) > 0');

        if ($bookingId) {
            $rooms_query->whereHas('eventPlotingRooms', function ($q) use ($bookingId) {
                $q->where('booking_id', $bookingId);
            });
        }

        $rooms = $rooms_query->get();

        return view('partials.rooms-table', compact('rooms'));
    }
}
