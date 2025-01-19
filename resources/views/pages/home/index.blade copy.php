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
        <div class="col-xxl-8">
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

            {{-- <hr class="my-4" /> --}}

            <div class="row">
                <div class="col-xl-12">
                    <div class="card crm-widget">
                        <div class="p-0 card-body">
                            <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                                <div class="col">
                                    <div class="px-3 py-4">
                                        <h5 class="text-muted text-uppercase fs-13">Campaign Sent <i
                                                class="align-middle ri-arrow-up-circle-line text-success fs-18 float-end"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-space-ship-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value"
                                                        data-target="197">197</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="px-3 py-4 mt-3 mt-md-0">
                                        <h5 class="text-muted text-uppercase fs-13">Annual Profit <i
                                                class="align-middle ri-arrow-up-circle-line text-success fs-18 float-end"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value"
                                                        data-target="489.4">489.4</span>k</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="px-3 py-4 mt-3 mt-md-0">
                                        <h5 class="text-muted text-uppercase fs-13">Lead Coversation <i
                                                class="align-middle ri-arrow-down-circle-line text-danger fs-18 float-end"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-pulse-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value"
                                                        data-target="32.89">32.89</span>%</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="px-3 py-4 mt-3 mt-lg-0">
                                        <h5 class="text-muted text-uppercase fs-13">Daily Average Income <i
                                                class="align-middle ri-arrow-up-circle-line text-success fs-18 float-end"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-trophy-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value"
                                                        data-target="1596.5">1,596.5</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col">
                                    <div class="px-3 py-4 mt-3 mt-lg-0">
                                        <h5 class="text-muted text-uppercase fs-13">Annual Deals <i
                                                class="align-middle ri-arrow-down-circle-line text-danger fs-18 float-end"></i>
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-service-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value"
                                                        data-target="2659">2,659</span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="overflow-hidden flex-grow-1">
                                    <p class="mb-0 text-uppercase fw-medium text-muted text-truncate">
                                        Total Kamar</p>
                                </div>
                                {{-- <div class="flex-shrink-0">
                                        <h5 class="mb-0 text-success fs-14">
                                            <i class="align-middle ri-arrow-right-up-line fs-13"></i>
                                            +16.24 %
                                        </h5>
                                    </div> --}}
                            </div>
                            <div class="mt-4 d-flex align-items-end justify-content-between">
                                <div>
                                    <h4 class="mb-4 fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                            data-target="1000">0</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 avatar-sm">
                                    <span class="rounded avatar-title bg-soft-primary fs-3">
                                        <i class="bx bx-dollar-circle text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="overflow-hidden flex-grow-1">
                                    <p class="mb-0 text-uppercase fw-medium text-muted text-truncate">
                                        Orders</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="mb-0 text-danger fs-14">
                                        <i class="align-middle ri-arrow-right-down-line fs-13"></i>
                                        -3.57 %
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-4 d-flex align-items-end justify-content-between">
                                <div>
                                    <h4 class="mb-4 fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                            data-target="36894">36,894</span></h4>
                                    <a href="#" class="link-secondary text-decoration-underline">View all
                                        orders</a>
                                </div>
                                <div class="flex-shrink-0 avatar-sm">
                                    <span class="rounded avatar-title bg-soft-primary fs-3">
                                        <i class="bx bx-shopping-bag text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="overflow-hidden flex-grow-1">
                                    <p class="mb-0 text-uppercase fw-medium text-muted text-truncate">
                                        Customers</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="mb-0 text-success fs-14">
                                        <i class="align-middle ri-arrow-right-up-line fs-13"></i>
                                        +29.08 %
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-4 d-flex align-items-end justify-content-between">
                                <div>
                                    <h4 class="mb-4 fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                            data-target="183.35">183.35</span>M
                                    </h4>
                                    <a href="" class="link-secondary text-decoration-underline">See
                                        details</a>
                                </div>
                                <div class="flex-shrink-0 avatar-sm">
                                    <span class="rounded avatar-title bg-soft-primary fs-3">
                                        <i class="bx bx-user-circle text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="overflow-hidden flex-grow-1">
                                    <p class="mb-0 text-uppercase fw-medium text-muted text-truncate">
                                        My Balance</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="mb-0 text-muted fs-14">
                                        +0.00 %
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-4 d-flex align-items-end justify-content-between">
                                <div>
                                    <h4 class="mb-4 fs-22 fw-semibold ff-secondary">$<span class="counter-value"
                                            data-target="165.89">165.89</span>k
                                    </h4>
                                    <a href="" class="link-secondary text-decoration-underline">Withdraw
                                        money</a>
                                </div>
                                <div class="flex-shrink-0 avatar-sm">
                                    <span class="rounded avatar-title bg-soft-primary fs-3">
                                        <i class="bx bx-wallet text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>

            <div class="row">
                <div class="col-xl-3">
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
                <div class="col-xl-3">
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
                <div class="col-xl-3">
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
                <div class="col-xl-3">
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

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="border-0 card-header align-items-center d-flex">
                            <h4 class="mb-0 card-title flex-grow-1">Projects Overview</h4>
                            <div>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    ALL
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    1M
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    6M
                                </button>
                                <button type="button" class="btn btn-soft-primary btn-sm">
                                    1Y
                                </button>
                            </div>
                        </div><!-- end card header -->

                        <div class="p-0 border-0 card-header bg-soft-light">
                            <div class="text-center row g-0">
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value" data-target="9851">0</span>
                                        </h5>
                                        <p class="mb-0 text-muted">Number of Projects</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value" data-target="1026">0</span>
                                        </h5>
                                        <p class="mb-0 text-muted">Active Projects</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1">$<span class="counter-value"
                                                data-target="228.89">0</span>k
                                        </h5>
                                        <p class="mb-0 text-muted">Revenue</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                                        <h5 class="mb-1 text-success"><span class="counter-value"
                                                data-target="10589">0</span>h</h5>
                                        <p class="mb-0 text-muted">Working Hours</p>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </div><!-- end card header -->
                        <div class="p-0 pb-2 card-body">
                            <div>
                                <div id="projects-overview-chart"
                                    data-colors='["--vz-primary", "--vz-primary-rgb, 0.1", "--vz-primary-rgb, 0.50"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end col -->

        <div class="col-xxl-4">
            <div class="card">
                <div class="border-0 card-header">
                    <h4 class="mb-0 card-title">Upcoming Schedules</h4>
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
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xl-7">
            <div class="card card-height-100">
                <div class="card-header d-flex align-items-center">
                    <h4 class="mb-0 card-title flex-grow-1">Active Projects</h4>
                    <div class="flex-shrink-0">
                        <a href="javascript:void(0);" class="btn btn-soft-secondary btn-sm">Export Report</a>
                    </div>
                </div><!-- end cardheader -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap table-centered">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Project Lead</th>
                                    <th scope="col">Progress</th>
                                    <th scope="col">Assignee</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 10%;">Due Date</th>
                                </tr><!-- end tr -->
                            </thead><!-- thead -->

                            <tbody>
                                <tr>
                                    <td class="fw-medium">Brand Logo Design</td>
                                    <td>
                                        <img src="assets/images/users/avatar-1.jpg"
                                            class="avatar-xxs rounded-circle me-1" alt="">
                                        <a href="javascript: void(0);" class="text-reset">Donald Risher</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-1 text-muted fs-13">53%</div>
                                            <div class="progress progress-sm flex-grow-1" style="width: 68%;">
                                                <div class="rounded progress-bar bg-primary" role="progressbar"
                                                    style="width: 53%" aria-valuenow="53" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-group flex-nowrap">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-3.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-soft-warning">Inprogress</span></td>
                                    <td class="text-muted">06 Sep 2021</td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="fw-medium">Redesign - Landing Page</td>
                                    <td>
                                        <img src="assets/images/users/avatar-2.jpg"
                                            class="avatar-xxs rounded-circle me-1" alt="">
                                        <a href="javascript: void(0);" class="text-reset">Prezy William</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 text-muted me-1">0%</div>
                                            <div class="progress progress-sm flex-grow-1" style="width: 68%;">
                                                <div class="rounded progress-bar bg-primary" role="progressbar"
                                                    style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-5.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-6.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-soft-danger">Pending</span></td>
                                    <td class="text-muted">13 Nov 2021</td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="fw-medium">Multipurpose Landing Template</td>
                                    <td>
                                        <img src="assets/images/users/avatar-3.jpg"
                                            class="avatar-xxs rounded-circle me-1" alt="">
                                        <a href="javascript: void(0);" class="text-reset">Boonie Hoynas</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 text-muted me-1">100%</div>
                                            <div class="progress progress-sm flex-grow-1" style="width: 68%;">
                                                <div class="rounded progress-bar bg-primary" role="progressbar"
                                                    style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-7.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-8.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-soft-success">Completed</span></td>
                                    <td class="text-muted">26 Nov 2021</td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="fw-medium">Chat Application</td>
                                    <td>
                                        <img src="assets/images/users/avatar-5.jpg"
                                            class="avatar-xxs rounded-circle me-1" alt="">
                                        <a href="javascript: void(0);" class="text-reset">Pauline Moll</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 text-muted me-1">64%</div>
                                            <div class="progress flex-grow-1 progress-sm" style="width: 68%;">
                                                <div class="rounded progress-bar bg-primary" role="progressbar"
                                                    style="width: 64%" aria-valuenow="64" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-soft-warning">Progress</span></td>
                                    <td class="text-muted">15 Dec 2021</td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="fw-medium">Create Wireframe</td>
                                    <td>
                                        <img src="assets/images/users/avatar-6.jpg"
                                            class="avatar-xxs rounded-circle me-1" alt="">
                                        <a href="javascript: void(0);" class="text-reset">James Bangs</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 text-muted me-1">77%</div>
                                            <div class="progress flex-grow-1 progress-sm" style="width: 68%;">
                                                <div class="rounded progress-bar bg-primary" role="progressbar"
                                                    style="width: 77%" aria-valuenow="77" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-group">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-6.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-4.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-soft-warning">Progress</span></td>
                                    <td class="text-muted">21 Dec 2021</td>
                                </tr><!-- end tr -->
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div>

                    <div class="mt-4 align-items-center mt-xl-3 justify-content-between d-flex">
                        <div class="flex-shrink-0">
                            <div class="text-muted">Showing <span class="fw-semibold">5</span> of <span
                                    class="fw-semibold">25</span> Results
                            </div>
                        </div>
                        <ul class="mb-0 pagination pagination-separated pagination-sm">
                            <li class="page-item disabled">
                                <a href="#" class="page-link">←</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="page-item active">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">→</a>
                            </li>
                        </ul>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-5">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="py-1 mb-0 card-title flex-grow-1">My Tasks</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted">All Tasks <i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">All Tasks</a>
                                <a class="dropdown-item" href="#">Completed </a>
                                <a class="dropdown-item" href="#">Inprogress</a>
                                <a class="dropdown-item" href="#">Pending</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table mb-0 align-middle table-borderless table-nowrap table-centered">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Dedline</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Assignee</th>
                                </tr>
                            </thead><!-- end thead -->
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" value=""
                                                id="checkTask1">
                                            <label class="form-check-label ms-1" for="checkTask1">
                                                Create new Admin Template
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-muted">03 Nov 2021</td>
                                    <td><span class="badge badge-soft-success">Completed</span></td>
                                    <td>
                                        <a href="javascript: void(0);" class="d-inline-block"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                            data-bs-original-title="Mary Stoner">
                                            <img src="assets/images/users/avatar-2.jpg" alt=""
                                                class="rounded-circle avatar-xxs">
                                        </a>
                                    </td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" value=""
                                                id="checkTask2">
                                            <label class="form-check-label ms-1" for="checkTask2">
                                                Marketing Coordinator
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-muted">17 Nov 2021</td>
                                    <td><span class="badge badge-soft-warning">Progress</span></td>
                                    <td>
                                        <a href="javascript: void(0);" class="d-inline-block"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                            data-bs-original-title="Den Davis">
                                            <img src="assets/images/users/avatar-7.jpg" alt=""
                                                class="rounded-circle avatar-xxs">
                                        </a>
                                    </td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" value=""
                                                id="checkTask3">
                                            <label class="form-check-label ms-1" for="checkTask3">
                                                Administrative Analyst
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-muted">26 Nov 2021</td>
                                    <td><span class="badge badge-soft-success">Completed</span></td>
                                    <td>
                                        <a href="javascript: void(0);" class="d-inline-block"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                            data-bs-original-title="Alex Brown">
                                            <img src="assets/images/users/avatar-6.jpg" alt=""
                                                class="rounded-circle avatar-xxs">
                                        </a>
                                    </td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" value=""
                                                id="checkTask4">
                                            <label class="form-check-label ms-1" for="checkTask4">
                                                E-commerce Landing Page
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-muted">10 Dec 2021</td>
                                    <td><span class="badge badge-soft-danger">Pending</span></td>
                                    <td>
                                        <a href="javascript: void(0);" class="d-inline-block"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                            data-bs-original-title="Prezy Morin">
                                            <img src="assets/images/users/avatar-5.jpg" alt=""
                                                class="rounded-circle avatar-xxs">
                                        </a>
                                    </td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" value=""
                                                id="checkTask5">
                                            <label class="form-check-label ms-1" for="checkTask5">
                                                UI/UX Design
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-muted">22 Dec 2021</td>
                                    <td><span class="badge badge-soft-warning">Progress</span></td>
                                    <td>
                                        <a href="javascript: void(0);" class="d-inline-block"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                            data-bs-original-title="Stine Nielsen">
                                            <img src="assets/images/users/avatar-1.jpg" alt=""
                                                class="rounded-circle avatar-xxs">
                                        </a>
                                    </td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" value=""
                                                id="checkTask6">
                                            <label class="form-check-label ms-1" for="checkTask6">
                                                Projects Design
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-muted">31 Dec 2021</td>
                                    <td><span class="badge badge-soft-danger">Pending</span></td>
                                    <td>
                                        <a href="javascript: void(0);" class="d-inline-block"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                            data-bs-original-title="Jansh William">
                                            <img src="assets/images/users/avatar-4.jpg" alt=""
                                                class="rounded-circle avatar-xxs">
                                        </a>
                                    </td>
                                </tr><!-- end -->
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div>
                    <div class="mt-3 text-center">
                        <a href="javascript:void(0);" class="text-muted text-decoration-underline">Load More</a>
                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xxl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="mb-0 card-title flex-grow-1">Team Members</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                    class="text-muted">Last 30 Days<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Yesterday</a>
                                <a class="dropdown-item" href="#">Last 7 Days</a>
                                <a class="dropdown-item" href="#">Last 30 Days</a>
                                <a class="dropdown-item" href="#">This Month</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <div class="table-responsive table-card">
                        <table class="table mb-0 align-middle table-borderless table-nowrap">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col">Member</th>
                                    <th scope="col">Hours</th>
                                    <th scope="col">Tasks</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-1.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">Donald Risher</h5>
                                            <p class="mb-0 fs-12 text-muted">Product Manager</p>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">110h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        258
                                    </td>
                                    <td style="width:5%;">
                                        <div id="radialBar_chart_1" data-colors='["--vz-primary"]'
                                            data-chart-series="50" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-2.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">Jansh Brown</h5>
                                            <p class="mb-0 fs-12 text-muted">Lead Developer</p>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">83h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        105
                                    </td>
                                    <td>
                                        <div id="radialBar_chart_2" data-colors='["--vz-primary"]'
                                            data-chart-series="45" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">Carroll Adams</h5>
                                            <p class="mb-0 fs-12 text-muted">Lead Designer</p>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">58h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        75
                                    </td>
                                    <td>
                                        <div id="radialBar_chart_3" data-colors='["--vz-primary"]'
                                            data-chart-series="75" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-4.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">William Pinto</h5>
                                            <p class="mb-0 fs-12 text-muted">UI/UX Designer</p>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">96h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        85
                                    </td>
                                    <td>
                                        <div id="radialBar_chart_4" data-colors='["--vz-primary"]'
                                            data-chart-series="25" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">Garry Fournier</h5>
                                            <p class="mb-0 fs-12 text-muted">Web Designer</p>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">76h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        69
                                    </td>
                                    <td>
                                        <div id="radialBar_chart_5" data-colors='["--vz-primary"]'
                                            data-chart-series="60" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-5.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">Susan Denton</h5>
                                            <p class="mb-0 fs-12 text-muted">Lead Designer</p>
                                        </div>
                                    </td>

                                    <td>
                                        <h6 class="mb-0">123h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        658
                                    </td>
                                    <td>
                                        <div id="radialBar_chart_6" data-colors='["--vz-primary"]'
                                            data-chart-series="85" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                                <tr>
                                    <td class="d-flex">
                                        <img src="assets/images/users/avatar-3.jpg" alt=""
                                            class="avatar-xs rounded-3 me-2">
                                        <div>
                                            <h5 class="mb-0 fs-13">Joseph Jackson</h5>
                                            <p class="mb-0 fs-12 text-muted">React Developer</p>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">117h : <span class="text-muted">150h</span></h6>
                                    </td>
                                    <td>
                                        125
                                    </td>
                                    <td>
                                        <div id="radialBar_chart_7" data-colors='["--vz-primary"]'
                                            data-chart-series="70" class="apex-charts" dir="ltr"></div>
                                    </td>
                                </tr><!-- end tr -->
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xxl-4 col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="mb-0 card-title flex-grow-1">Chat</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted"><i class="align-middle ri-settings-4-line me-1"></i>Setting
                                    <i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i
                                        class="align-bottom ri-user-2-fill text-muted me-2"></i> View Profile</a>
                                <a class="dropdown-item" href="#"><i
                                        class="align-bottom ri-inbox-archive-line text-muted me-2"></i> Archive</a>
                                <a class="dropdown-item" href="#"><i
                                        class="align-bottom ri-mic-off-line text-muted me-2"></i> Muted</a>
                                <a class="dropdown-item" href="#"><i
                                        class="align-bottom ri-delete-bin-5-line text-muted me-2"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="p-0 card-body">
                    <div id="users-chat">
                        <div class="p-3 chat-conversation" id="chat-conversation" data-simplebar
                            style="height: 400px;">
                            <ul class="list-unstyled chat-conversation-list chat-sm" id="users-conversation">
                                <li class="chat-list left">
                                    <div class="conversation-list">
                                        <div class="chat-avatar">
                                            <img src="assets/images/users/avatar-2.jpg" alt="">
                                        </div>
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0 ctext-content">Good morning 😊</p>
                                                </div>
                                                <div class="dropdown align-self-start message-box-drop">
                                                    <a class="dropdown-toggle" href="#" role="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-file-copy-line me-2 text-muted"></i>Copy</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                        <a class="dropdown-item delete-item" href="#"><i
                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="conversation-name"><small class="text-muted time">09:07
                                                    am</small> <span class="text-success check-message-icon"><i
                                                        class="align-bottom ri-check-double-line"></i></span></div>
                                        </div>
                                    </div>
                                </li>
                                <!-- chat-list -->

                                <li class="chat-list right">
                                    <div class="conversation-list">
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0 ctext-content">Good morning, How are you? What about
                                                        our next meeting?</p>
                                                </div>
                                                <div class="dropdown align-self-start message-box-drop">
                                                    <a class="dropdown-toggle" href="#" role="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-file-copy-line me-2 text-muted"></i>Copy</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                        <a class="dropdown-item delete-item" href="#"><i
                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="conversation-name"><small class="text-muted time">09:08
                                                    am</small> <span class="text-success check-message-icon"><i
                                                        class="align-bottom ri-check-double-line"></i></span></div>
                                        </div>
                                    </div>
                                </li>
                                <!-- chat-list -->

                                <li class="chat-list left">
                                    <div class="conversation-list">
                                        <div class="chat-avatar">
                                            <img src="assets/images/users/avatar-2.jpg" alt="">
                                        </div>
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0 ctext-content">Yeah everything is fine. Our next
                                                        meeting tomorrow at 10.00 AM</p>
                                                </div>
                                                <div class="dropdown align-self-start message-box-drop">
                                                    <a class="dropdown-toggle" href="#" role="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-file-copy-line me-2 text-muted"></i>Copy</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                        <a class="dropdown-item delete-item" href="#"><i
                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0 ctext-content">Hey, I'm going to meet a friend of
                                                        mine at the department store. I have to buy some presents for my
                                                        parents 🎁.</p>
                                                </div>
                                                <div class="dropdown align-self-start message-box-drop">
                                                    <a class="dropdown-toggle" href="#" role="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-file-copy-line me-2 text-muted"></i>Copy</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                        <a class="dropdown-item delete-item" href="#"><i
                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="conversation-name"><small class="text-muted time">09:10
                                                    am</small> <span class="text-success check-message-icon"><i
                                                        class="align-bottom ri-check-double-line"></i></span></div>
                                        </div>
                                    </div>
                                </li>
                                <!-- chat-list -->

                                <li class="chat-list right">
                                    <div class="conversation-list">
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0 ctext-content">Wow that's great</p>
                                                </div>
                                                <div class="dropdown align-self-start message-box-drop">
                                                    <a class="dropdown-toggle" href="#" role="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-file-copy-line me-2 text-muted"></i>Copy</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                        <a class="dropdown-item delete-item" href="#"><i
                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="conversation-name"><small class="text-muted time">09:12
                                                    am</small> <span class="text-success check-message-icon"><i
                                                        class="align-bottom ri-check-double-line"></i></span></div>
                                        </div>
                                    </div>
                                </li>
                                <!-- chat-list -->

                                <li class="chat-list left">
                                    <div class="conversation-list">
                                        <div class="chat-avatar">
                                            <img src="assets/images/users/avatar-2.jpg" alt="">
                                        </div>
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="mb-0 message-img">
                                                    <div class="message-img-list">
                                                        <div>
                                                            <a class="popup-img d-inline-block"
                                                                href="assets/images/small/img-1.jpg">
                                                                <img src="assets/images/small/img-1.jpg"
                                                                    alt="" class="border rounded">
                                                            </a>
                                                        </div>
                                                        <div class="message-img-link">
                                                            <ul class="mb-0 list-inline">
                                                                <li class="list-inline-item dropdown">
                                                                    <a class="dropdown-toggle" href="#"
                                                                        role="button" data-bs-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false">
                                                                        <i class="ri-more-fill"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                            href="assets/images/small/img-1.jpg"
                                                                            download=""><i
                                                                                class="align-bottom ri-download-2-line me-2 text-muted"></i>Download</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                                        <a class="dropdown-item delete-item"
                                                                            href="#"><i
                                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="message-img-list">
                                                        <div>
                                                            <a class="popup-img d-inline-block"
                                                                href="assets/images/small/img-2.jpg">
                                                                <img src="assets/images/small/img-2.jpg"
                                                                    alt="" class="border rounded">
                                                            </a>
                                                        </div>
                                                        <div class="message-img-link">
                                                            <ul class="mb-0 list-inline">
                                                                <li class="list-inline-item dropdown">
                                                                    <a class="dropdown-toggle" href="#"
                                                                        role="button" data-bs-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false">
                                                                        <i class="ri-more-fill"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                            href="assets/images/small/img-2.jpg"
                                                                            download=""><i
                                                                                class="align-bottom ri-download-2-line me-2 text-muted"></i>Download</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="align-bottom ri-reply-line me-2 text-muted"></i>Reply</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="align-bottom ri-share-line me-2 text-muted"></i>Forward</a>
                                                                        <a class="dropdown-item" href="#"><i
                                                                                class="align-bottom ri-bookmark-line me-2 text-muted"></i>Bookmark</a>
                                                                        <a class="dropdown-item delete-item"
                                                                            href="#"><i
                                                                                class="align-bottom ri-delete-bin-5-line me-2 text-muted"></i>Delete</a>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="conversation-name"><small class="text-muted time">09:30
                                                    am</small> <span class="text-success check-message-icon"><i
                                                        class="align-bottom ri-check-double-line"></i></span></div>
                                        </div>
                                    </div>
                                </li>
                                <!-- chat-list -->
                            </ul>
                        </div>
                    </div>
                    <div class="border-top border-top-dashed">
                        <div class="mx-3 mt-2 mb-3 row g-2">
                            <div class="col">
                                <div class="position-relative">
                                    <input type="text" class="form-control border-light bg-light"
                                        placeholder="Enter Message...">
                                </div>
                            </div><!-- end col -->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary"><span
                                        class="d-none d-sm-inline-block me-2">Send</span> <i
                                        class="mdi mdi-send float-end"></i></button>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xxl-4 col-lg-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="mb-0 card-title flex-grow-1">Projects Status</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="dropdown-btn text-muted" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                All Time <i class="mdi mdi-chevron-down ms-1"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">All Time</a>
                                <a class="dropdown-item" href="#">Last 7 Days</a>
                                <a class="dropdown-item" href="#">Last 30 Days</a>
                                <a class="dropdown-item" href="#">Last 90 Days</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div id="prjects-status"
                        data-colors='["--vz-primary", "--vz-primary-rgb, 0.85", "--vz-primary-rgb, 0.70", "--vz-primary-rgb, 0.50"]'
                        class="apex-charts" dir="ltr"></div>
                    <div class="mt-3">
                        <div class="mb-4 d-flex justify-content-center align-items-center">
                            <h2 class="mb-0 me-3 ff-secondary">258</h2>
                            <div>
                                <p class="mb-0 text-muted">Total Projects</p>
                                <p class="mb-0 text-success fw-medium">
                                    <span class="p-1 badge badge-soft-success rounded-circle"><i
                                            class="ri-arrow-right-up-line"></i></span> +3 New
                                </p>
                            </div>
                        </div>

                        <div class="py-2 d-flex justify-content-between border-bottom border-bottom-dashed">
                            <p class="mb-0 fw-medium"><i
                                    class="align-middle ri-checkbox-blank-circle-fill text-success me-2"></i>
                                Completed
                            </p>
                            <div>
                                <span class="text-muted pe-5">125 Projects</span>
                                <span class="text-success fw-medium fs-12">15870hrs</span>
                            </div>
                        </div><!-- end -->
                        <div class="py-2 d-flex justify-content-between border-bottom border-bottom-dashed">
                            <p class="mb-0 fw-medium"><i
                                    class="align-middle ri-checkbox-blank-circle-fill text-primary me-2"></i> In
                                Progress</p>
                            <div>
                                <span class="text-muted pe-5">42 Projects</span>
                                <span class="text-success fw-medium fs-12">243hrs</span>
                            </div>
                        </div><!-- end -->
                        <div class="py-2 d-flex justify-content-between border-bottom border-bottom-dashed">
                            <p class="mb-0 fw-medium"><i
                                    class="align-middle ri-checkbox-blank-circle-fill text-warning me-2"></i> Yet to
                                Start</p>
                            <div>
                                <span class="text-muted pe-5">58 Projects</span>
                                <span class="text-success fw-medium fs-12">~2050hrs</span>
                            </div>
                        </div><!-- end -->
                        <div class="py-2 d-flex justify-content-between">
                            <p class="mb-0 fw-medium"><i
                                    class="align-middle ri-checkbox-blank-circle-fill text-danger me-2"></i> Cancelled
                            </p>
                            <div>
                                <span class="text-muted pe-5">89 Projects</span>
                                <span class="text-success fw-medium fs-12">~900hrs</span>
                            </div>
                        </div><!-- end -->
                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

</div>
<!-- container-fluid -->
@endsection