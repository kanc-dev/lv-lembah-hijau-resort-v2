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
                        <h5 class="mb-0 card-title">Tambah Kamar</h5>
                        <a href="{{ route('room.index') }}" class="d-flex align-items-center btn btn-secondary">
                            <i class="ri-arrow-left-line"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('room.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kamar" class="form-label">Nama Kamar <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_kamar') is-invalid @enderror"
                                            id="nama_kamar" name="nama_kamar" value="{{ old('nama_kamar') }}" required>
                                        <div class="invalid-feedback">
                                            @error('nama_kamar')
                                                {{ $message }}
                                            @else
                                                Harap masukkan nama kamar.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tipe" class="form-label">Tipe Kamar <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tipe') is-invalid @enderror"
                                            id="tipe" name="tipe" value="{{ old('tipe') }}" required>
                                        <div class="invalid-feedback">
                                            @error('tipe')
                                                {{ $message }}
                                            @else
                                                Harap masukkan tipe kamar.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="kapasitas" class="form-label">Kapasitas <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('kapasitas') is-invalid @enderror"
                                            id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" required>
                                        <div class="invalid-feedback">
                                            @error('kapasitas')
                                                {{ $message }}
                                            @else
                                                Harap masukkan kapasitas kamar.
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="harga" class="form-label">Harga <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                            id="harga" name="harga" value="{{ old('harga') }}" required>
                                        <div class="invalid-feedback">
                                            @error('harga')
                                                {{ $message }}
                                            @else
                                                Harap masukkan harga kamar.
                                            @enderror
                                        </div>
                                    </div> --}}
                                </div>

                                <!-- Kolom 2 -->
                                <div class="col-md-6">


                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit / Branch <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('branch_id') is-invalid @enderror" id="unit"
                                            name="branch_id" required>
                                            <option value="">Pilih Unit / Branch</option>
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
                                                Harap pilih unit atau branch.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="room_status" class="form-label">Status Kamar <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('room_status') is-invalid @enderror"
                                            id="room_status" name="room_status" required>
                                            <option value="available"
                                                {{ old('room_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="unavailable"
                                                {{ old('room_status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('room_status')
                                                {{ $message }}
                                            @else
                                                Harap pilih status kamar.
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-start">
                                <button type="submit" class="btn btn-success">Tambah Kamar</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
