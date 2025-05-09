<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    // public function bookings()
    // {
    //     return $this->hasMany(Booking::class);
    // }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'event_guest', 'event_id', 'booking_id')
                    ->withTimestamps();
    }

    public function rooms()
    {
        return $this->hasManyThrough(
            Room::class, // Model tujuan (Room)
            EventPlotingRoom::class, // Model perantara (EventPlotingRoom)
            'booking_id', // Foreign key di EventPlotingRoom yang merujuk ke Booking
            'id', // Foreign key di Room yang merujuk ke EventPlotingRoom
            'id', // Local key di Event
            'room_id' // Local key di EventPlotingRoom
        );
    }

    // public function booking()
    // {
    //     return $this->hasOne(Booking::class);
    // }

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'event_guest')
                ->withPivot('booking_id')
                ->withTimestamps();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // public function rooms()
    // {
    //     return $this->hasMany(Room::class);
    // }
}
