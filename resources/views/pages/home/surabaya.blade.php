@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Dashboard</h4>
                        <div class="gap-2 mt-3 d-flex align-items-center">
                            <a href="{{ url('/dashboard') }}" class="btn btn-soft-secondary btn-sm">
                                ALL
                            </a>
                            @php
                                $branchName = request()->segment(2);
                            @endphp
                            @foreach ($data['branch_list'] as $branch)
                                <a href="{{ url('/dashboard/' . strtolower($branch->name)) }}"
                                    class="btn {{ $branchName == strtolower($branch->name) ? 'btn-secondary' : 'btn-soft-secondary' }} btn-sm text-uppercase">
                                    {{ $branch->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript: void(0);">Dashboards</a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-xxl-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Booking</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ $data['total_booking_active'] }}">0</span>
                                                </h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Event</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ $data['total_event_active'] }}">0</span></h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Tamu</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ $data['total_guest_active'] }}">0</span></h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Kamar</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ $data['total_room_active'] }}">0</span></h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Kapasitas</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ $data['total_room_capacity'] }}">0</span></h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Kamar Terisi</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ number_format($data['total_room_occupied'], 0) }}">0</span>%
                                                </h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-md-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="mb-0 fw-medium text-muted">Total Kamar Kosong</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                        data-target="{{ number_format($data['total_room_empty'], 0) }}">0</span>%
                                                </h2>
                                            </div>
                                            <div>
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                                        <i data-feather="activity" class="text-primary"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div> <!-- end col-->


                    <div class="col-xxl-6">
                        <div class="row h-100">
                            <div class="col-xl-12">
                                <div class="card card-height-100">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="mb-0 card-title flex-grow-1">Kamar Terisi / Kosong</h4>
                                    </div>
                                    <div class="card-body">
                                        <x-chart.room-occupancy-chart />
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div> <!-- end col-->

                        </div> <!-- end row-->
                    </div><!-- end col -->
                </div> <!-- end row-->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="border-0 card-header align-items-center d-flex">
                                <h4 class="mb-0 card-title flex-grow-1">History Branch Occupancy</h4>
                                {{-- <di
                                 --}}
                            </div><!-- end card header -->

                            <div class="p-0 pb-2 card-body">
                                <div>
                                    <x-history-branch-occupancy :branchId="$branchId" />
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                </div><!-- end row -->

                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="border-0 card-header">
                                <h4 class="mb-0 card-title">Event Schedule</h4>
                            </div><!-- end cardheader -->
                            <div class="p-0 pb-2 card-body">
                                <div>
                                    <x-event-schedule :branchId="$branchId" />
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="mb-0 card-title flex-grow-1">Event Booking</h4>
                            </div>
                            <div class="card-body">
                                @foreach ($data['event_booking'] as $event)
                                    <div class="mt-3 mini-stats-wid d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span
                                                class="mini-stat-icon avatar-title rounded-circle text-primary bg-soft-primary fs-4">
                                                {{ date('d', strtotime($event['tanggal_rencana_checkin'])) }}
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">{{ $event->event['nama_kelas'] }}</h6>
                                            <p class="mb-0 text-muted">{{ $event->destinationBranch['name'] }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <p class="mb-0 text-muted">
                                                {{ date('M Y', strtotime($event['tanggal_rencana_checkin'])) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-3 text-center">
                                    <a href="javascript:void(0);" class="text-muted text-decoration-underline">View
                                        all Events</a>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div> <!-- end col-->
                </div>

            </div>
        </div>



    </div>
    <!-- container-fluid -->
@endsection

@push('head-script')
@endpush

@push('body-script')
@endpush
