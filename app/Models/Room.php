<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function guests()
    {
        return $this->hasMany(Guest::class, 'room_id');
    }

    public function guestCheckins()
    {
        return $this->hasMany(GuestCheckin::class, 'room_id');
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
}
