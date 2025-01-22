<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomReport extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tamu' => 'array',
        'report_date' => 'date',
    ];
}
