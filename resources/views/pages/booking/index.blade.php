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
                        <h5 class="mb-0 card-title">Data Reservasi</h5>
                        <a href="{{ route('booking.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-fill"></i> <span>Reservasi</span>
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        <form action="{{ route('booking.index') }}" method="GET">
                            <div class="row g-2 justify-content-end">
                                <div class="col-md-3">
                                    <input type="text" name="nama_kelas" class="form-control" placeholder="Cari Kelas / Pendidikan..." value="{{ request('nama_kelas') }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="gap-2 d-flex justify-content-end">
                                        <button type="submit" class="btn w-100 btn-primary"><i class="ri-search-line"></i></button>
                                        <a href="{{ route('booking.index') }}" class="btn w-100 btn-danger"><i class="ri-refresh-line"></i></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="" class="table align-middle table-striped table-hover nowrap" style="width:100%">
                            <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kelas / Pendidikan</th>
                                        <th>Jumlah Peserta</th>
                                        <th>Unit Asal</th>
                                        <th>Unit Tujuan</th>
                                        <th>Rencana Check In/Out</th>
                                        <th>Total Kamar</th>
                                        <th>Created At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data['bookings']->isEmpty())
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data reservasi</td>
                                        </tr>
                                    @endif
                                    @foreach ($data['bookings'] as $booking)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $booking->event->nama_kelas }}</td>
                                            <td>{{ $booking->jumlah_peserta }}</td>
                                            <td>{{ $booking->originBranch->name }}</td>
                                            <td>{{ $booking->destinationBranch->name }}</td>
                                            <td>
                                                {{ date('d, M Y', strtotime($booking->tanggal_rencana_checkin)) . ' / ' . date('d, M Y', strtotime($booking->tanggal_rencana_checkout)) }}
                                            </td>
                                            <td>
                                                @if (isset($booking->eventPlotingRooms))
                                                    {{ $booking->eventPlotingRooms->count() }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $booking->created_at }}</td>
                                            {{-- <td>
                                                @if (isset($booking->eventPlotingRooms) && count($booking->eventPlotingRooms) > 0)
                                                <div class="flex-wrap gap-1 d-flex">
                                                    @foreach ($booking->eventPlotingRooms as $room)
                                                    <span class="badge badge-soft-dark">{{ $room->room->nama }}</span><br>
                                                    @endforeach
                                                </div>
                                                @else
                                                    N/A
                                                @endif
                                            </td> --}}
                                            <td>
                                                <div class="gap-2 d-flex">
                                                    <a href="{{ route('booking.plotRooms', $booking->id) }}" class="btn btn-sm btn-info" title="Plot Kamar">
                                                        <i class="ri-hotel-bed-line"></i>
                                                    </a>
                                                    <a href="{{ route('booking.edit', $booking->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit"><i class="ri-edit-box-line"></i></a>
                                                    @if (!auth()->user()->branch_id)
                                                        <form action="{{ route('booking.destroy', $booking->id) }}" method="POST"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $data['bookings']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
