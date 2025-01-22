<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
