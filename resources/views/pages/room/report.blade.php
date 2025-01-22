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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Report Kamar</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        <table id="scroll-horizontal" class="table align-middle nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <td>Tanggal Laporan</td>
                                    <th>Nama Kamar</th>
                                    <th>Unit</th>
                                    <th>Kapasitas</th>
                                    <th>Terisi</th>
                                    <th>Tersedia</th>
                                    <th>Event</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['reports'] as $room)
                                    <tr>
                                        <td>{{ $room['id'] }}</td>
                                        <td>{{ date('d, M Y', strtotime($room['report_date'])) }}</td>
                                        <td>{{ $room['nama'] }}</td>
                                        <td>{{ $room['branch'] }}</td>
                                        <td>{{ $room['kapasitas'] }}</td>

                                        <td>{{ $room['terisi'] }}</td>
                                        <td>{{ $room['sisa_bed'] ?? 0 }}</td>
                                        <td>{{ $room['event'] ?? 'N/A' }}</td>
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
