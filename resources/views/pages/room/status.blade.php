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
                            <button type="submit" class="btn btn-success btn-sm" id="dailyReportButton">
                                <i class="ri-calendar-line" id="buttonIcon"></i>
                                <span id="buttonText">Daily Report</span>
                                <i class="ri-loader-2-line ri-spin" id="loadingIcon" style="display: none;"></i>
                            </button>
                            <div class="btn btn-primary btn-sm">
                                ({{ isset($data['rooms']) && $data['rooms']->sum('terisi') ? number_format(($data['rooms']->sum('terisi') / $data['rooms']->sum('kapasitas')) * 100, 2) : 0 }}%)
                                Occupancy</div>
                        </form>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        <div style="width: 100%; overflow-x: auto;">
                            <table class="table align-middle nowrap table-striped table-hover" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Kamar</th>
                                        @if (!auth()->user()->branch_id)
                                            <th>Unit</th>
                                        @endif
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Total Bed</th>
                                        <th>Bed Terisi</th>
                                        <th>Bed Tersedia</th>
                                        <th>Occupancy</th>
                                        <th>Event</th>
                                        <th>Tamu</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                    </tr>
                                </thead>
                                <tbody class="table-scroll">
                                    @foreach ($data['rooms'] as $room)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $room['nama'] }}</td>
                                            @if (!auth()->user()->branch_id)
                                                <td>{{ $room['branch'] }}</td>
                                            @endif
                                            <td>{{ $room['tipe'] }}</td>
                                            <td class="text-center">
                                                @if ($room['sisa_bed'] <= 0)
                                                    <span class="badge badge-soft-danger">
                                                        Full
                                                    </span>
                                                @else
                                                    <span class="badge badge-soft-success">
                                                        Available
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $room['kapasitas'] }}</td>

                                            <td>{{ $room['terisi'] }}</td>
                                            <td>
                                                @if ($room['sisa_bed'] <= 0)
                                                    <span class="badge badge-soft-danger">
                                                        {{ $room['sisa_bed'] ?? 0 }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-soft-success">
                                                        {{ $room['sisa_bed'] ?? 0 }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ number_format(($room['terisi'] / $room['kapasitas']) * 100, 2) }}%</td>
                                            <td>
                                                @if ($room['event'])
                                                    {{ $room['event'] }}
                                                @elseif ($room['events']->flatten()->first())
                                                    {{ $room['events']->flatten()->first()->nama_kelas }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
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
                                <tfoot>
                                    <tr>
                                        <th colspan="5">Summary</th>
                                        <th>{{ $data['rooms_summary'] ? $data['rooms_summary']->sum('kapasitas') : 0 }}</th>
                                        <th>{{ $data['rooms_summary'] ? $data['rooms_summary']->sum('terisi') : 0 }}</th>
                                        <th>{{ $data['rooms_summary'] ? $data['rooms_summary']->sum('sisa_bed') : 0 }}</th>
                                        <th>{{ $data['rooms_summary'] ? number_format(($data['rooms_summary']->sum('terisi') / $data['rooms_summary']->sum('kapasitas')) * 100, 2) : 0 }}%
                                        </th>
                                        <th colspan="4"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{ $data['rooms']->links() }}
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
