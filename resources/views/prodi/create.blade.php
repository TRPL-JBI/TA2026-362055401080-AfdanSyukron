<!-- resources/views/prodis/create.blade.php -->

@extends('layout.main')

@section('content')
<div class="container">
    <h1>Tambah Prodi</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('prodi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Prodi</label>
            <input type="text" class="form-control" name="prodi" value="{{ old('prodi') }}" required>
        </div>
        <div class="mb-3">
            <label for="foto_profil" class="form-label">Jurusan</label>
            <select class="form-select" aria-label="Default select example" name="jurusan" id="jurusan">
                <option selected>Open this select menu</option>
                <!-- <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option> -->
                @foreach($jurusan as $j)
                <option value="{{ $j->id }}">{{ $j->jurusan }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


<script>
            $(document).ready(function() {
            $('#jurusan').on('change', function() {
               var jurusanID = $(this).val();
               if(jurusanID) {
                   $.ajax({
                       url: '/getProdi/'+jurusanID,
                       type: "GET",
                       data : {"_token":"{{ csrf_token() }}"},
                       dataType: "json",
                       success:function(data)
                       {
                         if(data){
                            $('#prodi').empty();
                            $('#prodi').append('<option hidden>Choose prodi</option>'); 
                            $.each(data, function(key, prodi){
                                $('select[name="prodi"]').append('<option value="'+ prodi.id +'">' + prodi.prodi+ '</option>');
                            });
                        }else{
                            $('#prodi').empty();
                        }
                     }
                   });
               }else{
                 $('#prodi').empty();
               }
            });
            });
        </script>