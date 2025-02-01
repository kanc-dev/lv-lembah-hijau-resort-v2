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
            <x-widget-occupancy-monthly :data="$data" />
            <x-widget-occupancy-daily :data="$data" />
            <x-widget-occupancy-grafik :data="$data" />
            <x-widget-timeline-event :data="$data" />
        @else
        @endif

    </div>
    <!-- container-fluid -->
@endsection

@push('head-script')
@endpush

@push('body-script')
    <script></script>
@endpush
