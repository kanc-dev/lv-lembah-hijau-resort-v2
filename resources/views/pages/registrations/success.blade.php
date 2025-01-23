<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Hotel | Guest Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Guest Registration Form" name="description" />
    <link rel="shortcut icon" href="{{ asset('') }}assets/images/favicon.ico">

    <!-- Layout config Js -->
    <script src="{{ asset('') }}assets/js/layout.js"></script>
    <link href="{{ asset('') }}assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="pt-5 auth-page-wrapper">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles"
            style="height: 100% !important; background: url({{ asset('') }}image/13.jpg) !important ; ">
            <div class="bg-overlay"
                style="background: url({{ asset('') }}image/background.png) !important no-repeat;">
            </div>
        </div>

        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="mt-4 card">
                            <div class="p-4 card-body">
                                <div class="mt-2 text-center">
                                    <h5 class="text-primary">Anda Berhasil Melakukan Registrasi</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-white">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Lembah Hijau Resort Hotel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('') }}assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('') }}assets/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('') }}assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="{{ asset('') }}assets/js/plugins.js"></script>
</body>

</html>
