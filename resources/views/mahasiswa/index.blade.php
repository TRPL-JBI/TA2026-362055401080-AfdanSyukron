<!-- resources/views/mahasiswas/index.blade.php -->

@extends('layout.main')

@section('content')
<div class="d-flex align-items-center">
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary mb-3 ms-4 mt-4">Tambah Mahasiswa</a>
    <button type="button" class="btn btn-success mb-3 ms-2 mt-4" data-bs-toggle="modal" data-bs-target="#importModal">
        Import Mahasiswa
    </button>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Pilih File Excel (.xlsx / .csv)</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row">
    <div class="col-12">
        <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Daftar Mahasiswa</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Whatsapp</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mahasiswas as $mahasiswa)
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
                        <h6 class="mb-0 text-sm">{{ $mahasiswa->nama }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $mahasiswa->email }}</p>
                        </div>
                    </div>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $mahasiswa->nim }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $mahasiswa->whatsapp }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Edit user">
                        Edit
                    </a>
                    <a href="{{ route('mahasiswa.delete', $mahasiswa->id) }}" class="btn btn-danger btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Edit user">
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