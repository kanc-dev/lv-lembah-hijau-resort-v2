@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Dashboard</h4>
                        @if (isset($data['is_admin']) && $data['is_admin'] == true)
                            <div class="gap-2 mt-3 d-flex align-items-center">
                                <a href="{{ url('/dashboard') }}"
                                    class="btn {{ request()->is('dashboard') ? 'btn-secondary' : 'btn-soft-secondary' }} btn-sm">
                                    ALL
                                </a>
                                @php
                                    $branchName = request()->segment(2);
                                @endphp
                                @foreach ($data['branch_list'] as $branch)
                                    <a href="{{ url('/dashboard') . '/' . strtolower($branch->name) }}"
                                        class="btn btn-soft-secondary btn-sm text-uppercase">
                                        {{ $branch->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
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

        @if (isset($data['is_admin']) && $data['is_admin'] == true)
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-xxl-5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="mb-0 fw-medium text-muted">Total Unit</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                            data-target="{{ $data['total_branch_active'] }}">0</span></h2>
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
                                                    <p class="mb-0 fw-medium text-muted">Total Booking</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                            data-target="{{ $data['total_booking_active'] }}">0</span></h2>
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

                        <div class="col-xxl-7">
                            <div class="row h-100">
                                <div class="col-xl-6">
                                    <div class="card card-height-100">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="mb-0 card-title flex-grow-1">Persentase Occupancy</h4>
                                        </div><!-- end card header -->

                                        <!-- card body -->
                                        <div class="card-body">
                                            <x-chart.branch-occupancy-pie />

                                            <div class="mt-3 table-responsive table-card">
                                                <table
                                                    class="table mb-1 align-middle table-borderless table-sm table-centered table-nowrap">
                                                    <thead
                                                        class="border border-dashed text-muted border-start-0 border-end-0 bg-soft-light">
                                                        <tr>
                                                            <th>Branch / Unit</th>
                                                            <th style="width: 30%;">Kapasitas</th>
                                                            <th style="width: 30%;">Terisi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="border-0">
                                                        @foreach ($data['occupancy_of_branch'] as $occupancy)
                                                            <tr>
                                                                <td>{{ $occupancy['name'] }}</td>
                                                                <td>{{ $occupancy['occupancy']['total'] }}</td>
                                                                <td>{{ $occupancy['occupancy']['occupied'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- end card body -->
                                    </div><!-- end card -->
                                </div><!-- end col -->

                                <div class="col-xl-6">
                                    <div class="card card-height-100">
                                        <div class="card-header align-items-center d-flex">
                                            <h4 class="mb-0 card-title flex-grow-1">Kamar Terisi / Kosong</h4>
                                        </div>
                                        <div class="card-body">
                                            {{-- <x-chart.room-occupancy-chart /> --}}
                                            <x-room-empty-occupied :branchId="$branchId" />
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
                                    {{-- <div>
                                        <button type="button" class="btn btn-soft-secondary btn-sm"
                                            onclick="updateChart('daily')">Daily</button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm"
                                            onclick="updateChart('1M')">1M</button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm"
                                            onclick="updateChart('6M')">6M</button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm"
                                            onclick="updateChart('1Y')">1Y</button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm"
                                            onclick="updateChart('ALL')">ALL</button>
                                    </div> --}}
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

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="border-0 card-header">
                                    <h4 class="mb-0 card-title">Calender Occupancy</h4>
                                </div><!-- end cardheader -->
                                <div class="p-0 pb-2 card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="gap-2 d-flex flex-column">
                                                        <button class="btn btn-secondary w-100 filter-branch active"
                                                            data-branch-id="">Semua</button>
                                                        @foreach ($data['branchs'] as $branch)
                                                            <button class="btn btn-outline-secondary w-100 filter-branch"
                                                                data-branch-id="{{ $branch->id }}">
                                                                {{ $branch->name }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <x-chart.calendar-occupancy-data />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        @else
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
                                            <x-room-empty-occupied :branchId="$branchId" />
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
        @endif



    </div>
    <!-- container-fluid -->
@endsection

@push('head-script')
@endpush

@push('body-script')
@endpush
