@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Booking</h4>
                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Booking</li>
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
                        <h5 class="mb-0 card-title">Edit Booking</h5>
                        <a href="{{ route('booking.index') }}" class="d-flex align-items-center btn btn-secondary">
                            <i class="ri-arrow-left-line"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('booking.update', $data['booking']->id) }}" method="POST"
                            class="needs-validation" novalidate>
                            @csrf
                            @method('PUT') <!-- Method Override for Update -->
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="event_id" class="form-label">Event <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('event_id') is-invalid @enderror" id="event_id"
                                            name="event_id">
                                            <option value="">Pilih Event</option>
                                            @foreach ($data['events'] as $event)
                                                <option value="{{ $event->id }}"
                                                    {{ $data['booking']->event_id == $event->id ? 'selected' : '' }}>
                                                    {{ $event->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div>atau</div>
                                        <button type="button" class="mt-1 btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#eventModal">
                                            <i class="ri-add-line"></i> Tambah Event
                                        </button>
                                        <div class="invalid-feedback">
                                            @error('event_id')
                                                {{ $message }}
                                            @else
                                                Harap pilih event.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="jumlah_peserta" class="form-label">Jumlah Peserta <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('jumlah_peserta') is-invalid @enderror"
                                            id="jumlah_peserta" name="jumlah_peserta"
                                            value="{{ old('jumlah_peserta', $data['booking']->jumlah_peserta) }}" required>
                                        <div class="invalid-feedback">
                                            @error('jumlah_peserta')
                                                {{ $message }}
                                            @else
                                                Harap masukkan jumlah peserta.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="unit_origin_id" class="form-label">Unit Asal <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('unit_origin_id') is-invalid @enderror"
                                            id="unit_origin_id" name="unit_origin_id" disabled>
                                            <option value="">Pilih Unit Asal</option>
                                            @foreach ($data['branches'] as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $data['booking']->unit_origin_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('unit_origin_id')
                                                {{ $message }}
                                            @else
                                                Harap pilih unit asal.
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="unit_destination_id" class="form-label">Unit Tujuan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('unit_destination_id') is-invalid @enderror"
                                            id="unit_destination_id" name="unit_destination_id" required>
                                            <option value="">Pilih Unit Tujuan</option>
                                            @foreach ($data['branches'] as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ $data['booking']->unit_destination_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('unit_destination_id')
                                                {{ $message }}
                                            @else
                                                Harap pilih unit tujuan.
                                            @enderror
                                        </div>
                                    </div>


                                </div>

                                <!-- Kolom 2 -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_rencana_checkin" class="form-label">Tanggal Rencana Check-in
                                            <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_rencana_checkin') is-invalid @enderror"
                                            id="tanggal_rencana_checkin" name="tanggal_rencana_checkin"
                                            value="{{ old('tanggal_rencana_checkin', $data['booking']->tanggal_rencana_checkin) }}"
                                            required>
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
                                            value="{{ old('tanggal_rencana_checkout', $data['booking']->tanggal_rencana_checkout) }}"
                                            required>
                                        <div class="invalid-feedback">
                                            @error('tanggal_rencana_checkout')
                                                {{ $message }}
                                            @else
                                                Harap masukkan tanggal rencana check-out.
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="rooms" class="form-label">Pilih Kamar <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="rooms" name="rooms[]" multiple>
                                            @foreach ($data['rooms'] as $room)
                                                <option value="{{ $room->id }}"
                                                    {{ in_array($room->id, old('room_id', is_array($data['booking']->rooms) ? $data['booking']->rooms : json_decode($data['booking']->rooms, true))) ? 'selected' : '' }}>
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
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Event -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Tambah Event Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm" method="POST" action="{{ route('event.store_ajax') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Simpan Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    // Tambahkan script JavaScript untuk menangani pengeditan data form jika diperlukan
    document.addEventListener('DOMContentLoaded', function() {
        const roomSelect = document.getElementById('rooms');
        const choices = new Choices(roomSelect, {
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Pilih kamar...',
            searchEnabled: true,
            itemSelectText: ''
        });
    });
</script>
