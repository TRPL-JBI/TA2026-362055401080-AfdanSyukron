<!-- resources/views/alats/create.blade.php -->

@extends('layout.main')

@section('content')
<div class="container">
    <h1>Edit Alat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama </label>
            <input type="text" class="form-control" name="nama" value="{{ $alat->nama ?? old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label for="serial_number" class="form-label">Serial Number</label>
            <input type="text" class="form-control" name="serial_number" value="{{ $alat->serial_number ?? old('serial_number') }}" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="stok" class="form-control" name="stok" value="{{ $alat->stok ?? old('stok') }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <input type="text" class="form-control" name="deskripsi" value="{{ $alat->deskripsi ?? old('deskripsi') }}" required>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Alat</label>
            <input type="file" class="form-control" name="foto" accept="image/*">
            @if($alat->foto)
                <div class="mt-2">
                    <img src="{{ asset('uploads/alat/' . $alat->foto) }}" alt="{{ $alat->nama }}" class="img-thumbnail" style="max-height: 150px;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>