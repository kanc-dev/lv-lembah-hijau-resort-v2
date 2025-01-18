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

                        <table id="example" class="table align-middle table-bordered dt-responsive nowrap table-striped"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                                value="option">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Branch</th>
                                    <th>Batch</th>
                                    <th>Vehicle</th>
                                    <th>Plate No</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Room</th>
                                    <th>Actions</th>
                                    <th>Planned Check-in Date</th>
                                    <th>Planned Check-out Date</th>
                                    <th>Actual Check-in Date</th>
                                    <th>Actual Check-out Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['guests'] as $guest)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input fs-15" type="checkbox" name="checkAll"
                                                    value="option1">
                                            </div>
                                        </th>
                                        {{-- <td>{!! $guest->branch->rooms !!}</td> --}}
                                        <td>{{ $guest->id }}</td>
                                        <td>{{ $guest->nama }}</td>
                                        <td>{{ $guest->jenis_kelamin }}</td>
                                        <td>{{ $guest->branch->name }}</td>
                                        <td>{{ $guest->batch }}</td>
                                        <td>{{ $guest->kendaraan }}</td>
                                        <td>{{ $guest->no_polisi }}</td>
                                        <td>{{ $guest->no_hp }}</td>
                                        <td>{{ $guest->email }}</td>
                                        <td>
                                            {{ $guest->guestcheckins->first() ? $guest->guestcheckins->first()->room->nama : 'N/A' }}
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
                                        <td>{{ $guest->tanggal_rencana_checkin }}</td>
                                        <td>{{ $guest->tanggal_rencana_checkout }}</td>
                                        <td id="checkin_date_display{{ $guest->id }}">
                                            @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->tanggal_checkin)
                                                {{ $guest->guestcheckins->first()->tanggal_checkin }}
                                            @else
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#checkinModal{{ $guest->id }}">
                                                    Set Check-in
                                                </button>
                                            @endif
                                        </td>
                                        <td id="checkout_date_display{{ $guest->id }}">
                                            @if ($guest->guestcheckins->isNotEmpty() && $guest->guestcheckins->first()->tanggal_checkout)
                                                {{ $guest->guestcheckins->first()->tanggal_checkout }}
                                            @else
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#checkoutModal{{ $guest->id }}">
                                                    Set Check-out
                                                </button>
                                            @endif
                                        </td>

                                    </tr>

                                    <!-- Modal for Check-in -->
                                    <div class="modal fade" id="checkinModal{{ $guest->id }}" tabindex="-1"
                                        aria-labelledby="checkinModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="checkinModalLabel">Set Check-in for
                                                        {{ $guest->nama }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="checkin_date" class="form-label">Select Check-in
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="checkin_date{{ $guest->id }}" name="checkin_date">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="room_select{{ $guest->id }}"
                                                            class="form-label">Select Room</label>
                                                        <select class="form-control" id="room_select{{ $guest->id }}"
                                                            name="room_id">
                                                            <option value="">Select Room</option>
                                                            @foreach ($guest->branch->rooms as $room)
                                                                <option value="{{ $room->id }}">{{ $room->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="setCheckinDate({{ $guest->id }})">Set Check-in</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal for Check-out -->
                                    <div class="modal fade" id="checkoutModal{{ $guest->id }}" tabindex="-1"
                                        aria-labelledby="checkoutModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="checkoutModalLabel">Set Check-out for
                                                        {{ $guest->nama }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="checkout_date" class="form-label">Select Check-out
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="checkout_date{{ $guest->id }}" name="checkout_date">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="setCheckoutDate({{ $guest->id }})">Set
                                                        Check-out</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        // $('#checkinModal{{ $guest->id }}').on('show.bs.modal', function() {
                                        //     loadRoomsForBranch({{ $guest->id }});
                                        // });
                                    </script>
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
    function loadRoomsForBranch(guestId) {
        fetch('/guest/rooms/' + guestId)
            .then(response => response.json())
            .then(data => {
                let roomSelect = document.getElementById('room_select' + guestId);
                roomSelect.innerHTML = ''; // Clear existing options

                // Add a default "Select Room" option
                let defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select Room';
                roomSelect.appendChild(defaultOption);

                // Populate room options
                data.forEach(room => {
                    let option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.nama;
                    roomSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading rooms:', error));
    }

    // Call loadRoomsForBranch when the modal is shown


    // Set the check-in date and room for the guest
    function setCheckinDate(guestId) {
        let checkinDate = document.getElementById('checkin_date' + guestId).value;
        let roomId = document.getElementById('room_select' + guestId).value;

        if (checkinDate && roomId) {
            // Make AJAX request to update the check-in date and room assignment
            fetch('/guest/checkin/' + guestId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        checkin_date: checkinDate,
                        room_id: roomId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Update the UI to show the new check-in date and room
                        document.getElementById('checkin_date_display' + guestId).textContent = data.checkin_date;
                        // Close the modal
                        let modal = document.getElementById('checkinModal' + guestId);
                        let modalInstance = bootstrap.Modal.getInstance(modal);
                        modalInstance.hide();
                        location.reload();
                        window.location.reload();
                    } else {
                        alert('Error updating check-in information.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    function setCheckoutDate(guestId) {
        let checkoutDate = document.getElementById('checkout_date' + guestId).value;

        if (checkoutDate) {
            // Make AJAX request to update the check-out date
            fetch('/guest/checkout/' + guestId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        checkout_date: checkoutDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Update the UI to show the new check-out date
                        document.getElementById('checkout_date_display' + guestId).textContent = data.checkout_date;
                        // Close the modal
                        let modal = document.getElementById('checkoutModal' + guestId);
                        let modalInstance = bootstrap.Modal.getInstance(modal);
                        modalInstance.hide();
                        window.location.reload();
                    } else {
                        alert('Error updating check-out date.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
</script>
