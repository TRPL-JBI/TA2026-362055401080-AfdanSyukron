<!-- resources/views/users/create.blade.php -->

@extends('layout.main')

@section('content')
<div class="container">
    <h1>Tambah user</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">NIP</label>
            <input type="text" class="form-control" name="nip" value="{{ old('nip') }}" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Email</label>
            <input type="text" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Password</label>
            <input type="text" class="form-control" name="password" value="{{ old('password') }}" required>
        </div>
        <div class="mb-3">
            <label for="foto_profil" class="form-label">Role</label>
            <select class="form-select" aria-label="Default select example" name="role" id="role">
                <option selected>Open this select menu</option>
                <!-- <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option> -->
                @foreach($role as $j)
                <option value="{{ $j->id }}">{{ $j->role }}</option>
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
            $('#role').on('change', function() {
               var roleID = $(this).val();
               if(roleID) {
                   $.ajax({
                       url: '/getuser/'+roleID,
                       type: "GET",
                       data : {"_token":"{{ csrf_token() }}"},
                       dataType: "json",
                       success:function(data)
                       {
                         if(data){
                            $('#user').empty();
                            $('#user').append('<option hidden>Choose user</option>'); 
                            $.each(data, function(key, user){
                                $('select[name="user"]').append('<option value="'+ user.id +'">' + user.user+ '</option>');
                            });
                        }else{
                            $('#user').empty();
                        }
                     }
                   });
               }else{
                 $('#user').empty();
               }
            });
            });
        </script>