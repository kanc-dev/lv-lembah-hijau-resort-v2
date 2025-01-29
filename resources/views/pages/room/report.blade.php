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
                        {{-- <h5 class="mb-0 card-title">Report Kamar</h5> --}}
                    </div>
                    <div class="card-body">
                        <table id="scroll-horizontal" class="table align-middle nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Tanggal</th>
                                    <th>Nama Kamar</th>
                                    <th>Unit</th>
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
                                        <td>{{ $room['branch'] }}</td>
                                        <td>{{ $room['kapasitas'] }}</td>

                                        <td>{{ $room['terisi'] }}</td>
                                        <td>{{ $room['sisa_bed'] ?? 0 }}</td>
                                        {{-- <td>{{ $room['event'] ?? 'N/A' }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{-- <h5 class="mb-0 card-title">Report Kamar</h5> --}}
                    </div>
                    <div class="card-body">
                        <table id="room-reports" class="table align-middle nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Unit</th>
                                    <th>Total Kamar Terisi</th>
                                    <th>Total Kamar Kosong</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['summary'] as $summary)
                                    <tr>
                                        <td>{{ date('d, M Y', strtotime($summary['report_date'])) }}</td>
                                        <td>{{ $summary['branch'] }}</td>
                                        <td>{{ $summary['total_kamar_terisi'] }}</td>
                                        <td>{{ $summary['total_kamar_kosong'] }}</td>
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
            $('#room-reports').DataTable({
                responsive: true, // Membuat tabel responsif pada ukuran layar kecil
                paging: true, // Mengaktifkan pagination
                searching: true, // Mengaktifkan fitur pencarian
                ordering: true, // Mengaktifkan fitur pengurutan
                info: true, // Menampilkan informasi jumlah data
                autoWidth: false // Menonaktifkan pengaturan lebar otomatis
            });

        });
    </script>
@endpush
