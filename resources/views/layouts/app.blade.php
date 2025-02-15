<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('') }}image/favicon.png">

    <!-- jsvectormap css -->
    <link href="{{ asset('') }}assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{ asset('') }}assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <!-- Tambahkan link ke Choices.js di bagian <head> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


    <!-- Layout config Js -->
    <script src="{{ asset('') }}assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('') }}assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('') }}assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('') }}assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- apexcharts -->
    <script src="{{ asset('') }}assets/libs/apexcharts/apexcharts.min.js"></script>

    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <style>
        .hide-column {
            display: none;
        }
    </style>

    @stack('styles')
    @stack('head-script')

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('sweetalert::alert')

        @include('partials.header')
        <!-- ========== App Menu ========== -->
        @include('partials.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                @yield('content')

            </div>
            <!-- End Page-content -->

            @include('partials.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    {{-- <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button> --}}
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    {{-- <div class="customizer-setting d-none d-md-block">
        <div class="p-2 shadow-lg btn-primary btn-rounded btn btn-icon btn-lg" data-bs-toggle="offcanvas"
            data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div> --}}

    <!-- Theme Settings -->
    {{-- <div class="border-0 offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="p-3 d-flex align-items-center bg-primary bg-gradient offcanvas-header">
            <h5 class="m-0 text-white me-2">Theme Customizer</h5>

            <button type="button" class="btn-close btn-close-white ms-auto" id="customizerclose-btn"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="p-0 offcanvas-body">
            <div data-simplebar class="h-100">
                <div class="p-4">
                    <h6 class="mb-0 fw-semibold text-uppercase">Layout</h6>
                    <p class="text-muted">Choose your layout</p>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout01" name="data-layout" type="radio" value="vertical"
                                    class="form-check-input">
                                <label class="p-0 form-check-label avatar-md w-100" for="customizer-layout01">
                                    <span class="gap-1 d-flex h-100">
                                        <span class="flex-shrink-0">
                                            <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="p-1 bg-light d-block"></span>
                                                <span class="p-1 mt-auto bg-light d-block"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="mt-2 text-center fs-13">Vertical</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal"
                                    class="form-check-input">
                                <label class="p-0 form-check-label avatar-md w-100" for="customizer-layout02">
                                    <span class="gap-1 d-flex h-100 flex-column">
                                        <span class="gap-1 p-1 bg-light d-flex align-items-center">
                                            <span class="p-1 rounded d-block bg-soft-primary me-1"></span>
                                            <span class="p-1 px-2 pb-0 d-block bg-soft-primary ms-auto"></span>
                                            <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                        </span>
                                        <span class="p-1 bg-light d-block"></span>
                                        <span class="p-1 mt-auto bg-light d-block"></span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="mt-2 text-center fs-13">Horizontal</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn"
                                    class="form-check-input">
                                <label class="p-0 form-check-label avatar-md w-100" for="customizer-layout03">
                                    <span class="gap-1 d-flex h-100">
                                        <span class="flex-shrink-0">
                                            <span class="gap-1 bg-light d-flex h-100 flex-column">
                                                <span class="p-1 mb-2 d-block bg-soft-primary"></span>
                                                <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-shrink-0">
                                            <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="p-1 bg-light d-block"></span>
                                                <span class="p-1 mt-auto bg-light d-block"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="mt-2 text-center fs-13">Two Column</h5>
                        </div>
                        <!-- end col -->
                    </div>

                    <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Color Scheme</h6>
                    <p class="text-muted">Choose Light or Dark Scheme.</p>

                    <div class="colorscheme-cardradio">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-mode"
                                        id="layout-mode-light" value="light">
                                    <label class="p-0 form-check-label avatar-md w-100" for="layout-mode-light">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Light</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check card-radio dark">
                                    <input class="form-check-input" type="radio" name="data-layout-mode"
                                        id="layout-mode-dark" value="dark">
                                    <label class="p-0 form-check-label avatar-md w-100 bg-dark"
                                        for="layout-mode-dark">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-soft-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-light"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-soft-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-soft-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Dark</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-width">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Width</h6>
                        <p class="text-muted">Choose Fluid or Boxed layout.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-width"
                                        id="layout-width-fluid" value="fluid">
                                    <label class="p-0 form-check-label avatar-md w-100" for="layout-width-fluid">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Fluid</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-width"
                                        id="layout-width-boxed" value="boxed">
                                    <label class="p-0 px-2 form-check-label avatar-md w-100" for="layout-width-boxed">
                                        <span class="gap-1 d-flex h-100 border-start border-end">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Boxed</h5>
                            </div>
                        </div>
                    </div>

                    <div id="layout-position">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Layout Position</h6>
                        <p class="text-muted">Choose Fixed or Scrollable Layout Position.</p>

                        <div class="btn-group radio" role="group">
                            <input type="radio" class="btn-check" name="data-layout-position"
                                id="layout-position-fixed" value="fixed">
                            <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                            <input type="radio" class="btn-check" name="data-layout-position"
                                id="layout-position-scrollable" value="scrollable">
                            <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                        </div>
                    </div>
                    <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Topbar Color</h6>
                    <p class="text-muted">Choose Light or Dark Topbar Color.</p>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar"
                                    id="topbar-color-light" value="light">
                                <label class="p-0 form-check-label avatar-md w-100" for="topbar-color-light">
                                    <span class="gap-1 d-flex h-100">
                                        <span class="flex-shrink-0">
                                            <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="p-1 bg-light d-block"></span>
                                                <span class="p-1 mt-auto bg-light d-block"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="mt-2 text-center fs-13">Light</h5>
                        </div>
                        <div class="col-4">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="data-topbar"
                                    id="topbar-color-dark" value="dark">
                                <label class="p-0 form-check-label avatar-md w-100" for="topbar-color-dark">
                                    <span class="gap-1 d-flex h-100">
                                        <span class="flex-shrink-0">
                                            <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="p-1 bg-primary d-block"></span>
                                                <span class="p-1 mt-auto bg-light d-block"></span>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                            <h5 class="mt-2 text-center fs-13">Dark</h5>
                        </div>
                    </div>

                    <div id="sidebar-size">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Size</h6>
                        <p class="text-muted">Choose a size of Sidebar.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-default" value="lg">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-size-default">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Default</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-compact" value="md">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-size-compact">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Compact</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-small" value="sm">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-size-small">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 mb-2 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Small (Icon View)</h5>
                            </div>

                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar-size"
                                        id="sidebar-size-small-hover" value="sm-hover">
                                    <label class="p-0 form-check-label avatar-md w-100"
                                        for="sidebar-size-small-hover">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 mb-2 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Small Hover View</h5>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-view">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar View</h6>
                        <p class="text-muted">Choose Default or Detached Sidebar view.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-style"
                                        id="sidebar-view-default" value="default">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-view-default">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Default</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-layout-style"
                                        id="sidebar-view-detached" value="detached">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-view-detached">
                                        <span class="d-flex h-100 flex-column">
                                            <span class="gap-1 p-1 px-2 bg-light d-flex align-items-center">
                                                <span class="p-1 rounded d-block bg-soft-primary me-1"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary ms-auto"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                            </span>
                                            <span class="gap-1 p-1 px-2 d-flex h-100">
                                                <span class="flex-shrink-0">
                                                    <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                        <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                        <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                        <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    </span>
                                                </span>
                                            </span>
                                            <span class="p-1 px-2 mt-auto bg-light d-block"></span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Detached</h5>
                            </div>
                        </div>
                    </div>
                    <div id="sidebar-color">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Color</h6>
                        <p class="text-muted">Choose a color of Sidebar.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse"
                                    data-bs-target="#collapseBgGradient.show">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-light" value="light">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-color-light">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-white border-end d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Light</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse"
                                    data-bs-target="#collapseBgGradient.show">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-dark" value="dark">
                                    <label class="p-0 form-check-label avatar-md w-100" for="sidebar-color-dark">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-primary d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-light"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Dark</h5>
                            </div>
                            <div class="col-4">
                                <button class="p-0 overflow-hidden border btn btn-link avatar-md w-100 collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient"
                                    aria-expanded="false" aria-controls="collapseBgGradient">
                                    <span class="gap-1 d-flex h-100">
                                        <span class="flex-shrink-0">
                                            <span class="gap-1 p-1 bg-vertical-gradient d-flex h-100 flex-column">
                                                <span class="p-1 px-2 mb-2 rounded d-block bg-soft-light"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                                <span class="p-1 px-2 pb-0 d-block bg-soft-light"></span>
                                            </span>
                                        </span>
                                        <span class="flex-grow-1">
                                            <span class="d-flex h-100 flex-column">
                                                <span class="p-1 bg-light d-block"></span>
                                                <span class="p-1 mt-auto bg-light d-block"></span>
                                            </span>
                                        </span>
                                    </span>
                                </button>
                                <h5 class="mt-2 text-center fs-13">Gradient</h5>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="collapse" id="collapseBgGradient">
                            <div class="flex-wrap gap-2 p-2 px-3 rounded d-flex img-switch bg-light">

                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient" value="gradient">
                                    <label class="p-0 form-check-label avatar-xs rounded-circle"
                                        for="sidebar-color-gradient">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient"></span>
                                    </label>
                                </div>
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient-2" value="gradient-2">
                                    <label class="p-0 form-check-label avatar-xs rounded-circle"
                                        for="sidebar-color-gradient-2">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient-2"></span>
                                    </label>
                                </div>
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient-3" value="gradient-3">
                                    <label class="p-0 form-check-label avatar-xs rounded-circle"
                                        for="sidebar-color-gradient-3">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient-3"></span>
                                    </label>
                                </div>
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-sidebar"
                                        id="sidebar-color-gradient-4" value="gradient-4">
                                    <label class="p-0 form-check-label avatar-xs rounded-circle"
                                        for="sidebar-color-gradient-4">
                                        <span class="avatar-title rounded-circle bg-vertical-gradient-4"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="sidebar-img">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Sidebar Images</h6>
                        <p class="text-muted">Choose a image of Sidebar.</p>

                        <div class="flex-wrap gap-2 d-flex img-switch">
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-none" value="none">
                                <label class="h-auto p-0 form-check-label avatar-sm" for="sidebarimg-none">
                                    <span
                                        class="w-auto avatar-md bg-light d-flex align-items-center justify-content-center">
                                        <i class="ri-close-fill fs-20"></i>
                                    </span>
                                </label>
                            </div>

                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-01" value="img-1">
                                <label class="h-auto p-0 form-check-label avatar-sm" for="sidebarimg-01">
                                    <img src="{{ asset('') }}assets/images/sidebar/img-1.jpg"
                                        class="object-cover w-auto avatar-md">
                                </label>
                            </div>

                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-02" value="img-2">
                                <label class="h-auto p-0 form-check-label avatar-sm" for="sidebarimg-02">
                                    <img src="{{ asset('') }}assets/images/sidebar/img-2.jpg"
                                        class="object-cover w-auto avatar-md">
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-03" value="img-3">
                                <label class="h-auto p-0 form-check-label avatar-sm" for="sidebarimg-03">
                                    <img src="{{ asset('') }}assets/images/sidebar/img-3.jpg"
                                        class="object-cover w-auto avatar-md">
                                </label>
                            </div>
                            <div class="form-check sidebar-setting card-radio">
                                <input class="form-check-input" type="radio" name="data-sidebar-image"
                                    id="sidebarimg-04" value="img-4">
                                <label class="h-auto p-0 form-check-label avatar-sm" for="sidebarimg-04">
                                    <img src="{{ asset('') }}assets/images/sidebar/img-4.jpg"
                                        class="object-cover w-auto avatar-md">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="preloader-menu">
                        <h6 class="mt-4 mb-0 fw-semibold text-uppercase">Preloader</h6>
                        <p class="text-muted">Choose a preloader.</p>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-preloader"
                                        id="preloader-view-custom" value="enable">
                                    <label class="p-0 form-check-label avatar-md w-100" for="preloader-view-custom">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                        <!-- <div id="preloader"> -->
                                        <div id="status" class="d-flex align-items-center justify-content-center">
                                            <div class="m-auto spinner-border text-primary avatar-xxs" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <!-- </div> -->
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Enable</h5>
                            </div>
                            <div class="col-4">
                                <div class="form-check sidebar-setting card-radio">
                                    <input class="form-check-input" type="radio" name="data-preloader"
                                        id="preloader-view-none" value="disable">
                                    <label class="p-0 form-check-label avatar-md w-100" for="preloader-view-none">
                                        <span class="gap-1 d-flex h-100">
                                            <span class="flex-shrink-0">
                                                <span class="gap-1 p-1 bg-light d-flex h-100 flex-column">
                                                    <span class="p-1 px-2 mb-2 rounded d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                    <span class="p-1 px-2 pb-0 d-block bg-soft-primary"></span>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1">
                                                <span class="d-flex h-100 flex-column">
                                                    <span class="p-1 bg-light d-block"></span>
                                                    <span class="p-1 mt-auto bg-light d-block"></span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <h5 class="mt-2 text-center fs-13">Disable</h5>
                            </div>
                        </div>

                    </div>
                    <!-- end preloader-menu -->

                </div>
            </div>

        </div>
        <div class="p-3 text-center offcanvas-footer border-top">
            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-light w-100" id="reset-layout">Reset</button>
                </div>
                <div class="col-6">
                    <a href="https://1.envato.market/velzon-admin" target="_blank" class="btn btn-primary w-100">Buy
                        Now</a>
                </div>
            </div>
        </div>
    </div> --}}


    <!-- JAVASCRIPT -->
    <script src="{{ asset('') }}assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('') }}assets/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('') }}assets/libs/feather-icons/feather.min.js"></script>
    <script src="{{ asset('') }}assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{ asset('') }}assets/js/plugins.js"></script>




    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{ asset('') }}assets/js/pages/datatables.init.js"></script>



    <!-- Vector map-->
    <script src="{{ asset('') }}assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="{{ asset('') }}assets/libs/jsvectormap/maps/world-merc.js"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('') }}assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Dashboard init -->
    <script src="{{ asset('') }}assets/js/pages/dashboard-ecommerce.init.js"></script>
    <script src="{{ asset('') }}assets/js/pages/dashboard-analytics.init.js"></script>
    <script src="{{ asset('') }}assets/js/pages/dashboard-projects.init.js"></script>
    <script src="{{ asset('') }}assets/js/pages/dashboard-crm.init.js"></script>



    <!-- prismjs plugin -->
    <script src="{{ asset('') }}assets/libs/prismjs/prism.js"></script>


    <!-- App js -->
    <script src="{{ asset('') }}assets/js/app.js"></script>

    @stack('scripts')
    @stack('body-script')
</body>

</html>
