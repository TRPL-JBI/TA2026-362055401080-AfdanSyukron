<!-- resources/views/mahasiswa/edit.blade.php -->

@extends('layout.main')

@section('content')
<div class="container">
    <h1>Edit Mahasiswa</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jurusan.update', $jurusan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Jurusan</label>
            <input type="text" class="form-control" name="nama" value="{{ $jurusan->jurusan ?? old('nama') }}" required>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection