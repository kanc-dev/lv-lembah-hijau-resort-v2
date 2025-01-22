@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $data['page_title'] }}</h4>

                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a>
                            </li>
                            @if (isset($data['page_title']))
                                <li class="breadcrumb-item active">{{ $data['page_title'] }}</li>
                            @endif
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
                        <h5 class="mb-0 card-title">Edit Kamar</h5>
                        <a href="{{ route('room.index') }}" class="d-flex align-items-center btn btn-secondary">
                            <i class="ri-arrow-left-line"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('room.update', $data['room']->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kamar" class="form-label">Nama Kamar *</label>
                                        <input type="text" class="form-control" id="nama_kamar" name="nama_kamar"
                                            value="{{ old('nama_kamar', $data['room']->nama) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tipe" class="form-label">Tipe Kamar *</label>
                                        <input type="text" class="form-control" id="tipe" name="tipe"
                                            value="{{ old('tipe', $data['room']->tipe) }}" required>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="harga" class="form-label">Harga *</label>
                                        <input type="number" class="form-control" id="harga" name="harga"
                                            value="{{ old('harga', $data['room']->harga) }}" required>
                                    </div> --}}
                                    <div class="mb-3">
                                        <label for="kapasitas" class="form-label">Kapasitas *</label>
                                        <input type="number" class="form-control" id="kapasitas" name="kapasitas"
                                            value="{{ old('kapasitas', $data['room']->kapasitas) }}" required>
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">

                                    <!-- Branch Selection -->
                                    <div class="mb-3">
                                        <label for="branch_id" class="form-label">Unit / Branch *</label>
                                        <select class="form-select" id="branch_id" name="branch_id" required>
                                            @if (Auth::user()->branch_id == 0)
                                                <!-- Tampilkan semua branch jika branch_id 0 -->
                                                @foreach ($data['branches'] as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ old('branch_id', $data['room']->branch_id) == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <!-- Tampilkan hanya branch yang sesuai dengan user yang login -->
                                                <option value="{{ Auth::user()->branch_id }}" selected>
                                                    {{ Auth::user()->branch->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="event_id" class="form-label">Event</label>
                                        <select class="form-select" id="event_id" name="event_id">
                                            <!-- Tampilkan semua branch jika branch_id 0 -->
                                            <option value="">Pilih Event</option>
                                            @foreach ($data['events'] as $event)
                                                <option value="{{ $event->id }}"
                                                    {{ old('event_id', $data['room']->event_id) === $event->id ? 'selected' : '' }}>
                                                    {{ $event->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="room_status" class="form-label">Status Kamar *</label>
                                        <select class="form-select" id="room_status" name="room_status" required>
                                            <option value="available"
                                                {{ old('room_status', $data['room']->status) == 'available' ? 'selected' : '' }}>
                                                Tersedia
                                            </option>
                                            <option value="unavailable"
                                                {{ old('room_status', $data['room']->status) == 'unavailable' ? 'selected' : '' }}>
                                                Tidak Tersedia
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-start">
                                <button type="submit" class="btn btn-success">Edit Kamar</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
