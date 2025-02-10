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
                        <h5 class="mb-0 card-title">Data Kamar</h5>
                        <a href="{{ route('room.create') }}" class="btn btn-primary">
                            <i class="ri-add-fill"></i> <span>Tambah Kamar</span>
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        <div class="mb-3">
                            <button id="bulkPlotEvent" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#bulkPlotEventModal" disabled>Bulk Plot Event</button>
                        </div>
                        <table id="scroll-horizontal" class="table align-middle nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                                value="option">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Unit</th>
                                    <th>Room Name</th>
                                    <th>Type</th>
                                    {{-- <th>Price</th> --}}
                                    <th>Status</th>
                                    {{-- <th>Kapasitas</th>
                                    <th>Terisi</th> --}}
                                    {{-- <th>Tersedia</th> --}}
                                    <th>Event</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['rooms'] as $room)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input fs-15 room-checkbox" type="checkbox"
                                                    name="checkAll" value="{{ $room['id'] }}">
                                            </div>
                                        </th>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $room->branch->name ?? 'N/A' }}</td>
                                        <td><a href="#!">{{ $room['nama'] }}</a></td>
                                        <td>{{ $room['tipe'] }}</td>
                                        {{-- <td>{{ number_format($room['harga'], 0, ',', '.') }}</td> --}}
                                        <td><span
                                                class="badge {{ $room['status'] == 'available' ? 'badge-soft-info' : 'badge-soft-secondary' }}">{{ ucfirst($room['status']) }}</span>
                                        </td>
                                        {{-- <td>{{ $room['kapasitas'] }}</td>
                                        <td>{{ $room['terisi'] ?? 0 }}</td> --}}
                                        {{-- <td>{{ $room['kapasitas'] - $room['terisi'] }}</td> --}}
                                        <td>
                                            @if (!$room['event_id'])
                                                @php
                                                    $events = $room->guestCheckins->flatMap(function ($checkin) {
                                                        return $checkin->guest->events ?? [];
                                                    });
                                                @endphp

                                                @if ($events->isNotEmpty())
                                                    <span>{{ $events[0]->nama_kelas }}</span><br>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            @else
                                                <span>{{ $room->event->nama_kelas }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="align-middle ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('room.edit', $room['id']) }}"
                                                            class="dropdown-item">
                                                            <i class="align-bottom ri-pencil-fill me-2 text-muted"></i> Edit
                                                        </a>
                                                    </li>
                                                    @if (!auth()->user()->branch_id)
                                                    <li>
                                                        <form action="{{ route('room.destroy', $room['id']) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this room?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item remove-item-btn"
                                                                onclick="confirmDelete({{ $room['id'] }})">
                                                                <i
                                                                    class="align-bottom ri-delete-bin-fill me-2 text-muted"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
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

    <div class="modal fade" id="bulkPlotEventModal" tabindex="-1" aria-labelledby="bulkPlotEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkPlotEventModalLabel">Pilih Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('room.bulk-plot-event') }}" method="POST">
                        @csrf
                        <select class="form-select mb-3 @error('event_id') is-invalid @enderror" id="event_id"
                            name="event_id">
                            <option value="">--Pilih Event--</option>
                            @foreach ($data['events'] as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'event_id' : '' }}>
                                    {{ $event->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="room_ids" id="room_ids">
                        <button type="submit" class="btn btn-success ">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function getSelectedRooms() {
            const selected = [];
            document.querySelectorAll('.room-checkbox:checked').forEach((checkbox) => {
                selected.push(checkbox.value);
            });
            return selected;
        }

        function toggleBulkButtons(isEnabled) {
            const bulkPlotEvent = document.getElementById('bulkPlotEvent');

            bulkPlotEvent.disabled = !isEnabled;
        }

        function checkSelectedRooms() {
            const selectedRooms = getSelectedRooms();
            const isSelected = selectedRooms.length > 0;
            toggleBulkButtons(isSelected);
            console.log(selectedRooms);

        }


        document.getElementById('checkAll').addEventListener('change', (event) => {
            const isChecked = event.target.checked;

            document.querySelectorAll('.room-checkbox').forEach((checkbox) => {
                checkbox.checked = isChecked;
            });

            checkSelectedRooms();
        });

        document.querySelectorAll('.room-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                checkSelectedRooms();
            });
        });

        document.getElementById('bulkPlotEvent').addEventListener('click', () => {
            const selectedRooms = getSelectedRooms();
            const roomIdsInput = document.getElementById('room_ids'); // Get the room_ids input
            roomIdsInput.value = selectedRooms.join(',');
        });
    </script>
@endpush
