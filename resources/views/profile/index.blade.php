@extends('layout.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card card-profile shadow-sm">
                <div class="row justify-content-center">
                    <div class="col-8 col-lg-8 order-lg-2">
                        <div class="text-center mt-4">
                            @if($user->mahasiswa && $user->mahasiswa->foto_profil && $user->mahasiswa->foto_profil !== 'default.png')
                                <img src="{{ asset('storage/foto_profil/' . $user->mahasiswa->foto_profil) }}" class="rounded-circle img-fluid border border-3 border-white shadow" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&size=150&bold=true" class="rounded-circle img-fluid border border-3 border-white shadow" style="width: 150px; height: 150px;">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3 text-center">
                    <div>
                        <h5 class="font-weight-bold mb-1">
                            {{ $user->name }}
                        </h5>
                        <p class="text-sm text-secondary mb-3">
                            <i class="fa fa-envelope me-1"></i> {{ $user->email }}
                        </p>
                        <span class="badge bg-primary px-3 py-2 rounded-pill text-xs">
                            {{ strtoupper($user->role->role) }}
                        </span>
                    </div>

                    <hr class="my-4">

                    <div class="text-start">
                        <h6 class="text-uppercase text-muted text-xxs font-weight-bolder mb-3">Informasi Tambahan</h6>
                        @if($user->mahasiswa)
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">NIM</div>
                                <div class="col-7 text-sm text-dark">{{ $user->mahasiswa->nim }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">WhatsApp</div>
                                <div class="col-7 text-sm text-dark">{{ $user->mahasiswa->whatsapp }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">Jurusan</div>
                                <div class="col-7 text-sm text-dark">{{ $user->mahasiswa->jurusan_mahasiswa->jurusan ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">Program Studi</div>
                                <div class="col-7 text-sm text-dark">{{ $user->mahasiswa->prodi_mahasiswa->prodi ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">Ormawa</div>
                                <div class="col-7 text-sm text-dark">{{ $user->mahasiswa->ormawa_mahasiswa->ormawa ?? '-' }}</div>
                            </div>
                        @else
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">NIP</div>
                                <div class="col-7 text-sm text-dark">{{ $user->nip ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-sm font-weight-bold text-secondary">Status</div>
                                <div class="col-7 text-sm text-dark">
                                    <span class="badge bg-{{ $user->status == 1 ? 'success' : 'secondary' }} text-xxs px-2 py-1">
                                        {{ $user->status == 1 ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form Card -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header pb-0 bg-white border-bottom border-light">
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <h4 class="mb-0 font-weight-bold">Pengaturan Profil</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white text-sm" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success text-white text-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label text-xs font-weight-bold text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label text-xs font-weight-bold text-uppercase">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        @if($user->mahasiswa)
                            <div class="row">
                                <!-- WhatsApp -->
                                <div class="col-md-6 mb-3">
                                    <label for="whatsapp" class="form-label text-xs font-weight-bold text-uppercase">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="whatsapp" value="{{ old('whatsapp', $user->mahasiswa->whatsapp) }}" required>
                                </div>
                                <!-- Profile Picture -->
                                <div class="col-md-6 mb-3">
                                    <label for="foto_profil" class="form-label text-xs font-weight-bold text-uppercase">Ubah Foto Profil</label>
                                    <input type="file" class="form-control" name="foto_profil" accept="image/*">
                                    <small class="text-muted text-xxs d-block mt-1">Maksimal 5MB. Format: jpeg, png, jpg, gif.</small>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">
                        <h6 class="text-uppercase text-muted text-xxs font-weight-bolder mb-3">Ubah Password <span class="text-secondary">(Kosongkan jika tidak ingin diubah)</span></h6>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-xs font-weight-bold text-uppercase">Password Baru</label>
                                <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter">
                            </div>
                            <!-- Password Confirmation -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label text-xs font-weight-bold text-uppercase">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
