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
                            @if (session('success'))
                                <div class="p-4 card-body">
                                    <div class="mt-2 text-center">
                                        <h5 class="text-primary">Anda Berhasil Melakukan Registrasi</h5>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 card-body">
                                    <div class="mt-2 text-center">
                                        <h5 class="text-primary">Registrasi</h5>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form method="POST" action="{{ route('registrasi.store') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="nama" name="nama"
                                                    class="form-control @error('nama') is-invalid @enderror" required
                                                    placeholder="Masukkan nama lengkap" value="{{ old('nama') }}">
                                                <div class="invalid-feedback">
                                                    @error('nama')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan nama lengkap.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin<span
                                                        class="text-danger">*</span></label>
                                                <select id="jenis_kelamin" name="jenis_kelamin"
                                                    class="form-control @error('jenis_kelamin') is-invalid       @enderror"
                                                    required>
                                                    <option value="">--Pilih Jenis Kelamin--</option>
                                                    <option value="L"
                                                        {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki
                                                    </option>
                                                    <option value="P"
                                                        {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="branch_id" class="form-label">Unit<span
                                                        class="text-danger">*</span></label>
                                                <select id="branch_id" name="branch_id"
                                                    class="form-control @error('branch_id') is-invalid
                                            @enderror"
                                                    required>
                                                    <option value="">--Pilih Unit--</option>
                                                    @foreach ($data['branches'] as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                                            @if ($branch->id == $branchId) selected @endif>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="kantor_cabang" class="form-label">Kantor Cabang<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="kantor_cabang" name="kantor_cabang"
                                                    class="form-control @error('kantor_cabang') is-invalid
                                                @enderror"
                                                    value="{{ old('kantor_cabang') }}"
                                                    placeholder="Masukkan kantor cabang">
                                                <div class="invalid-feedback">
                                                    @error('kantor_cabang')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan kantor cabang.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="event_id" class="form-label">Pendidikan/Kelas<span
                                                        class="text-danger">*</span></label>
                                                <select id="event_id" name="event_id" class="form-control"
                                                    @error('event_id')
                                            @enderror
                                                    required>
                                                    <option value="">--Pilih Pendidikan / Kelas--</option>
                                                    @foreach ($data['events'] as $event)
                                                        <option value="{{ $event->id }}"
                                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                            {{ $event->nama_kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="batch" class="form-label">Batch<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="batch" name="batch"
                                                    class="form-control @error('batch') is-invalid
                                            @enderror"
                                                    value="{{ old('batch') }}" placeholder="Masukkan batch">
                                                <div class="invalid-feedback">
                                                    @error('batch')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan batch.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="kendaraan" class="form-label">Kendaraan<span
                                                        class="text-danger">*</span></label>
                                                <select id="kendaraan" name="kendaraan"
                                                    class="form-control @error('kendaraan') is-invalid
                                            @enderror"
                                                    required>
                                                    <option value="">--Pilih Kendaraan--</option>
                                                    <option value="mobil"
                                                        {{ old('kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil
                                                    </option>
                                                    <option value="motor"
                                                        {{ old('kendaraan') == 'motor' ? 'selected' : '' }}>Motor
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="no_polisi" class="form-label">Plat Nomor<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="no_polisi" name="no_polisi"
                                                    class="form-control @error('no_polisi') is-invalid
                                                @enderror"
                                                    value="{{ old('no_polisi') }}" required
                                                    placeholder="Masukkan plat nomor">
                                                <div class="invalid-feedback">
                                                    @error('no_polisi')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan plat nomor.
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="mb-3">
                                                <label for="no_hp" class="form-label">Nomor Telepon<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="no_hp" name="no_hp"
                                                    class="form-control @error('no_hp') is-invalid
                                                @enderror"
                                                    value="{{ old('no_hp') }}" required
                                                    placeholder="Masukkan nomor telepon">
                                                <div class="invalid-feedback">
                                                    @error('no_hp')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan nomor telepon.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email<span
                                                        class="text-danger">*</span></label>
                                                <input type="email" id="email" name="email"
                                                    class="form-control @error('email') is-invalid
                                            @enderror"
                                                    value="{{ old('email') }}" required placeholder="Enter email">
                                                <div class="invalid-feedback">
                                                    @error('email')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan email.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal_rencana_checkin" class="form-label">Tanggal
                                                    Rencana
                                                    Check-in<span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal_rencana_checkin"
                                                    name="tanggal_rencana_checkin"
                                                    class="form-control @error('tanggal_rencana_checkin') is-invalid
                                                @enderror"
                                                    value="{{ old('tanggal_rencana_checkin') }}" required>
                                                <div class="invalid-feedback">
                                                    @error('tanggal_rencana_checkin')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan tanggal rencana check-in.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal_rencana_checkout" class="form-label">Tanggal
                                                    Rencana
                                                    Check-out<span class="text-danger">*</span></label>
                                                <input type="date" id="tanggal_rencana_checkout"
                                                    name="tanggal_rencana_checkout"
                                                    class="form-control @error('tanggal_rencana_checkout') is-invalid
                                                @enderror"
                                                    value="{{ old('tanggal_rencana_checkout') }}" required>
                                                <div class="invalid-feedback">
                                                    @error('tanggal_rencana_checkout')
                                                        {{ $message }}
                                                    @else
                                                        Harap masukkan tanggal rencana check-out.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success w-100">Register</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            @endif
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
