<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomReportHistory extends Model
{

    protected $guarded = ['id'];

    protected $casts = [
        'data_history' => 'array', // Automatically casts the JSON column to an array
    ];
}
