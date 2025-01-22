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


Auth::routes();

Route::get('/registrasi', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/{branchName}', [HomeController::class, 'branch'])->where('branchName', 'bandung|yogyakarta|surabaya|padang|makassar')->name('dashboard.branch');


    Route::get('/branch-guests', [HomeController::class, 'getBranchGuestData'])->name('branch-guests');
    Route::get('/branch-occupancy-chart', [HomeController::class, 'getRoomOccupancy'])->name('branch-occupancy-chart');
    Route::get('/branch-occupancy-accumulated', [HomeController::class, 'getRoomOccupancyAccumulated'])->name('branch-occupancy-accumulated');
    Route::get('/branch-occupancy-pie', [HomeController::class, 'getRoomOccupancyPieChart'])->name('branch-occupancy-pie');
    Route::get('/event-timeline', [HomeController::class, 'getEventTimelineData'])->name('event-timeline');
    Route::get('/room-occupancy-data', [HomeController::class, 'getOccupancyChartData'])->name('room-occupancy-data');

    Route::get('/branch-room-occupancy-data', [HomeController::class, 'getBranchOccupancyChartData'])->name('branch-room-occupancy-data');
    Route::get('/calendar-occupancy-data', [HomeController::class, 'getCalendarDataOccupancy'])->name('calendar-occupancy-data');


    // Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::resource('booking', BookingController::class);

    Route::get('/guest', [GuestController::class, 'index'])->name('guest.index');
    Route::resource('guest', GuestController::class);
    Route::post('/guest/plot-room/{guestId}', [GuestController::class, 'plotRoom'])->name('guest.plotRoom');
    Route::post('/guest/checkin/{guestId}', [GuestController::class, 'setCheckinDate'])->name('guest.checkin');
    Route::post('/guest/checkout/{guestId}', [GuestController::class, 'setCheckoutDate'])->name('guest.checkout');

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
