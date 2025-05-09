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
                        <a href="{{ route('event.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-fill"></i> <span>Tambah Event</span>
                        </a>
                    </div>
                    <div class="card-body">
                        {{-- @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif --}}
                        <table class="table align-middle nowrap table-striped table-hover" style="width:100%">
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
                                    @if (!auth()->user()->branch_id)
                                        <th>Unit / Branch</th>
                                    @endif
                                    <th>Created At</th>
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
                                        @if (!auth()->user()->branch_id)
                                            <td>{{ isset($event['branch_id']) ? $event->branch['name'] : 'N/A' }}</td>
                                        @endif
                                        <td>{{ $event['created_at'] }}</td>
                                        <td>
                                            <div class=" d-inline-block">
                                                <a href="{{ route('event.edit', $event) }}" class="btn btn-warning btn-sm"
                                                    title="Edit">
                                                    <i class="ri-edit-box-line"></i>
                                                </a>
                                                @if (!auth()->user()->branch_id)
                                                    <form id="delete-form-{{ $event['id'] }}"
                                                        action="{{ route('event.destroy', $event) }}" method="POST"
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
                        {{ $data['events']->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/event/" + id + "/delete";
                }
            })
        }
    </script>
@endsection
