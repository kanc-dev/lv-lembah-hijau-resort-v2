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
                        <a href="{{ route('guest.create') }}" class="btn btn-primary">
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
                                <div class="col-md-4">
                                    <div class="form-control">
                                        <label class="form-check-label"><strong>Status Tamu:</strong></label>
                                        <div class="flex-wrap d-flex">
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="filter_status[]" value="plotted" id="plotted" {{ in_array('plotted', (array) request('filter_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="plotted">Sudah Plot Kamar</label>
                                            </div>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="filter_status[]" value="checked_in" id="checked_in" {{ in_array('checked_in', (array) request('filter_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="checked_in">Sudah Check-In</label>
                                            </div>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="checkbox" name="filter_status[]" value="checked_out" id="checked_out" {{ in_array('checked_out', (array) request('filter_status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="checked_out">Sudah Check-Out</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row g-2">

                                        <div class="col-12">
                                            <input type="text" name="search" class="form-control" placeholder="Cari Nama/Telp/Email..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-12">
                                            <select class="form-select" id="filter_gender" name="filter_gender">
                                                <option value="">--Filter Jenis Kelamin--</option>
                                                <option value="l" {{ request('filter_gender') == 'l' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="p" {{ request('filter_gender') == 'p' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <select class="form-select" id="filter_event" name="filter_event">
                                                <option value="">--Kelas / Pendidikan--</option>
                                                @foreach ($data['bookings'] as $booking)
                                                    <option value="{{ $booking->id }}">{{ $booking->event->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Unit Filter -->
                                        <div class="col-12">
                                            <div class="row g-2">
                                                <div class="col-md-9">
                                                    <select class="form-select" id="filter_unit" name="filter_unit">
                                                        <option value="">--Unit--</option>
                                                        @foreach ($data['branches'] as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="gap-2 col-md-3 d-flex ">
                                                    <button type="submit" class="btn w-100 btn-primary"><i class="ri-search-line"></i></button>
                                                    <a href="{{ route('guest.index') }}" class="btn w-100 btn-danger"><i class="ri-refresh-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="gap-2 col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i></button>
                                    <a href="{{ route('guest.index') }}" class="btn btn-danger"><i class="ri-refresh-line"></i></a>
                                </div> --}}
                            </div>
                        </form>

                        {{-- <form action="" method="POST" id="bulkActionForm" class="mt-3">
                            @csrf
                            <div class="g-2 row">
                                <div class="w-full col-3 col-md-2">
                                    <select name="bulk_action" class="form-select form-select-sm">
                                        <option value="">Pilih Aksi</option>
                                        <option value="delete">Plot Kamar</option>
                                        <option value="checkin">Check In</option>
                                        <option value="checkout">Check Out</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="submit" class="btn-sm btn btn-primary">Terapkan</button>
                                </div>
                            </div>
                        </form> --}}


                        {{-- <div class="form-control">
                        </div> --}}
                        <div class="mt-3">
                            <button id="bulkPlotRoom" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#bulkPlotRoomModal" disabled>Bulk Plot Kamar</button>
                            <button id="bulkCheckin" class="btn btn-success btn-sm" disabled>Bulk Check-in</button>
                            <button id="bulkCheckout" class="btn btn-soft-danger btn-sm" disabled>Bulk Check-out</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        {{-- <th>ID</th> --}}
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
                                        <tr>
                                            <td><input type="checkbox" class="guest-checkbox" name="guest_ids[]" value="{{ $guest->id }}"></td>
                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                            <td class="text-nowrap">{{ $guest->nama }}</td>
                                            <td>
                                                @if ($guest->guestcheckins->first())
                                                    <span class="badge bg-soft-info text-dark ">{{ $guest->guestcheckins->first()->room->nama }}</span>
                                                @else
                                                    <span class="badge bg-soft-info text-dark">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span>
                                                    @if ($guest->guestcheckins->isNotEmpty())
                                                        @if ($guest->guestcheckins->first()->tanggal_checkin)
                                                            <span class="badge bg-success">{{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkin)) }}</span>
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
                                                            <span class="badge bg-danger">{{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkout)) }}</span>
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
                                                    @foreach ($guest->events as $event)
                                                        {{ $event->nama_kelas }}
                                                    @endforeach
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
                                            <td class="hide-column">{{ date('d, M Y', strtotime($guest->tanggal_rencana_checkin)) }}</td>
                                            <td class="hide-column">{{ date('d, M Y', strtotime($guest->tanggal_rencana_checkout)) }}</td>
                                            <td>
                                                <div class="flex-wrap gap-1 d-flex">
                                                    <div>
                                                        <button class="text-nowrap btn btn-info btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#plotRoomModal{{ $guest->id }}" title="Plot Kamar">
                                                            <i class="ri-hotel-bed-line"></i>
                                                        </button>
                                                        @include('partials.modal-plot-room', ['guest' => $guest, 'rooms' => $data['rooms']])
                                                    </div>
                                                    <form action="{{ route('guest.checkin', $guest->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="checkin_date" value="{{ date('Y-m-d') }}">
                                                        <button type="submit" class="text-nowarp btn btn-success btn-sm" title="Check In"><i class="ri-calendar-check-line"></i></button>
                                                    </form>
                                                    <form action="{{ route('guest.checkout', $guest->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="checkout_date" value="{{ date('Y-m-d') }}">
                                                        <button type="submit" class="btn text-nowarp btn-soft-dark btn-sm" title="Check Out"><i class=" ri-calendar-check-fill"></i></button>
                                                    </form>
                                                    <div>
                                                        <a href="{{ route('guest.edit', $guest) }}" class="btn btn-warning btn-sm" title="Edit"><i class="ri-edit-box-line"></i></a>
                                                    </div>
                                                    @if (!auth()->user()->branch_id)
                                                        <form action="{{ route('guest.destroy', $guest) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this guest?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                                        </form>
                                                    @endif
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

    <div class="modal fade" id="bulkPlotRoomModal" tabindex="-1" aria-labelledby="bulkPlotRoomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkPlotRoomModalLabel">Pilih Kamar untuk Tamu yang Dipilih</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="roomsTable"
                            class="table text-center align-middle table-sm table-striped table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Unit</th>
                                    <th>Nama Kamar</th>
                                    <th>Kapasitas</th>
                                    <th>Bed Terisi</th>
                                    <th>Bed Tersedia</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['rooms'] as $index => $room)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $room->branch->name }}</td>
                                        <td>{{ $room->nama }}</td>
                                        <td>{{ $room->kapasitas }}</td>
                                        <td>{{ $room->bed_terisi}}</td>
                                        <td>{{ $room->bed_sisa}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-success plot-room-btn"
                                                data-room-id="{{ $room->id }}"
                                                data-room-capacity="{{ $room->kapasitas }}"
                                                @if ($room->terisi >= $room->kapasitas) disabled @endif
                                                >
                                                {{ $room->terisi >= $room->kapasitas ? 'Penuh' : 'Pilih' }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#roomsTable').DataTable({
                responsive: true, // Membuat tabel responsif pada ukuran layar kecil
                paging: true, // Mengaktifkan pagination
                searching: true, // Mengaktifkan fitur pencarian
                ordering: true, // Mengaktifkan fitur pengurutan
                info: true, // Menampilkan informasi jumlah data
                autoWidth: false // Menonaktifkan pengaturan lebar otomatis
            });
            $('#roomsTableModal').DataTable({
                responsive: true, // Membuat tabel responsif pada ukuran layar kecil
                paging: true, // Mengaktifkan pagination
                searching: true, // Mengaktifkan fitur pencarian
                ordering: true, // Mengaktifkan fitur pengurutan
                info: true, // Menampilkan informasi jumlah data
                autoWidth: false // Menonaktifkan pengaturan lebar otomatis
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkinButtons = document.querySelectorAll('.set-checkin-btn');
            const checkoutButtons = document.querySelectorAll('.set-checkout-btn');
            const selectRoomButtons = document.querySelectorAll('.select-room-btn');

            selectRoomButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-room-id');
                    const guestId = this.getAttribute('data-guest-id');

                    fetch(`/guest/plot-room/${guestId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                room_id: roomId,
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                const modal = bootstrap.Modal.getInstance(document
                                    .querySelector(`#plotRoomModal${guestId}`));
                                modal.hide();
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan: ' + error.message);
                        });
                });
            });
        });


        function getSelectedGuests() {
            const selected = [];
            document.querySelectorAll('.guest-checkbox:checked').forEach((checkbox) => {
                selected.push(checkbox.value);
            });
            return selected;
        }

        function toggleBulkButtons(isEnabled) {
            const bulkCheckinBtn = document.getElementById('bulkCheckin');
            const bulkCheckoutBtn = document.getElementById('bulkCheckout');
            const bulkPlotRoomBtn = document.getElementById('bulkPlotRoom');

            // Jika ada tamu yang terpilih, tombol diaktifkan, jika tidak, dinonaktifkan
            bulkCheckinBtn.disabled = !isEnabled;
            bulkCheckoutBtn.disabled = !isEnabled;
            bulkPlotRoomBtn.disabled = !isEnabled;
        }

        function checkSelectedGuests() {
            const selectedGuests = getSelectedGuests(); // Dapatkan tamu yang terpilih
            const isSelected = selectedGuests.length > 0; // Jika ada yang terpilih, tombol akan aktif
            toggleBulkButtons(isSelected); // Aktifkan/Nonaktifkan tombol berdasarkan kondisi
        }


        document.getElementById('checkAll').addEventListener('change', (event) => {
            const isChecked = event.target.checked;

            // Tandai semua checkbox sesuai dengan status checkAll
            document.querySelectorAll('.guest-checkbox').forEach((checkbox) => {
                checkbox.checked = isChecked;
            });

            // Periksa apakah ada tamu yang terpilih
            checkSelectedGuests();
        });

        document.querySelectorAll('.guest-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                // Periksa apakah ada tamu yang terpilih
                checkSelectedGuests();
            });
        });

        document.querySelectorAll('.plot-room-btn').forEach((button) => {
            button.addEventListener('click', async (event) => {
                let roomId = event.target.getAttribute('data-room-id');
                const roomCapacity = event.target.getAttribute('data-room-capacity');
                const selectedGuests = getSelectedGuests();
                console.log('Room ID:', roomId, 'Selected Guests:', selectedGuests);

                const data = {
                    room_id: roomId,
                    guests: selectedGuests
                };
                if (selectedGuests.length > roomCapacity) {
                    alert("kapasitas tidak cukup")
                } else {
                    try {
                        // Mengirim data ke controller menggunakan fetch
                        const response = await fetch('/guest/bulk-plot-rooms', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')
                                    .getAttribute('content'), // jika menggunakan CSRF token
                            },
                            body: JSON.stringify(data)
                        });

                        // Menangani response dari server
                        const result = await response.json();
                        if (response.ok) {
                            console.log('Data berhasil dikirim:', result);
                            alert(result.message);
                            const modal = bootstrap.Modal.getInstance(document
                                .querySelector(`#bulkPlotRoomModal`));
                            modal.hide();
                            location.reload();

                        } else {
                            console.log('Terjadi kesalahan:', result.message);
                        }
                    } catch (error) {
                        console.error('Terjadi error:', error);
                    }
                }

            });
        });

        document.getElementById('bulkCheckin').addEventListener('click', () => {
            // Ambil semua checkbox yang terpilih
            const selectedGuests = getSelectedGuests();

            console.log(selectedGuests)

            // Jika tidak ada tamu yang dipilih, tampilkan alert
            if (selectedGuests.length === 0) {
                alert('Pilih tamu terlebih dahulu!');
                return;
            }

            fetch('/guest/bulk-checkin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        guest_ids: selectedGuests
                    })
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return response.json().then(err => Promise.reject(err));
                    }
                })
                .then(data => {
                    alert('Tamu berhasil di-check-in!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Terjadi kesalahan. Coba lagi!');
                });
        });
        document.getElementById('bulkCheckout').addEventListener('click', () => {
            // Ambil semua checkbox yang terpilih
            const selectedGuests = getSelectedGuests();

            console.log(selectedGuests)

            // Jika tidak ada tamu yang dipilih, tampilkan alert
            if (selectedGuests.length === 0) {
                alert('Pilih tamu terlebih dahulu!');
                return;
            }

            fetch('/guest/bulk-checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        guest_ids: selectedGuests
                    })
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        return response.json().then(err => Promise.reject(err));
                    }
                })
                .then(data => {
                    alert('Tamu berhasil di-check-out!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Terjadi kesalahan. Coba lagi!');
                });
        });
    </script>
@endpush
