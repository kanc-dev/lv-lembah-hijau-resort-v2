@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $data['page_title'] }}</h4>
                    <div class="page-title-right">
                        <ol class="m-0 breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
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

                        <table id="example" class="table align-middle dt-responsive nowrap table-striped"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Branch</th>
                                    <th>Batch</th>
                                    <th>Vehicle</th>
                                    <th>Plate No</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Kamar</th>
                                    <th>Rencana Check-in</th>
                                    <th>Rencana Check-out</th>
                                    <th>Actual Check-in</th>
                                    <th>Actual Check-out</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['guests'] as $guest)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $guest->nama }}</td>
                                        <td>{{ $guest->jenis_kelamin }}</td>
                                        <td>{{ $guest->branch->name }}</td>
                                        <td>{{ $guest->batch }}</td>
                                        <td>{{ $guest->kendaraan }}</td>
                                        <td>{{ $guest->no_polisi }}</td>
                                        <td>{{ $guest->no_hp }}</td>
                                        <td>{{ $guest->email }}</td>
                                        <td>
                                            @if ($guest->guestcheckins->isNotEmpty())
                                                {{ $guest->guestcheckins->first() ? $guest->guestcheckins->first()->room->nama : 'N/A' }}
                                            @else
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#plotRoomModal{{ $guest->id }}">
                                                    Plot Kamar
                                                </button>

                                                <div class="modal fade" id="plotRoomModal{{ $guest->id }}"
                                                    tabindex="-1" aria-labelledby="plotRoomModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="plotRoomModalLabel">Pilih Kamar
                                                                    untuk Tamu: {{ $guest->nama }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                                <th>Terisi</th>
                                                                                <th>Tersedia</th>
                                                                                <th>Aksi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($data['rooms'] as $index => $room)
                                                                                <tr>
                                                                                    <td>{{ $index + 1 }}</td>
                                                                                    <td>{{ $room->nama }}</td>
                                                                                    <td>{{ $room->kapasitas }}</td>
                                                                                    <td>{{ $room->terisi ?? 0 }}</td>
                                                                                    <td>{{ $room->kapasitas - $room->terisi }}
                                                                                    </td>
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
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>

                                        <td>{{ date('d, M Y', strtotime($guest->tanggal_rencana_checkin)) }}</td>
                                        <td>{{ date('d, M Y', strtotime($guest->tanggal_rencana_checkout)) }}</td>
                                        <td id="checkin_date_display{{ $guest->id }}">
                                            @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->room_id)
                                                @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->tanggal_checkin)
                                                    {{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkin)) }}
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
                                                    {{ date('d, M Y', strtotime($guest->guestcheckins->first()->tanggal_checkout)) }}
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
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkinButtons = document.querySelectorAll('.set-checkin-btn');
        console.log(checkinButtons); // Pastikan tombol ada di DOM

        checkinButtons.forEach(button => {
            button.addEventListener('click', function() {
                console.log('Tombol Check-in diklik!');
                const guestId = this.getAttribute('data-guest-id');
                const today = new Date().toISOString().split('T')[0];
                console.log(today);
            });
        });

    })
</script>

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

            // checkinButtons.forEach(button => {
            //     button.addEventListener('click', function() {
            //         console.log('Tombol Check-in diklik!');
            //         const guestId = this.getAttribute('data-guest-id');
            //         const today = new Date().toISOString().split('T')[0];
            //         console.log(today);
            //     });
            // });
            // checkinButtons.forEach(button => {
            //     button.addEventListener('click', function() {
            //         console.log('Tombol Check-in diklik!');
            //         const guestId = this.getAttribute('data-guest-id');
            //         const today = new Date().toISOString().split('T')[
            //             0];
            //         console.log(today);

            //         fetch(`/guest/checkin/${guestId}`, {
            //                 method: 'POST',
            //                 headers: {
            //                     'Content-Type': 'application/json',
            //                     'X-CSRF-TOKEN': '{{ csrf_token() }}', // Pastikan CSRF token ada
            //                 },
            //                 body: JSON.stringify({
            //                     checkin_date: today,
            //                 }),
            //             })
            //             .then(response => response.json())
            //             .then(data => {
            //                 if (data.success) {
            //                     alert(data.message);
            //                     location.reload(); // Reload halaman setelah sukses
            //                 } else {
            //                     alert('Error: ' + data.message);
            //                 }
            //             })
            //             .catch(error => {
            //                 alert('Terjadi kesalahan: ' + error.message);
            //             });
            //     });
            // });

            checkoutButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const guestId = this.getAttribute('data-guest-id');
                    const today = new Date().toISOString().split('T')[0]; // Tanggal hari ini

                    fetch(`/guest/checkout/${guestId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                checkout_date: today,
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
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
    </script>
@endpush
