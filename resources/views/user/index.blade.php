<!-- resources/views/roles/index.blade.php -->

@extends('layout.main')

@section('content')
<a href="{{ route('user.create') }}" class="btn btn-primary mb-3 ms-4 mt-4">Tambah user</a>

<div class="container-fluid py-4">
    <div class="row">
    <div class="col-12">
        <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Daftar user</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama User</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIP</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                @foreach($users as $p)
                <tr>
                    <td>
                        <h6 class="mb-0 text-sm ms-3">{{ $loop->iteration}}</h6>                    
                    </td>
                    <td>
                    <div class="d-flex px-2 py-1">
                        <div>
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user1">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $p->name }}</h6>
                        </div>
                    </div>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $p->nip }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $p->email }}</p>
                    </td>
                    <td>
                    <!-- <p class="text-xs font-weight-bold mb-0">{{ $p->role }}</p> -->
                    <p class="text-xs text-secondary mb-0 align-middle text-center text-sm">{{ $p->role->role ?? '-' }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <a href="{{ route('user.edit', $p->id) }}" class="btn btn-warning btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                    </a>
                    <a href="{{ route('user.delete', $p->id) }}" class="btn btn-danger btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Edit user">
                        Delete
                    </a>
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
</div>
@endsection