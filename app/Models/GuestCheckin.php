<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCheckin extends Model
{
    protected $guarded = ['id'];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
