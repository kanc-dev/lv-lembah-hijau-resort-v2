@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

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

        <div class="row project-wrapper">
            <div class="col-12">
                @if (isset($data['is_admin']) && $data['is_admin'] == true)
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="overflow-hidden flex-grow-1 ms-3">
                                            <p class="mb-3 text-uppercase fw-medium text-muted text-truncate">Unit Aktif
                                            </p>
                                            <div class="mb-3 d-flex align-items-center">
                                                <h4 class="mb-0 fs-4 flex-grow-1"><span class="counter-value"
                                                        data-target="{{ isset($data['branchs']) ? count($data['branchs']) : 0 }}">0</span>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                                <i class='bx bx-building-house text-primary'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-4">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">

                                        <div class="overflow-hidden flex-grow-1 ms-3">
                                            <p class="mb-3 text-uppercase fw-medium text-muted text-truncate">Jumlah Booking
                                            </p>
                                            <div class="mb-3 d-flex align-items-center">
                                                <h4 class="mb-0 fs-4 flex-grow-1"><span class="counter-value"
                                                        data-target="{{ isset($data['bookings']) ? count($data['bookings']) : 0 }}">0</span>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                                <i class='bx bx-calendar-check text-primary'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-4">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">

                                        <div class="overflow-hidden flex-grow-1 ms-3">
                                            <p class="mb-3 text-uppercase fw-medium text-muted text-truncate">Total Kelas
                                            </p>
                                            <div class="mb-3 d-flex align-items-center">
                                                <h4 class="mb-0 fs-4 flex-grow-1"><span class="counter-value"
                                                        data-target="{{ isset($data['events']) ? count($data['events']) : 0 }}">0</span>
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                                <i class='bx bx-calendar-event text-primary'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                @endif

                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                            <i class="align-middle bx bxs-door-open"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 text-uppercase fw-semibold fs-12 text-muted">
                                            Total Kamar</p>
                                        <h4 class="mb-0 "><span class="counter-value"
                                                data-target="{{ $data['total_rooms'] }}">0</span></h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                            <i class="align-middle bx bxs-hotel"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 text-uppercase fw-semibold fs-12 text-muted">
                                            Total Kamar Terisi</p>
                                        <h4 class="mb-0 "><span class="counter-value"
                                                data-target="{{ $data['occupied_rooms'] }}">0</span></h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                            <i class="align-middle bx bxs-bed"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 text-uppercase fw-semibold fs-12 text-muted">Total Kamar Kosong</p>
                                        <h4 class="mb-0 "><span class="counter-value"
                                                data-target="{{ $data['empty_rooms'] }}">0</span></h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                            <i class="align-middle bx bxs-user"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 text-uppercase fw-semibold fs-12 text-muted">Total Tamu</p>
                                        <h4 class="mb-0 "><span class="counter-value"
                                                data-target="{{ $data['total_guests'] }}">0</span></h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="mb-0 card-title flex-grow-1">Selisih Kamar Terisi</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <x-chart.room-occupancy-chart />
                            </div>
                        </div> <!-- .card-->
                    </div> <!-- .col-->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="border-0 card-header align-items-center d-flex">
                                <h4 class="mb-0 card-title flex-grow-1">History Kamar Terisi</h4>
                                <div>
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
                                </div>
                            </div><!-- end card header -->

                            <div class="p-0 pb-2 card-body">
                                <div>
                                    <x-chart.branch-occupancy-bar />
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>


                {{-- <hr class="my-4" /> --}}

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="mb-0 card-title flex-grow-1">Kamar Terisi per {{ date('d, M Y') }}</h4>
                            </div>

                            <div class="p-2 card-body">

                                <div class="row">

                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <x-chart.branch-occupancy-pie />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="d-flex flex-column h-100">

                                            <div class="row">
                                                @if (isset($data['occupancy_of_branch']) && count($data['occupancy_of_branch']) > 0)
                                                    @foreach ($data['occupancy_of_branch'] as $key => $branch)
                                                        <div class="col-md-4">
                                                            <div class="card card-animate">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between">
                                                                        <div>
                                                                            <p class="mb-0 fw-medium text-muted">
                                                                                {{ $branch->name }}</p>
                                                                            <h2 class="mt-4 ff-secondary fw-semibold"><span
                                                                                    class="counter-value"
                                                                                    data-target="{{ $branch->occupancy['occupied'] }}">0</span>
                                                                            </h2>
                                                                            <p class="mb-0 text-muted">
                                                                                @php
                                                                                    $percentageDifference =
                                                                                        $branch->occupancy[
                                                                                            'percentage_difference'
                                                                                        ];
                                                                                    $badgeClass =
                                                                                        $percentageDifference > 0
                                                                                            ? 'text-success'
                                                                                            : ($percentageDifference < 0
                                                                                                ? 'text-danger'
                                                                                                : 'text-info');
                                                                                    $arrowIcon =
                                                                                        $percentageDifference > 0
                                                                                            ? 'ri-arrow-up-line'
                                                                                            : ($percentageDifference < 0
                                                                                                ? 'ri-arrow-down-line'
                                                                                                : 'ri-arrow-right-line');
                                                                                @endphp
                                                                                <span
                                                                                    class="mb-0 badge bg-light {{ $badgeClass }}">
                                                                                    <i
                                                                                        class="align-middle {{ $arrowIcon }}"></i>
                                                                                    {{ number_format($percentageDifference, 2) }}
                                                                                    %
                                                                                </span>
                                                                                <small>
                                                                                    dari
                                                                                    {{ date('d, M Y', strtotime($branch->occupancy['yesterday'])) }}
                                                                                </small>
                                                                            </p>
                                                                        </div>
                                                                        <div>
                                                                            <div class="flex-shrink-0 avatar-sm">
                                                                                <span
                                                                                    class="rounded avatar-title bg-soft-primary fs-3">
                                                                                    <i
                                                                                        class="bx bx-door-open text-primary"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div><!-- end card body -->
                                                            </div> <!-- end card-->
                                                        </div> <!-- end col-->
                                                    @endforeach
                                                @endif

                                            </div> <!-- end row-->
                                        </div>
                                    </div> <!-- end col-->
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->


                </div>

                @if (isset($data['is_admin']) && $data['is_admin'] == true)
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="border-0 card-header align-items-center d-flex">
                                    <h4 class="mb-0 card-title flex-grow-1">History Kamar Terisi</h4>
                                    <div>
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
                                    </div>
                                </div><!-- end card header -->

                                <div class="p-0 pb-2 card-body">
                                    <div>
                                        <x-chart.branch-occupancy-chart />
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="border-0 card-header">
                                    <h4 class="mb-0 card-title">Event Schedule</h4>
                                </div><!-- end cardheader -->
                                <div class="p-0 pb-2 card-body">
                                    <div>
                                        <x-chart.event-timeline-chart />
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->



                    </div><!-- end row -->
                @endif
            </div>



            {{-- <div class="col-12">
                <div class="card">
                    <div class="border-0 card-header">
                        <h4 class="mb-0 card-title">Event Schedules</h4>
                    </div><!-- end cardheader -->
                    <div class="pt-0 card-body">
                        <div class="upcoming-scheduled">
                            <input type="text" class="form-control" data-provider="flatpickr"
                                data-date-format="d M, Y" data-deafult-date="today" data-inline-date="true">
                        </div>

                        <h6 class="mt-4 mb-3 text-uppercase fw-semibold text-muted">Events:</h6>
                        <div class="mt-3 mini-stats-wid d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-primary bg-soft-primary fs-4">
                                    09
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Development planning</h6>
                                <p class="mb-0 text-muted">iTest Factory </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0 text-muted">9:20 <span class="text-uppercase">am</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mt-3 mini-stats-wid d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-primary bg-soft-primary fs-4">
                                    12
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Design new UI and check sales</h6>
                                <p class="mb-0 text-muted">Meta4Systems</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0 text-muted">11:30 <span class="text-uppercase">am</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mt-3 mini-stats-wid d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-primary bg-soft-primary fs-4">
                                    25
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Weekly catch-up </h6>
                                <p class="mb-0 text-muted">Nesta Technologies</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0 text-muted">02:00 <span class="text-uppercase">pm</span></p>
                            </div>
                        </div><!-- end -->
                        <div class="mt-3 mini-stats-wid d-flex align-items-center">
                            <div class="flex-shrink-0 avatar-sm">
                                <span class="mini-stat-icon avatar-title rounded-circle text-primary bg-soft-primary fs-4">
                                    27
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">James Bangs (Client) Meeting</h6>
                                <p class="mb-0 text-muted">Nesta Technologies</p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0 text-muted">03:45 <span class="text-uppercase">pm</span></p>
                            </div>
                        </div><!-- end -->

                        <div class="mt-3 text-center">
                            <a href="javascript:void(0);" class="text-muted text-decoration-underline">View all Events</a>
                        </div>

                    </div><!-- end cardbody -->
                </div><!-- end card -->
            </div> --}}
        </div><!-- end row -->


    </div>
    <!-- container-fluid -->
@endsection
