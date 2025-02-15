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
                    <table id="roomsTableModal"
                        class="table text-center align-middle table-sm table-striped table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kamar (Unit)</th>
                                <th>Kapasitas</th>
                                <th>Terisi</th>
                                <th>Sisa Bed</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rooms as $index => $room)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $room->nama }} ({{ $room->branch->name }})</td>
                                    <td>{{ $room->kapasitas }}</td>
                                    <td>{{ $room->terisi }}</td>
                                    <td>{{ $room->kapasitas - $room->terisi }}</td>
                                    <td>
                                        <form action="{{ route('guest.plot-room.store', $guest->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                                            <button type="submit" class="btn btn-sm btn-success"
                                                @if ($room->terisi >= $room->kapasitas) disabled @endif>
                                                {{ $room->terisi >= $room->kapasitas ? 'Penuh' : 'Pilih' }}
                                            </button>
                                        </form>
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