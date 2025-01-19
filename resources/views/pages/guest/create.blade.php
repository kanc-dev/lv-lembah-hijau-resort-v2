@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Tambah Tamu</h4>
                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Tambah Tamu</li>
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
                        <h5 class="mb-0 card-title">Tambah Tamu</h5>
                        <a href="{{ route('guest.index') }}" class="d-flex align-items-center btn btn-secondary">
                            <i class="ri-arrow-left-line"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('guest.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama" value="{{ old('nama') }}" required>
                                        <div class="invalid-feedback">
                                            @error('nama')
                                                {{ $message }}
                                            @else
                                                Harap masukkan nama tamu.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                            id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                                                Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('jenis_kelamin')
                                                {{ $message }}
                                            @else
                                                Harap pilih jenis kelamin.
                                            @enderror
                                        </div>
                                    </div>





                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">No. HP <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                            id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                                        <div class="invalid-feedback">
                                            @error('no_hp')
                                                {{ $message }}
                                            @else
                                                Harap masukkan no. HP.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        <div class="invalid-feedback">
                                            @error('email')
                                                {{ $message }}
                                            @else
                                                Harap masukkan email.
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kendaraan" class="form-label">Kendaraan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kendaraan') is-invalid @enderror"
                                            id="kendaraan" name="kendaraan" value="{{ old('kendaraan') }}" required>
                                        <div class="invalid-feedback">
                                            @error('kendaraan')
                                                {{ $message }}
                                            @else
                                                Harap masukkan kendaraan.
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_polisi" class="form-label">No. Polisi <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_polisi') is-invalid @enderror"
                                            id="no_polisi" name="no_polisi" value="{{ old('no_polisi') }}" required>
                                        <div class="invalid-feedback">
                                            @error('no_polisi')
                                                {{ $message }}
                                            @else
                                                Harap masukkan no. polisi.
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <!-- Kolom 2 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kantor_cabang" class="form-label">Kantor Cabang
                                            <span class="text-danger">*</span></label>
                                        </label>
                                        <input type="text"
                                            class="form-control @error('kantor_cabang') is-invalid @enderror"
                                            id="kantor_cabang" name="kantor_cabang" value="{{ old('kantor_cabang') }}">
                                        <div class="invalid-feedback">
                                            @error('kantor_cabang')
                                                {{ $message }}
                                            @else
                                                Harap masukkan Kantor Cabang.
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="event_id" class="form-label">Event <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('event_id') is-invalid @enderror" id="event_id"
                                            name="event_id" required>
                                            <option value="">Pilih Event</option>
                                            @foreach ($data['events'] as $event)
                                                <option value="{{ $event->id }}"
                                                    {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('event_id')
                                                {{ $message }}
                                            @else
                                                Harap pilih event.
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="batch" class="form-label">Batch</label>
                                        <input type="text" class="form-control @error('batch') is-invalid @enderror"
                                            id="batch" name="batch" value="{{ old('batch') }}">
                                        <div class="invalid-feedback">
                                            @error('batch')
                                                {{ $message }}
                                            @else
                                                Harap masukkan batch.
                                            @enderror
                                        </div>
                                    </div>



                                    <div class="mb-3">
                                        <label for="tanggal_rencana_checkin" class="form-label">Tanggal Rencana Check-in
                                            <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_rencana_checkin') is-invalid @enderror"
                                            id="tanggal_rencana_checkin" name="tanggal_rencana_checkin"
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
                                        <label for="tanggal_rencana_checkout" class="form-label">Tanggal Rencana Check-out
                                            <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_rencana_checkout') is-invalid @enderror"
                                            id="tanggal_rencana_checkout" name="tanggal_rencana_checkout"
                                            value="{{ old('tanggal_rencana_checkout') }}" required>
                                        <div class="invalid-feedback">
                                            @error('tanggal_rencana_checkout')
                                                {{ $message }}
                                            @else
                                                Harap masukkan tanggal rencana check-out.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="branch_id" class="form-label">Branch / Unit <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('branch_id') is-invalid @enderror"
                                            id="branch_id" name="branch_id" required>
                                            <option value="">Pilih Branch / Unit</option>
                                            @foreach ($data['branches'] as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('branch_id')
                                                {{ $message }}
                                            @else
                                                Harap pilih branch.
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="mb-3">
                                        <label for="room_id" class="form-label">Kamar</label>
                                        <select class="form-select @error('room_id') is-invalid @enderror" id="room_id"
                                            name="room_id" required>
                                            <option value="">Pilih Kamar</option>
                                            @foreach ($data['rooms'] as $room)
                                                <option value="{{ $room->id }}"
                                                    {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                                    {{ $room->nama }} <small>({{ $room->kapasitas }})</small>
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('room_id')
                                                {{ $message }}
                                            @else
                                                Harap pilih kamar.
                                            @enderror
                                        </div>
                                    </div> --}}


                                </div>
                            </div>

                            <div class="text-start">
                                <button type="submit" class="btn btn-success">Tambah Tamu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
