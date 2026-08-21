<!-- resources/views/ormawa/edit.blade.php -->

@extends('layout.main')

@section('content')
<div class="container">
    <h1>Edit Ormawa</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ormawa.update', $ormawa->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Ormawa</label>
            <input type="text" class="form-control" name="nama" value="{{ $ormawa->ormawa ?? old('nama') }}" required>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection