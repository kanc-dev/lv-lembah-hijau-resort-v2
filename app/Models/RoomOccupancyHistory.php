<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomOccupancyHistory extends Model
{
    use HasFactory;


    protected $table = 'room_occupancy_histories';

    protected $guarded = ['id'];

    protected $dates = ['tanggal'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
