<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
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

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
