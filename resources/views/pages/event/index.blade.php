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
                        <h5 class="mb-0 card-title">Data Event</h5>
                        <a href="{{ route('event.create') }}" class="btn btn-primary">
                            <i class="ri-add-fill"></i> <span>Tambah Event</span>
                        </a>
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
                                    <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                                value="option">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Nama Kelas / Pendidikan</th>
                                    <th>Deskripsi</th>
                                    <th>Unit / Branch</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['events'] as $event)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input fs-15" type="checkbox" name="checkAll"
                                                    value="option{{ $event['id'] }}">
                                            </div>
                                        </th>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $event['nama_kelas'] }}</td>
                                        <td>{{ $event['deskripsi'] }}</td>
                                        <td>{{ isset($event['branch_id']) ? $event->branch['name'] : 'N/A' }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="align-middle ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('event.edit', $event) }}" class="dropdown-item">
                                                            <i class="align-bottom ri-pencil-fill me-2 text-muted"></i> Edit
                                                        </a>
                                                    </li>
                                                    @if (!auth()->user()->branch_id)
                                                    <li>
                                                        <form action="{{ route('event.destroy', $event) }}" method="POST"
                                                            class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this room?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item remove-item-btn"
                                                                onclick="confirmDelete({{ $event['id'] }})">
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
@endsection
