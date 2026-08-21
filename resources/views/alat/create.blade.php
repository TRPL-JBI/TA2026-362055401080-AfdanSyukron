<!-- resources/views/alats/create.blade.php -->

@extends('layout.main')

@section('content')
<div class="container">
    <h1>Tambah Alat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label for="serial_number" class="form-label">Serial Number</label>
            <input type="text" class="form-control" name="serial_number" value="{{ old('serial_number') }}" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="stok" class="form-control" name="stok" value="{{ old('stok') }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <input type="text" class="form-control" name="deskripsi" value="{{ old('deskripsi') }}" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Alat</label>
            <input type="file" class="form-control" name="foto" accept="image/*">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>