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
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
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
                        <h5 class="mb-0 card-title">Status Kamar</h5>
                        <form action="{{ route('generate.room.reports') }}" id="dailyReportForm" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" id="dailyReportButton">
                                <i class="ri-calendar-line" id="buttonIcon"></i>
                                <span id="buttonText">Daily Report</span>
                                <i class="ri-loader-2-line ri-spin" id="loadingIcon" style="display: none;"></i>
                            </button>
                        </form>
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
                                    <th>Nama Kamar</th>
                                    <th>Unit</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Kapasitas</th>
                                    <th>Terisi</th>
                                    <th>Tersedia</th>
                                    <th>Event</th>
                                    <th>Tamu</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['rooms'] as $room)
                                    <tr>
                                        <td>{{ $room['id'] }}</td>
                                        <td>{{ $room['nama'] }}</td>
                                        <td>{{ $room['branch'] }}</td>
                                        <td>{{ $room['tipe'] }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $room['status'] == 'available' ? 'badge-soft-info' : 'badge-soft-secondary' }}">
                                                {{ ucfirst($room['status']) }}
                                            </span>
                                        </td>
                                        <td>{{ $room['kapasitas'] }}</td>

                                        <td>{{ $room['terisi'] }}</td>
                                        <td>{{ $room['sisa_bed'] ?? 0 }}</td>
                                        <td>{{ $room['event'] ?? 'N/A' }}</td>
                                        <td>
                                            <div class="flex-wrap gap-1 d-flex">
                                                @foreach ($room['tamu'] as $tamu)
                                                    <span
                                                        class="badge badge-soft-info">{{ $tamu['nama'] ? $tamu['nama'] : 'N/A' }}</span>
                                                @endforeach

                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-wrap gap-1 d-flex">

                                                @foreach ($room['tamu'] as $tamu)
                                                    <span
                                                        class="badge badge-soft-info">{{ $tamu['checkin'] ? date('d, M Y', strtotime($tamu['checkin'])) : 'N/A' }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-wrap gap-1 d-flex">
                                                @foreach ($room['tamu'] as $tamu)
                                                    <span
                                                        class="badge badge-soft-info">{{ $tamu['checkout'] ? date('d, M Y', strtotime($tamu['checkout'])) : 'N/A' }}</span>
                                                @endforeach

                                            </div>
                                        </td>
                                        {{-- <td>{!! $room['tamu'] !!}</td> --}}
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
@push('scripts')
    <script>
        document.getElementById('dailyReportForm').addEventListener('submit', function(event) {
            // Disable the button
            const button = document.getElementById('dailyReportButton');
            const loadingIcon = document.getElementById('loadingIcon');
            const buttonIcon = document.getElementById('buttonIcon');
            const buttonText = document.getElementById('buttonText');

            // Disable the button
            button.disabled = true;

            // Show loading spinner, hide text and icon
            loadingIcon.style.display = 'inline-block';
            buttonIcon.style.display = 'none';
            buttonText.textContent = 'Processing...';
        });
    </script>
@endpush
