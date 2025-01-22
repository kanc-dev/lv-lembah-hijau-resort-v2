<?php

use App\Http\Controllers\Api\OccupancyController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('login', [AuthController::class, 'login']);

Route::get('/occupancy/calendar-data', [OccupancyController::class, 'getCalendarData']);
Route::get('/occupancy/room-history', [OccupancyController::class, 'getRoomHistory']);
Route::get('/occupancy/event-schedule', [OccupancyController::class, 'getEventSchedule']);
Route::get('/occupancy/room-empty-occupied', [OccupancyController::class, 'getRoomEmptyOccupied']);
