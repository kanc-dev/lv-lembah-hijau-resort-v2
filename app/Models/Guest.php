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
        return $this->belongsToMany(Event::class, 'event_guest')
                ->withPivot('booking_id')
                ->withTimestamps();
    }

    public function guestcheckins()
    {
        return $this->hasMany(GuestCheckin::class);
    }
}
