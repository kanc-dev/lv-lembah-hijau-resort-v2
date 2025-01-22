<?php

use App\Http\Controllers\Api\OccupancyController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class, 'login']);

Route::get('/occupancy/calendar-data', [OccupancyController::class, 'getCalendarDataOccupancy']);
Route::get('/occupancy/room-history', [OccupancyController::class, 'getRoomOccupancy']);
Route::get('/occupancy/event-schedule', [OccupancyController::class, 'getEventTimelineData']);
