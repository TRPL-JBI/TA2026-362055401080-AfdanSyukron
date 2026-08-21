<!-- resources/views/alats/index.blade.php -->

@extends('layout.main')

@section('content')
<a href="{{ route('alat.create') }}" class="btn btn-primary mb-3 ms-4 mt-4">Tambah Alat</a>

<div class="container-fluid py-4">
    <div class="row">
    <div class="col-12">
        <div class="card mb-4">
        <div class="card-header pb-0">
            <h6>Daftar Alat</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Serial</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stok</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($alats as $alat)
                <tr>
                <td>
                    <h6 class="mb-0 text-sm ms-3">{{ $loop->iteration}}</h6>                    
                </td>
                    <td>
                    <div class="d-flex px-2 py-1">
                        <div>
                        @if($alat->foto)
                            <img src="{{ asset('uploads/alat/' . $alat->foto) }}" class="avatar avatar-sm me-3" alt="{{ $alat->nama }}" style="object-fit: cover;">
                        @else
                            <div class="avatar avatar-sm me-3 d-flex align-items-center justify-content-center bg-light border rounded">
                                <i class="fas fa-camera text-secondary" style="font-size: 0.85rem;"></i>
                            </div>
                        @endif
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $alat->nama }}</h6>
                        <!-- <p class="text-xs text-secondary mb-0">{{ $alat->serial_number }}</p> -->
                        </div>
                    </div>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $alat->serial_number }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $alat->stok }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0" style="white-space: normal; max-width: 250px;">{{ $alat->deskripsi }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                        @if($alat->deleted_at)
                            <span class="badge bg-secondary text-xs font-weight-bold mb-0">Tidak Aktif</span>
                        @elseif($alat->is_maintenance)
                            <span class="badge bg-warning text-xs font-weight-bold mb-0">Perbaikan</span>
                        @else
                            <span class="badge bg-success text-xs font-weight-bold mb-0">Aktif</span>
                        @endif
                    </td>
                    <td class="align-middle text-center text-sm">
                    <a href="{{ route('alat.edit', $alat->id) }}" class="btn btn-warning btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Ubah alat">
                        Ubah
                    </a>
                    <a href="{{ route('alat.maintenance', $alat->id) }}" class="btn btn-{{ $alat->is_maintenance ? 'info' : 'secondary' }} btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Perbaikan alat">
                        {{ $alat->is_maintenance ? 'Selesai Perbaikan' : 'Perbaikan' }}
                    </a>
                    <a href="{{ route('alat.delete', $alat->id) }}" class="btn btn-{{ $alat->deleted_at ? 'primary' : 'danger' }} btn-xs font-weight-bold text-sm" data-toggle="tooltip" data-original-title="Ubah status">
                        {{ $alat->deleted_at ? 'Aktif' : 'Non Aktif' }}
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