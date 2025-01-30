@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0">Dashboard Monitoring</h4>
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
            <div class=" row">
                <div class="col-12">
                    <!-- card body -->
                    <div class="mb-2 d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fs-16">Data Okupansi Bulanan</h4>
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="mb-0 row g-3 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <input type="month" class="border form-control " value="{{ date('Y-m') }}">
                                            <div class="text-white input-group-text bg-primary border-primary">
                                                <i class="ri-calendar-2-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($data['occupancy_of_branch'] as $branch)
                            <div class="col">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="overflow-hidden flex-grow-1">
                                                <p class="mb-0 text-uppercase fw-medium text-muted text-truncate">
                                                    {{ $branch['name'] }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-4 d-flex align-items-end justify-content-between">
                                            <div>
                                                <h4 class="mb-4 fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                                        data-target="{{ $branch['occupancy']['percentage_occupied'] }}">{{ $branch['occupancy']['percentage_occupied'] }}</span>%
                                                </h4>
                                            </div>
                                            <div class="flex-shrink-0 avatar-sm">
                                                <span class="rounded avatar-title bg-soft-primary fs-3">
                                                    <i class='bx bx-doughnut-chart text-primary'></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        @endforeach
                    </div>
                </div>
            </div>
            <div class=" row">
                <div class="col-12">
                    <!-- card body -->
                    <div class="mb-2 d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fs-16">Data Okupansi Harian</h4>
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="mb-0 row g-3 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <input type="date" class="border form-control " value="{{ date('Y-m-d') }}">
                                            <div class="text-white input-group-text bg-primary border-primary">
                                                <i class="ri-calendar-2-line"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($data['occupancy_of_branch'] as $branch)
                            <div class="col">
                                <div class="card card-height-100 ">
                                    <div class="card-header align-items-center d-flex">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-0 card-title flex-grow-1">{{ $branch['name'] }}</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <x-room-daily-occupancy :branchId="$branch['id']" />


                                        <div class="mt-3 table-responsive table-card">
                                            <table
                                                class="table mb-1 align-middle table-borderless table-sm table-centered table-nowrap">
                                                <thead
                                                    class="border border-dashed text-muted border-start-0 border-end-0 bg-soft-light">
                                                    <tr>
                                                        <th></th>
                                                        <th style="width: 30%;">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="border-0">
                                                    <tr>
                                                        <th>Kapasitas</th>
                                                        <td>{{ $branch['occupancy']['total'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Terisi</th>
                                                        <td>{{ $branch['occupancy']['occupied'] }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tersedia</th>
                                                        <td>{{ $branch['occupancy']['empty'] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class=" row">
                <div class="col-12">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <div class="flex-grow-1">
                                <h4 class="mb-1 fs-16">Grafik Okupansi Bulanan</h4>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <form action="javascript:void(0);">
                                    <div class="mb-0 row g-3 align-items-center">
                                        <div class="col-sm-auto">
                                            <div class="input-group">
                                                <input type="date" class="border form-control"
                                                    value="{{ date('Y-m-d') }}">
                                                <div class="text-white input-group-text bg-primary border-primary">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div><!-- end card header -->
                        <!-- card body -->
                        <div class="card-body">
                            <x-history-branch-occupancy :branchId="$branchId" />
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        @endif

    </div>
    <!-- container-fluid -->
@endsection

@push('head-script')
@endpush

@push('body-script')
@endpush
