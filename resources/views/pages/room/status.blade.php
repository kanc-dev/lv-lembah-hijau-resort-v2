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
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
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
                        <h5 class="mb-0 card-title">Room Status</h5>
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
                                    <th>Room Name</th>
                                    <th>Branch</th>
                                    <th>Capacity</th>
                                    <th>Occupied</th>
                                    <th>Available</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_capacity = 0;
                                    $total_occupied = 0;
                                    $total_available = 0;
                                @endphp
                                @foreach ($data['rooms'] as $room)
                                    @php
                                        $occupied = $room['total_terisi'] ?? 0;
                                        $available = $room['kapasitas'] - $occupied;
                                        $total_capacity += $room['kapasitas'];
                                        $total_occupied += $occupied;
                                        $total_available += $available;
                                    @endphp
                                    <tr>
                                        <td>{{ $room['id'] }}</td>
                                        <td>{{ $room['unit'] }}</td>
                                        <td>{{ $room['nama_kamar'] }}</td>
                                        <td>{{ $room['kapasitas'] }}</td>
                                        <td>{{ $room['total_terisi'] ?? 0 }}</td>
                                        <td>{{ $room['kapasitas'] - ($room['total_terisi'] ?? 0) }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $room['status'] == 'available' ? 'badge-soft-info' : 'badge-soft-secondary' }}">
                                                {{ ucfirst($room['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td><strong>{{ number_format($total_capacity, 0, ',', '.') }}</strong></td>
                                    <td><strong>{{ number_format($total_occupied, 0, ',', '.') }}</strong></td>
                                    <td><strong>{{ number_format($total_available, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <strong>
                                            {{ number_format(($total_occupied / $total_capacity) * 100, 2) }}% Occupied
                                            <br>
                                            {{ number_format(($total_available / $total_capacity) * 100, 2) }}% Available
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
