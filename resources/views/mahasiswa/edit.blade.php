<!-- resources/views/mahasiswas/create.blade.php -->

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

    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama </label>
            <input type="text" class="form-control" name="nama" value="{{ $mahasiswa->nama ?? old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="text" class="form-control" name="nim" value="{{ $mahasiswa->nim ?? old('nim') }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $mahasiswa->email ?? old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="whatsapp" class="form-label">WhatsApp</label>
            <input type="text" class="form-control" name="whatsapp" value="{{ $mahasiswa->whatsapp ?? old('whatsapp') }}" required>
        </div>
        <div class="mb-3">
            <label for="foto_profil" class="form-label">Foto Profil</label>
            <input type="file" class="form-control" name="foto_profil" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="foto_profil" class="form-label">Jurusan</label>
            <select class="form-select" aria-label="Default select example" name="jurusan" id="jurusan">
                <option>Open this select menu</option>
                <!-- <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option> -->
                @foreach($jurusan as $j)
                <option value="{{ $j->id }}" @selected( $mahasiswa->jurusan == $j->id )>{{ $j->jurusan }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
                        <label for="prodi" class="form-label">Prodi</label>
                        <select class="form-control" name="prodi" id="prodi"></select>
                    </div>

        <div class="mb-3">
            <label for="foto_profil" class="form-label">Ormawa</label>
            <select class="form-select" aria-label="Default select example" name="ormawa" id="ormawa">
                <option>Open this select menu</option>
                <!-- <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option> -->
                @foreach($ormawa as $j)
                <option value="{{ $j->id }}" @selected( $mahasiswa->ormawa == $j->id )>{{ $j->ormawa }}</option>
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

            //get data prodi ketika ada perubahan di jurusan
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

            //get data prodi ketika akses pertama kali
            var jurusanID = '{!! $mahasiswa->jurusan !!}';
            
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
                        // $('#prodi').append('<option hidden>Choose prodi</option>'); 
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

        </script>