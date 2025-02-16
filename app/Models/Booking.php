<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'rooms' => 'array', // Cast rooms as an array
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function originBranch()
    {
        return $this->belongsTo(Branch::class, 'unit_origin_id');
    }

    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'unit_destination_id');
    }

    public function eventPlotingRooms() {
        return $this->hasMany(EventPlotingRoom::class, 'booking_id');
    }

}
