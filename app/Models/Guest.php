<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    /** @use HasFactory<\Database\Factories\GuestFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }


    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_guest', 'guest_id', 'event_id')
                    ->withPivot('booking_id')
                    ->withTimestamps();
    }

    public function guestcheckins()
    {
        return $this->hasMany(GuestCheckin::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'event_guest', 'guest_id', 'booking_id')
                    ->withTimestamps();
    }

    public function rooms()
    {
        return $this->hasManyThrough(
            Room::class, // Model tujuan (Room)
            EventPlotingRoom::class, // Model perantara (EventPlotingRoom)
            'booking_id', // Foreign key di EventPlotingRoom yang merujuk ke Booking
            'id', // Foreign key di Room yang merujuk ke EventPlotingRoom
            'id', // Local key di Guest
            'room_id' // Local key di EventPlotingRoom
        )->whereIn('event_ploting_rooms.booking_id', function ($query) {
            $query->select('booking_id')
                  ->from('event_guest')
                  ->where('guest_id', $this->id);
        });
    }


}
