<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function originBookings()
    {
        return $this->hasMany(Booking::class, 'unit_origin_id');
    }

    public function destinationBookings()
    {
        return $this->hasMany(Booking::class, 'unit_destination_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
