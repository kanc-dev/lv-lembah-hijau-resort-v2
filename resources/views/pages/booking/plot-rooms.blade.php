@extends('layouts.app')

@section('content')
    <div class="container-fluid">
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Reservasi ID #{{ $booking->id }}</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Kelas</th>
                                <td>{{ $booking->event->nama_kelas }}</td>
                            </tr>
                            <tr>
                                <th>Unit Asal -> Tujuan</th>
                                <td>{{ $booking->originBranch->name }} -> {{ $booking->destinationBranch->name }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Peserta</th>
                                <td id="totalPeserta">{{ $booking->jumlah_peserta }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Rencana Check-in</th>
                                <td>{{ $booking->tanggal_rencana_checkin }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Rencana Check-out</th>
                                <td>{{ $booking->tanggal_rencana_checkout }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Pilih Kamar</h4>

                        <form action="{{ route('booking.storePlotRooms', $booking->id) }}" method="POST">
                            @csrf

                            <table id="roomslist" class="table overflow-scroll table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Pilih</th>
                                        <th>Unit</th>
                                        <th>Nama Kamar</th>
                                        <th>Kapasitas</th>
                                        <th>Status</th>
                                        <th>Event</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalKapasitas = 0; @endphp
                                    @foreach ($data['rooms'] as $room)
                                        @php
                                            $isSelected = $booking->eventPlotingRooms->contains('room_id', $room->id);
                                            if ($isSelected) {
                                                $totalKapasitas += $room->capacity;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="rooms[]" value="{{ $room->id }}"
                                                    class="room-checkbox" data-capacity="{{ $room->kapasitas ?? 0 }}"
                                                    @if ($isSelected) checked @endif>
                                            </td>
                                            <td>{{ $room->branch->name }}</td>
                                            <td>{{ $room->nama }}</td>
                                            <td>{{ $room->kapasitas }}</td>
                                            <td><span
                                                    class="badge {{ $room->status == 'available' ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $room->status }}</span>
                                            </td>
                                            <td>{!! $room->nama_kelas !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{--
                        <div class="d-flex justify-content-end">
                            {{ $data['rooms']->links() }}
                        </div> --}}

                            <div class="my-3">
                                <label for="totalKapasitas">Total Kapasitas yang Dipilih:</label>
                                <input type="text" id="totalKapasitas" class="form-control" value="0" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('booking.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #roomslist tbody {
            display: block;
            max-height: 400px;
            /* Sesuaikan tinggi sesuai kebutuhan */
            overflow-y: scroll;
        }

        #roomslist thead,
        #roomslist tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        #roomslist thead {
            width: calc(100% - 17px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let totalKapasitas = 0;
            let totalKamar = 0;
            let selectedRooms = @json($data['selected_rooms']); // Menyimpan kamar yang sudah dipilih dari backend

            // Inisialisasi DataTables
            let table = $('#example').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false
            });

            function updateTotal() {
                let checkedRooms = $(".room-checkbox:checked");
                totalKapasitas = 0;
                totalKamar = checkedRooms.length;

                checkedRooms.each(function() {
                    totalKapasitas += parseInt($(this).data("capacity"));
                });

                $("#totalKapasitas").val(totalKapasitas);
            }

            // Menandai kembali checkbox yang sudah dipilih dari backend
            $(".room-checkbox").each(function() {
                if (selectedRooms.includes(parseInt($(this).val()))) {
                    $(this).prop("checked", true);
                }
            });

            // Event listener untuk checkbox
            $(document).on("change", ".room-checkbox", function() {
                updateTotal();
            });

            // Hitung total saat halaman dimuat (jika ada yang sudah terpilih)
            updateTotal();
        });
    </script>
@endpush
