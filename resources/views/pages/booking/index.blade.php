@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $data['page_title'] }}</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a>
                            </li>
                            @if (isset($data['page_title']))
                                <li class="breadcrumb-item active">{{ $data['page_title'] }}</li>
                            @endif
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Data Booking</h5>
                        <a href="{{ route('booking.create') }}" class="btn btn-primary">
                            <i class="ri-add-fill"></i> <span>Reservasi</span>
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        <table id="scroll-horizontal" class="table align-middle nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Event</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Unit Origin</th>
                                    <th>Unit Destination</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Room Booked</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($data['bookings'] as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->event->nama_kelas }}</td>
                                        <td>{{ $booking->jumlah_peserta }}</td>
                                        <td>{{ $booking->originBranch->name }}</td>
                                        <td>{{ $booking->destinationBranch->name }}</td>
                                        <td>{{ date('d, M Y', strtotime($booking->tanggal_rencana_checkin)) }} </td>
                                        <td>{{ date('d, M Y', strtotime($booking->tanggal_rencana_checkout)) }} </td>
                                        <td>
                                            @if (isset($booking->rooms))
                                                @php
                                                    // Check if 'rooms' is a JSON string (i.e., it's not already an array)
                                                    $rooms = is_array($booking->rooms)
                                                        ? $booking->rooms
                                                        : json_decode($booking->rooms, true);
                                                    $totalRooms = count($rooms); // Get the total number of rooms
                                                @endphp
                                                {{ $totalRooms }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('booking.edit', $booking->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('booking.destroy', $booking->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
