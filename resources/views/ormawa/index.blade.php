<!-- resources/views/ormawas/index.blade.php -->

@extends('layout.main')

@section('content')
<a href="{{ route('ormawa.create') }}" class="btn btn-primary mb-3 ms-4 mt-4">Tambah Ormawa</a>

<div class="container-fluid py-4">
    <div class="row">
    <div class="col-12">
        <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Daftar Ormawa</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ormawa</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
                </thead>
                <tbody>
                
                @foreach($ormawas as $p)
                <tr>
                    <td>
                        <h6 class="mb-0 text-sm ms-3">{{ $loop->iteration}}</h6>                    
                    </td>
                    <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $p->ormawa }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <a href="{{ route('ormawa.edit', $p->id) }}" class="btn btn-warning btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                    </a>
                    <a href="{{ route('ormawa.delete', $p->id) }}" class="btn btn-danger btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Edit user">
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