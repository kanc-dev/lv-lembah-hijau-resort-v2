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
                        <h5 class="mb-0 card-title">Guests Data</h5>
                        <a href="{{ route('guest.create') }}" class="btn btn-primary">
                            <i class="ri-add-fill"></i> <span>Add New Guest</span>
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif

                        <div class="mb-3">
                            <button id="bulkPlotRoom" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#bulkPlotRoomModal" disabled>Bulk Plot Kamar</button>
                            <button id="bulkCheckin" class="btn btn-success btn-sm" disabled>Bulk Check-in</button>
                            <button id="bulkCheckout" class="btn btn-danger btn-sm" disabled>Bulk Check-out</button>
                        </div>

                        <table id="scroll-horizontal" class="table align-middle nowrap table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                                value="option">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Kamar</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Unit</th>
                                    <th>Event</th>
                                    <th>Batch</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Kendaraan</th>
                                    <th>Plat No</th>
                                    <th>Rencana Check-in</th>
                                    <th>Rencana Check-out</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['guests'] as $guest)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input fs-15 guest-checkbox" type="checkbox"
                                                    name="checkAll" value="{{ $guest->id }}">
                                            </div>
                                        </th>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $guest->nama }}</td>
                                        <td>
                                            {{-- @if ($guest->guestcheckins->isNotEmpty())
                                            @else --}}
                                            @if ($guest->guestcheckins->first())
                                                <span
                                                    class="badge bg-warning">{{ $guest->guestcheckins->first()->room->nama }}</span>
                                            @else
                                                <span class="badge bg-warning">N/A</span>
                                            @endif
                                            {{-- -
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#plotRoomModal{{ $guest->id }}">
                                                Plot Kamar
                                            </button>

                                            <div class="modal fade" id="plotRoomModal{{ $guest->id }}" tabindex="-1"
                                                aria-labelledby="plotRoomModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="plotRoomModalLabel">Pilih Kamar
                                                                untuk Tamu: {{ $guest->nama }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive">
                                                                <table id="roomsTable"
                                                                    class="table text-center align-middle table-sm table-striped table-hover table-bordered">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>No</th>
                                                                            <th>Nama Kamar</th>
                                                                            <th>Kapasitas</th>
                                                                            <th>Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($data['rooms'] as $index => $room)
                                                                            @if ($guest->branch_id == $room->branch_id)
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>{{ $room->nama }}</td>
                                                                                    <td>{{ $room->kapasitas }}</td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-sm btn-success select-room-btn"
                                                                                            data-room-id="{{ $room->id }}"
                                                                                            data-guest-id="{{ $guest->id }}"
                                                                                            @if ($room->terisi >= $room->kapasitas) disabled @endif>
                                                                                            {{ $room->terisi >= $room->kapasitas ? 'Penuh' : 'Pilih' }}
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            {{-- @endif --}}
                                        </td>
                                        <td id="checkin_date_display{{ $guest->id }}">
                                            @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->room_id)
                                                @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->tanggal_checkin)
                                                    <span class="badge bg-success">
                                                        {{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkin)) }}
                                                    </span>
                                                @else
                                                    <form action="{{ route('guest.checkin', $guest->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="checkin_date"
                                                            value="{{ date('Y-m-d') }}">
                                                        <button type="submit" class="btn btn-info btn-sm">Check In</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                        <td id="checkout_date_display{{ $guest->id }}">
                                            @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->tanggal_checkin)
                                                @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->tanggal_checkout)
                                                    <span class="badge bg-danger">
                                                        {{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkout)) }}

                                                    </span>
                                                @else
                                                    <form action="{{ route('guest.checkout', $guest->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="checkout_date"
                                                            value="{{ date('Y-m-d') }}">
                                                        <button type="submit" class="btn btn-info btn-sm">Check
                                                            Out</button>
                                                    </form>
                                                @endif
                                            @endif
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
                                        <td>{{ $guest->no_hp }}</td>
                                        <td>{{ $guest->email }}</td>
                                        <td>{{ $guest->jenis_kelamin }}</td>
                                        <td>{{ $guest->kendaraan }}</td>
                                        <td>{{ $guest->no_polisi }}</td>
                                        <td>{{ date('d, M Y', strtotime($guest->tanggal_rencana_checkin)) }}</td>
                                        <td>{{ date('d, M Y', strtotime($guest->tanggal_rencana_checkout)) }}</td>

                                        <td>
                                            <a href="{{ route('guest.edit', $guest) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('guest.destroy', $guest) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this guest?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>

                                    </tr>

                                    <!-- Default Modals -->
                                @endforeach
                            </tbody>
                        </table>
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
                                        <td>
                                            <button class="btn btn-sm btn-success plot-room-btn"
                                                data-room-id="{{ $room->id }}"
                                                data-room-capacity="{{ $room->kapasitas }}">
                                                Plot
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
