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
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
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
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Status Kamar</h5>
                    </div>
                    <div class="card-body">
                        <div style="width: 100%; overflow-x: auto;">
                            <table class="table align-middle nowrap table-striped table-hover " style="width:100%">
                                <thead>
                                    <tr>
                                        {{-- <th>ID</th> --}}
                                        <th>Tanggal</th>
                                        <th>Nama Kamar</th>
                                        @if (!auth()->user()->branch_id)
                                            <th>Unit</th>
                                        @endif
                                        <th>Jumlah Bed</th>
                                        <th>Bed Terisi</th>
                                        <th>Sisa Bed</th>
                                        {{-- <th>Event</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['reports'] as $room)
                                        <tr>
                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                            <td>{{ date('d, M Y', strtotime($room['report_date'])) }}</td>
                                            <td>{{ $room['nama'] }}</td>
                                            @if (!auth()->user()->branch_id)
                                                <td>{{ $room['branch'] }}</td>
                                            @endif
                                            <td>{{ $room['kapasitas'] }}</td>

                                            <td>{{ $room['terisi'] }}</td>
                                            <td>{{ $room['sisa_bed'] ?? 0 }}</td>
                                            {{-- <td>{{ $room['event'] ?? 'N/A' }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <th>{{ $data['reports'] ? $data['reports']->sum('kapasitas') : 0 }}</th>
                                        <th>{{ $data['reports'] ? $data['reports']->sum('terisi') : 0 }}</th>
                                        <th>{{ $data['reports'] ? $data['reports']->sum('sisa_bed') : 0 }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                        {{ $data['reports']->links() }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Report Kamar</h5>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal">Export</button>

                    </div>
                    <div class="card-body">
                        <div style="width: 100%; overflow-x: auto;">
                            <table class="table align-middle nowrap table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        @if (!auth()->user()->branch_id)
                                            <th>Unit</th>
                                        @endif
                                        {{-- <th>Total Kamar</th> --}}
                                        <th>Kamar Terisi</th>
                                        <th>Kamar Kosong</th>
                                        {{-- <th>Total Kapasitas</th> --}}
                                        <th>Bed Terisi</th>
                                        <th>Bed Tersedia</th>
                                        <th>Jumlah Checkin</th>
                                        <th>Jumlah Checkout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['summary'] as $summary)
                                        <tr>
                                            <td>{{ date('d, M Y', strtotime($summary['report_date'])) }}</td>
                                            @if (!auth()->user()->branch_id)
                                                <td>{{ $summary['branch'] }}</td>
                                            @endif
                                            {{-- <td>{{ $summary['total_kamar'] }}</td> --}}
                                            <td>{{ $summary['total_kamar_terisi'] }}</td>
                                            <td>{{ $summary['total_kamar_kosong'] }}</td>
                                            {{-- <td>{{ $summary['total_kapasitas'] }}</td> --}}
                                            <td>{{ $summary['total_bed_terisi'] }}</td>
                                            <td>{{ $summary['total_bed_tersedia'] }}</td>
                                            <td>{{ $summary['total_tamu_checkin'] }}</td>
                                            <td>{{ $summary['total_tamu_checkout'] }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Summary</th>
                                        {{-- <th>{{ $data['summary'] ? $data['summary']->sum('total_kamar') : 0 }}</th> --}}
                                        {{-- <th>{{ $data['summary'] ? $data['summary']->sum('total_kamar_terisi') : 0 }}</th> --}}
                                        <th>{{ $data['summary'] ? $data['summary']->sum('total_kamar_kosong') : 0 }}</th>
                                        {{-- <th>{{ $data['summary'] ? $data['summary']->sum('total_kapasitas') : 0 }}</th> --}}
                                        <th>{{ $data['summary'] ? $data['summary']->sum('total_bed_terisi') : 0 }}</th>
                                        <th>{{ $data['summary'] ? $data['summary']->sum('total_bed_tersedia') : 0 }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{ $data['summary']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pilih Range Tanggal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Pilih Rentang Tanggal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('room.export') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    <input type="hidden" id="branch_id" name="branch_id" value="{{ Auth::user()->branch_id }}" readonly>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#room-reports').DataTable({
                // responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                scrollX: true
            });
        });
    </script>
@endpush
