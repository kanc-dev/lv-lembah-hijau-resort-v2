<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/branch-guests', [HomeController::class, 'getBranchGuestData'])->name('branch-guests');
    Route::get('/branch-room-occupancy', [HomeController::class, 'getRoomOccupancy'])->name('branch-room-occupancy');


    // Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::resource('booking', BookingController::class);

    Route::get('/guest', [GuestController::class, 'index'])->name('guest.index');
    Route::resource('guest', GuestController::class);
    Route::post('/guest/checkin/{guest}', [GuestController::class, 'checkIn'])->name('guest.checkIn');
    Route::post('/guest/checkout/{guest}', [GuestController::class, 'checkOut'])->name('guest.checkOut');

    Route::get('/guest/rooms/{guestId}', [GuestController::class, 'getAvailableRooms']);


    Route::resource('event', EventController::class);
    Route::post('/event/store_ajax', [EventController::class, 'store_ajax'])->name('event.store_ajax');

    // Route::re('/event', [EventController::class, 'index'])->name('event.index');
    // Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    // Route::post('/event/store', [EventController::class, 'store'])->name('event.store');
    // Route::get('/event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    // Route::put('/event/{id}/update', [EventController::class, 'update'])->name('event.update');
    // Route::delete('/event/{id}/destroy', [EventController::class, 'destroy'])->name('event.destroy');

    // Room
    Route::get('/room', [RoomController::class, 'index'])->name('room.index');
    Route::get('/room-report', [RoomController::class, 'report'])->name('room.report');
    Route::get('/room/create', [RoomController::class, 'create'])->name('room.create');
    Route::post('/room/store', [RoomController::class, 'store'])->name('room.store');
    Route::get('/room/{id}/edit', [RoomController::class, 'edit'])->name('room.edit');
    Route::put('/room/{id}/update', [RoomController::class, 'update'])->name('room.update');
    Route::delete('/room/{id}/destroy', [RoomController::class, 'destroy'])->name('room.destroy');
    Route::get('/available-rooms', [RoomController::class, 'getAvailableRooms'])->name('room.available_rooms');

    Route::get('/status-room', [RoomController::class, 'status'])->name('status.room');


    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/branch', [BranchController::class, 'index'])->name('branch.index');
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
});
