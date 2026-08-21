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

    <form action="{{ route('prodi.update', $prodi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama prodi</label>
            <input type="text" class="form-control" name="nama" value="{{ $prodi->prodi ?? old('nama') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="foto_profil" class="form-label">Jurusan</label>
            <select class="form-select" aria-label="Default select example" name="jurusan" id="jurusan">
                <option selected>Open this select menu</option>
                <!-- <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option> -->
                @foreach($jurusan as $j)
                <option value="{{ $j->id }}" @selected( $prodi->jurusan_id == $j->id )>{{ $j->jurusan }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

@endsection