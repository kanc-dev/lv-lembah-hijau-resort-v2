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
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a>
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
                        <h5 class="mb-0 card-title">Tambah Event</h5>
                        <a href="{{ route('event.index') }}" class="d-flex align-items-center btn btn-secondary">
                            <i class="ri-arrow-left-line"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('event.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="nama_kelas" class="form-label">Nama Event <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_kelas') is-invalid @enderror"
                                    id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas') }}" required>
                                <div class="invalid-feedback">
                                    @error('nama_kelas')
                                        {{ $message }}
                                    @else
                                        Harap masukkan nama event.
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi<span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi"
                                    required>{{ old('deskripsi') }}</textarea>
                                <div class="invalid-feedback">
                                    @error('deskripsi')
                                        {{ $message }}
                                    @else
                                        Harap masukkan deskripsi event.
                                    @enderror
                                </div>
                            </div>


                            <div class="text-start">
                                <button type="submit" class="btn btn-success">Tambah Event</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
