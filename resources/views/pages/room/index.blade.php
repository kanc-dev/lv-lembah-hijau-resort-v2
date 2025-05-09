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
                        <a href="{{ route('room.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-fill"></i> <span>Tambah Kamar</span>
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        {{-- <div class="mb-3">
                            <button id="bulkPlotEvent" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#bulkPlotEventModal" disabled>Bulk Plot Event</button>
                        </div> --}}
                        <table class="table align-middle nowrap table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                                value="option">
                                        </div>
                                    </th> --}}
                                    <th>ID</th>
                                    @if (!auth()->user()->branch_id)
                                        <th>Unit</th>
                                    @endif
                                    <th>Nama Kamar</th>
                                    <th>Type</th>
                                    {{-- <th>Price</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Kapasitas</th>
                                    <th>Terisi</th> --}}
                                    {{-- <th>Tersedia</th> --}}
                                    {{-- <th>Event</th> --}}
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['rooms'] as $room)
                                    <tr>
                                        {{-- <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input fs-15 room-checkbox" type="checkbox"
                                                    name="checkAll" value="{{ $room['id'] }}">
                                            </div>
                                        </th> --}}
                                        <td>{{ $loop->iteration }}</td>
                                        @if (!auth()->user()->branch_id)
                                            <td>{{ $room->branch->name ?? 'N/A' }}</td>
                                        @endif
                                        <td>{{ $room['nama'] }}</td>
                                        <td>{{ $room['tipe'] }}</td>
                                        <td>{{ $room['created_at'] }}</td>
                                        {{-- <td>{{ number_format($room['harga'], 0, ',', '.') }}</td> --}}
                                        {{-- <td>
                                            <span
                                                class="badge {{ $room['status'] == 'available' ? 'badge-soft-info' : 'badge-soft-secondary' }}">{{ ucfirst($room['status']) }}</span>
                                        </td> --}}
                                        {{-- <td>{{ $room['kapasitas'] }}</td>
                                        <td>{{ $room['terisi'] ?? 0 }}</td> --}}
                                        {{-- <td>{{ $room['kapasitas'] - $room['terisi'] }}</td> --}}
                                        {{-- <td>
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
                                        </td> --}}
                                        <td>
                                            <div class=" d-inline-block">
                                                <a href="{{ route('room.edit', $room['id']) }}"
                                                    class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="ri-edit-box-line"></i>
                                                </a>
                                                @if (!auth()->user()->branch_id)
                                                    <form action="{{ route('room.destroy', $room['id']) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="event.preventDefault();
                                                            Swal.fire({
                                                                title: 'Apakah Anda yakin?',
                                                                text: 'Data akan dihapus secara permanen!',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#d33',
                                                                cancelButtonColor: '#3085d6',
                                                                confirmButtonText: 'Ya, hapus!',
                                                                cancelButtonText: 'Batal'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    event.target.submit();
                                                                }
                                                            });">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm remove-item-btn"
                                                            title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data['rooms']->links() }}
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
