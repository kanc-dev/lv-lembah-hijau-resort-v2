@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $data['page_title'] }}</h4>
                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $data['page_title'] }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        {{-- {!! $data['guests'] !!} --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Data Tamu</h5>
                        <a href="{{ route('guest.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-fill"></i> <span>Tambah Tamu</span>
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif

                        <form action="{{ route('guest.index') }}" method="GET">
                            <div class="g-2 row">
                                <!-- Filter Status Tamu -->
                                <div class="col-md-4">
                                    <div class="form-control">
                                        <label class="form-check-label"><strong>Status Tamu:</strong></label>
                                        <div class="flex-wrap d-flex">
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="filter_status[]"
                                                    value="not_plotted" id="not_plotted"
                                                    {{ in_array('not_plotted', (array) request('filter_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="not_plotted">Belum Plot Kamar</label>
                                            </div>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="filter_status[]"
                                                    value="not_checked_in" id="not_checked_in"
                                                    {{ in_array('not_checked_in', (array) request('filter_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="not_checked_in">Belum Check-In</label>
                                            </div>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="filter_status[]"
                                                    value="not_checked_out" id="not_checked_out"
                                                    {{ in_array('not_checked_out', (array) request('filter_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="not_checked_out">Belum
                                                    Check-Out</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Filter Pencarian dan Jenis Kelamin -->
                                <div class="col-md-4">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Cari Nama/Telp/Email..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-12">
                                            <select class="form-select" id="filter_gender" name="filter_gender">
                                                <option value="">--Filter Jenis Kelamin--</option>
                                                <option value="l"
                                                    {{ request('filter_gender') == 'l' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="p"
                                                    {{ request('filter_gender') == 'p' ? 'selected' : '' }}>Perempuan
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Filter Event dan Unit -->
                                <div class="col-md-4">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <select class="form-select" id="filter_event" name="filter_event">
                                                <option value="">--Kelas / Pendidikan--</option>
                                                @foreach ($data['bookings'] as $booking)
                                                    <option value="{{ $booking->id }}"
                                                        {{ request('filter_event') == $booking->id ? 'selected' : '' }}>
                                                        {{ $booking->event->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="row g-2">
                                                <div class="col-md-9">
                                                    <select class="form-select" id="filter_unit" name="filter_unit"
                                                        {{ auth()->user()->branch_id !== null ? 'disabled' : '' }}>
                                                        <option value="">--Unit--</option>
                                                        @foreach ($data['branches'] as $branch)
                                                            <option value="{{ $branch->id }}"
                                                                {{ request('filter_unit') == $branch->id || auth()->user()->branch_id == $branch->id ? 'selected' : '' }}>
                                                                {{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="gap-2 col-md-3 d-flex">
                                                    <button type="submit" class="btn w-100 btn-primary"><i
                                                            class="ri-search-line"></i></button>
                                                    <a href="{{ route('guest.index') }}" class="btn w-100 btn-danger"><i
                                                            class="ri-refresh-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="mt-3">
                            <button id="bulkPlotRoom" class="btn btn-info btn-sm" disabled>Plot Kamar</button>
                            <button id="bulkCheckin" class="btn btn-success btn-sm" disabled>Check-in</button>
                            <button id="bulkCheckout" class="btn btn-secondary btn-sm" disabled>Check-out</button>
                            <button id="bulkUnPlotRoom" class="btn btn-soft-info btn-sm" disabled>Un-Plot Kamar</button>
                            <button id="bulkUnCheckin" class="btn btn-soft-success btn-sm" disabled>Un-Check-in</button>
                            <button id="bulkUnCheckout" class="btn btn-soft-secondary btn-sm"
                                disabled>Un-Check-out</button>
                            {{-- <button id="bulkDelete" class="btn btn-danger btn-sm" disabled><i class="ri-delete-bin-line"></i></button> --}}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Nama</th>
                                        <th>Kamar</th>
                                        <th>Check (In/Out)</th>
                                        <th>Unit</th>
                                        <th>Kelas / Pendidikan</th>
                                        <th>Batch</th>
                                        <th class="hide-column">Telepon</th>
                                        <th class="hide-column">Email</th>
                                        <th class="hide-column">Jenis Kelamin</th>
                                        <th class="hide-column">Kendaraan</th>
                                        <th class="hide-column">Plat No</th>
                                        <th class="hide-column">Rencana Check-in</th>
                                        <th class="hide-column">Rencana Check-out</th>
                                        <th>Created At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data['guests']->isEmpty())
                                        <tr>
                                            <td colspan="16" class="text-center">Tidak ada data tamu</td>
                                        </tr>
                                    @endif
                                    @foreach ($data['guests'] as $guest)
                                        {{-- @dump($guest) --}}
                                        <tr>
                                            <td><input type="checkbox" class="guest-checkbox" name="guest_ids[]"
                                                    data-guest-id="{{ $guest->id }}"
                                                    data-booking-id="{{ $guest->events->first()->pivot->booking_id ?? null }}"
                                                    value="{{ $guest->id }}"></td>
                                            <td class="text-nowrap">{{ $guest->nama }}</td>
                                            <td>
                                                @if ($guest->guestcheckins->first())
                                                    <span
                                                        class="badge bg-soft-info text-dark ">{{ $guest->guestcheckins->first()->room->nama }}</span>
                                                @else
                                                    <span class="badge bg-soft-info text-dark">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span>
                                                    @if ($guest->guestcheckins->isNotEmpty())
                                                        @if ($guest->guestcheckins->first()->tanggal_checkin)
                                                            <span
                                                                class="badge bg-success">{{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkin)) }}</span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </span>
                                                /
                                                <span>
                                                    @if ($guest->guestcheckins->isNotEmpty())
                                                        @if ($guest->guestcheckins->first()->tanggal_checkout)
                                                            <span
                                                                class="badge bg-danger">{{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkout)) }}</span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $guest->branch->name }}</td>
                                            <td>

                                                @if ($guest->events)
                                                    {{ $guest->events->first()->nama_kelas ?? 'N/A' }}
                                                    {{-- @foreach ($guest->events as $event)
                                                        {{ $event->nama_kelas }}
                                                    @endforeach --}}
                                                @else
                                                    'N/A'
                                                @endif
                                            </td>
                                            <td>{{ $guest->batch }}</td>
                                            <td class="hide-column">{{ $guest->no_hp }}</td>
                                            <td class="hide-column">{{ $guest->email }}</td>
                                            <td class="hide-column">{{ $guest->jenis_kelamin }}</td>
                                            <td class="hide-column">{{ $guest->kendaraan }}</td>
                                            <td class="hide-column">{{ $guest->no_polisi }}</td>
                                            <td class="hide-column">
                                                {{ date('d, M Y', strtotime($guest->tanggal_rencana_checkin)) }}</td>
                                            <td class="hide-column">
                                                {{ date('d, M Y', strtotime($guest->tanggal_rencana_checkout)) }}</td>
                                            <td>{{ $guest->created_at }}</td>
                                            <td>
                                                <div class="flex-wrap gap-1 d-flex">
                                                    <div>
                                                        <button class="btn btn-info btn-sm plot-room-btn"
                                                            data-guest-id="{{ $guest->id }}"
                                                            data-booking-id="{{ $guest->events->first()->pivot->booking_id ?? null }}"
                                                            data-branch-id="{{ $guest->branch_id }}" title="Plot Kamar">
                                                            <i class="ri-hotel-bed-line"></i>
                                                        </button>
                                                        @include('partials.modal-plot-room', [
                                                            'guest' => $guest,
                                                            'rooms' => $data['rooms'],
                                                        ])
                                                        {{-- <button class="text-nowrap btn btn-info btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#plotRoomModal{{ $guest->id }}"
                                                            title="Plot Kamar">
                                                            <i class="ri-hotel-bed-line"></i>
                                                        </button> --}}
                                                    </div>
                                                    <form action="{{ route('guest.checkin', $guest->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="checkin_date"
                                                            value="{{ date('Y-m-d') }}">
                                                        <button type="submit" class="text-nowarp btn btn-success btn-sm"
                                                            title="Check In"><i
                                                                class="ri-calendar-check-line"></i></button>
                                                    </form>
                                                    <form action="{{ route('guest.checkout', $guest->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="checkout_date"
                                                            value="{{ date('Y-m-d') }}">
                                                        <button type="submit"
                                                            class="btn text-nowarp btn-soft-dark btn-sm"
                                                            title="Check Out"><i
                                                                class=" ri-calendar-check-fill"></i></button>
                                                    </form>
                                                    <div>
                                                        <a href="{{ route('guest.edit', $guest) }}"
                                                            class="btn btn-warning btn-sm" title="Edit"><i
                                                                class="ri-edit-box-line"></i></a>
                                                    </div>
                                                    <form action="{{ route('guest.destroy', $guest) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this guest?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Delete"><i class="ri-delete-bin-line"></i></button>
                                                    </form>
                                                    {{-- @if (!auth()->user()->branch_id)
                                                    @endif --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $data['guests']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Bulk Plot Room -->
    <div class="modal fade" id="bulkPlotRoomModal" tabindex="-1" aria-labelledby="bulkPlotRoomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkPlotRoomModalLabel">Pilih Kamar untuk Tamu Terpilih</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="bulkPlotRoomTable"
                            class="table text-center align-middle table-sm table-striped table-hover table-bordered"
                            style="width:100%">
                            <!-- Header dan body akan diisi oleh jQuery -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #bulkPlotRoomTable,
        #roomsTable {
            width: 100%;
            border-collapse: collapse;
        }

        .table-scroll {
            max-height: 400px !important;
            /* Sesuaikan tinggi sesuai kebutuhan */
            overflow-y: auto !important;
            display: block !important;
        }

        #bulkPlotRoomTable thead,
        #bulkPlotRoomTable tbody tr,
        #roomsTable thead,
        #roomsTable tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        #bulkPlotRoomTable tbody,
        #roomsTable tbody {
            display: block;
            width: 100%;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {

            // Plot Kamar
            $('.plot-room-btn').on('click', function() {
                var guestId = $(this).data('guest-id');
                var bookingId = $(this).data('booking-id');
                var branchId = $(this).data('branch-id');

                console.log('Guest ID:', guestId);
                console.log('Booking ID:', bookingId);
                console.log('Branch ID:', branchId);

                $.ajax({
                    url: '/guest/get-rooms-by-booking-id',
                    method: 'GET',
                    data: {
                        booking_id: bookingId,
                        branch_id: branchId
                    },
                    success: function(response) {
                        console.log('Response Data:', response.data);
                        showPlotRoomModal(guestId, bookingId, response.data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            function showPlotRoomModal(guestId, bookingId, rooms) {
                let modalElement = $('#plotRoomModal' + guestId);
                let modal = new bootstrap.Modal(modalElement[0]);
                let modalBody = modalElement.find('.modal-body tbody');
                modalBody.empty();

                rooms.forEach(function(room, index) {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${room.nama} <small>(${room.branch.name})</small></td>
                            <td>${room.kapasitas}</td>
                            <td>${room.bed_terisi}</td>
                            <td>${room.bed_sisa}</td>
                            <td>
                                <button class="btn btn-sm btn-success select-room-btn"
                                    data-room-id="${room.id}"
                                    data-room-capacity="${room.kapasitas}"
                                    ${room.bed_sisa <= 0 ? 'disabled' : ''}>
                                    ${room.bed_sisa <= 0 ? 'Penuh' : 'Pilih'}
                                </button>

                            </td>
                        </tr>
                    `;
                    modalBody.append(row);
                });

                modal.show();

                // Event listener untuk tombol "Pilih Kamar"
                $(document).on('click', '.select-room-btn', function() {
                    const button = $(this);
                    const roomId = $(this).data('room-id');
                    const roomCapacity = $(this).data('room-capacity');
                    const selectedGuestIds = [guestId];

                    button.prop('disabled', true);
                    const originalText = button.html();
                    button.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    );

                    $.ajax({
                        url: '/guest/bulk-plot-rooms',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify({
                            room_id: roomId,
                            guests: selectedGuestIds,
                            booking_id: bookingId
                        }),
                        contentType: 'application/json',
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Kamar berhasil diplot ke tamu terpilih.',
                            }).then(() => {
                                modal.hide();
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat memplot kamar.',
                            });

                            button.prop('disabled', false);
                            button.html(originalText);
                        }
                    });
                });
            }
            // End Plot Kamar

            // Bulk Plot Kamar
            var selectedGuests = [];

            $('.guest-checkbox').on('change', function() {
                var guestId = $(this).val();
                var bookingId = $(this).closest('tr').find('.plot-room-btn').data('booking-id');


                if ($(this).is(':checked')) {
                    selectedGuests.push({
                        id: guestId,
                        bookingId: bookingId
                    });
                } else {
                    selectedGuests = selectedGuests.filter(guest => guest.id !== guestId);
                }

                if (selectedGuests.length > 0) {
                    $('#bulkPlotRoom').prop('disabled', false);
                } else {
                    $('#bulkPlotRoom').prop('disabled', true);
                }
            });

            $('#bulkPlotRoom').on('click', function() {
                var uniqueBookingIds = [...new Set(selectedGuests.map(guest => guest.bookingId))];

                console.log(uniqueBookingIds);


                if (uniqueBookingIds.length > 1) {
                    alert(
                        'Tamu yang dipilih memiliki booking yang berbeda. Silakan pilih tamu dengan booking yang sama.'
                    );
                    return false;
                }

                var bookingId = selectedGuests[0].bookingId;

                $.ajax({
                    url: '/guest/get-rooms-by-booking-id',
                    method: 'GET',
                    data: {
                        booking_id: bookingId
                    },
                    success: function(response) {
                        showBulkPlotRoomModal(response.data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            function showBulkPlotRoomModal(rooms) {
                let modalElement = $('#bulkPlotRoomModal');
                let tableElement = $('#bulkPlotRoomTable');

                tableElement.empty();

                const thead = $('<thead>').addClass('table-light');
                const headerRow = $('<tr>');
                const headers = ['No', 'Nama Kamar (Unit)', 'Kapasitas', 'Bed Terisi', 'Bed Tersedia', 'Aksi'];

                headers.forEach(headerText => {
                    const th = $('<th>').text(headerText);
                    headerRow.append(th);
                });

                thead.append(headerRow);
                tableElement.append(thead);

                const tbody = $('<tbody class="table-scroll">');

                rooms.forEach((room, index) => {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${room.nama} <small>(${room.branch.name})</small></td>
                            <td>${room.kapasitas}</td>
                            <td>${room.bed_terisi}</td>
                            <td>${room.bed_sisa}</td>
                            <td>
                               <button class="btn btn-sm btn-success bulk-select-room-btn"
                                    data-room-id="${room.id}"
                                    data-room-capacity="${room.kapasitas}"
                                    ${room.bed_sisa <= 0 ? 'disabled' : ''}>
                                    ${room.bed_sisa <= 0 ? 'Penuh' : 'Pilih'}
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);

                });

                tableElement.append(tbody);

                let modal = new bootstrap.Modal(modalElement[0], {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();

                // Event listener untuk tombol "Pilih Kamar"
                $(document).on('click', '.bulk-select-room-btn', function() {
                    const button = $(this);
                    const roomId = $(this).data('room-id');
                    const roomCapacity = $(this).data('room-capacity');
                    const bookingId = selectedGuests[0].bookingId;
                    const selectedGuestIds = selectedGuests.map(guest => guest.id);

                    if (selectedGuestIds.length > roomCapacity) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kapasitas Tidak Cukup!',
                            text: 'Kamar tidak bisa menampung jumlah tamu yang dipilih.',
                        });
                        return;
                    }
                    console.log(bookingId, selectedGuestIds);


                    button.prop('disabled', true);
                    const originalText = button.html();
                    button.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    );


                    $.ajax({
                        url: '/guest/bulk-plot-rooms',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify({
                            room_id: roomId,
                            guests: selectedGuestIds,
                            booking_id: bookingId
                        }),
                        contentType: 'application/json',
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message ||
                                    'Kamar berhasil diplot ke tamu terpilih.',
                            }).then(() => {
                                modal.hide();
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat memplot kamar.',
                            });

                            button.prop('disabled', false);
                            button.html(originalText);
                        }
                    });
                });

            }

            $('#bulkCheckin').on('click', function() {
                const selectedGuests = getSelectedGuests();
                handleBulkAction('checkin', selectedGuests);
            });

            $('#bulkCheckout').on('click', function() {
                const selectedGuests = getSelectedGuests();
                handleBulkAction('checkout', selectedGuests);
            });

            function handleBulkAction(action, selectedGuests) {
                if (selectedGuests.length === 0) {
                    alert('Pilih tamu terlebih dahulu!');
                    return;
                }

                const endpoint = action === 'checkin' ? '/guest/bulk-checkin' : '/guest/bulk-checkout';

                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({
                        guest_ids: selectedGuests
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        alert(`Tamu berhasil di-${action === 'checkin' ? 'check-in' : 'check-out'}!`);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert(xhr.responseJSON?.message || 'Terjadi kesalahan. Coba lagi!');
                    }
                });
            }
            // End Bulk Check-in dan Bulk Check-out

            function sendBulkRequest(url, data) {
                console.log("Data yang dikirim:", data); // Debugging

                $.ajax({
                    url: url,
                    type: "POST",
                    data: JSON.stringify({
                        guests: data,
                        _token: "{{ csrf_token() }}"
                    }),
                    contentType: "application/json", // Pastikan ini ada agar Laravel membaca JSON
                    dataType: "json", // Pastikan ini ada agar respons diterima sebagai JSON
                    success: function(response) {
                        Swal.fire("Sukses", response.message, "success").then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire("Error", "Terjadi kesalahan, coba lagi.", "error");
                        console.log(xhr.responseText); // Debugging error
                    }
                });
            }



            $("#bulkUnCheckout").click(function() {
                let selectedIds = getSelectedGuestData();
                if (selectedIds.length === 0) {
                    Swal.fire("Peringatan", "Pilih setidaknya satu tamu!", "warning");
                    return;
                }
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Tindakan ini akan menghapus tanggal checkout!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendBulkRequest("{{ route('guest.uncheckout') }}", selectedIds);
                    }
                });
            });

            // Event klik untuk Un-Checkin
            $("#bulkUnCheckin").click(function() {
                let selectedIds = getSelectedGuestData();
                if (selectedIds.length === 0) {
                    Swal.fire("Peringatan", "Pilih setidaknya satu tamu!", "warning");
                    return;
                }
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Tindakan ini akan menghapus tanggal check-in dan checkout!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendBulkRequest("{{ route('guest.uncheckin') }}", selectedIds);
                    }
                });
            });

            // Event klik untuk Un-Ploting
            $("#bulkUnPlotRoom").click(function() {
                let selectedIds = getSelectedGuestData();
                if (selectedIds.length === 0) {
                    Swal.fire("Peringatan", "Pilih setidaknya satu tamu!", "warning");
                    return;
                }
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dipilih akan dihapus sepenuhnya!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        sendBulkRequest("{{ route('guest.unploting') }}", selectedIds);
                    }
                });
            });



            $('#checkAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('.guest-checkbox').prop('checked', isChecked);
                checkSelectedGuests();
            });

            $('.guest-checkbox').on('change', function() {
                checkSelectedGuests();
            });

            function getSelectedGuestData() {
                let selectedGuests = [];
                $(".guest-checkbox:checked").each(function() {
                    let guestId = $(this).data("guest-id");
                    let bookingId = $(this).data("booking-id");

                    console.log("Guest ID:", guestId, "Booking ID:", bookingId); // Debugging

                    selectedGuests.push({
                        guest_id: guestId,
                        booking_id: bookingId
                    });
                });

                console.log("Selected Guests:", selectedGuests); // Debugging
                return selectedGuests;
            }

            function getSelectedGuests() {
                const selectedGuests = [];
                $('.guest-checkbox:checked').each(function() {
                    selectedGuests.push($(this).val());
                });
                return selectedGuests;
            }

            function toggleBulkButtons(isEnabled) {
                $('#bulkCheckin, #bulkCheckout, #bulkPlotRoom, #bulkUnPlotRoom, #bulkUnCheckin, #bulkUnCheckout, #bulkDelete')
                    .prop('disabled', !isEnabled);
            }

            function checkSelectedGuests() {
                const selectedGuests = getSelectedGuests();
                const isSelected = selectedGuests.length > 0;
                toggleBulkButtons(isSelected);
            }
        });
    </script>
@endpush
