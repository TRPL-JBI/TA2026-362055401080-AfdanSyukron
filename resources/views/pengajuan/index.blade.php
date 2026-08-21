@extends('layout.main')

@section('content')
<div class="container-fluid py-4">
    <!-- Session Messages -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-white mx-4" role="alert">
        <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show text-white mx-4" role="alert">
        <span class="alert-text"><strong>Berhasil!</strong> {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(strtolower(auth()->user()->role->role) == 'mahasiswa')
    <div class="mb-3">
        <a href="{{ route('pengajuan.create') }}" class="btn text-white" style="background-color: #004DB4 !important; border-color: #004DB4 !important;">
            <i class="fas fa-plus me-2"></i> Tambah Pengajuan Alat
        </a>
    </div>
    @endif

    <div class="row">
        <!-- Tabel Status Ketersediaan Alat (Hanya di halaman Monitoring) -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-boxes me-2 text-primary"></i> Ketersediaan Alat</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 border-0">Nama Alat</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Total Stok</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-info border-0">Tersedia</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alat as $j)
                                <tr class="{{ $j->stok_tersedia <= 0 ? 'bg-light' : '' }}">
                                    <td><h6 class="mb-0 text-sm ms-3">{{ $loop->iteration }}</h6></td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if($j->foto)
                                                    <img src="{{ asset('uploads/alat/' . $j->foto) }}" class="avatar avatar-sm me-3" alt="{{ $j->nama }}" style="object-fit: cover;">
                                                @else
                                                    <div class="avatar avatar-sm me-3 d-flex align-items-center justify-content-center bg-light border rounded">
                                                        <i class="fas fa-camera text-secondary" style="font-size: 0.85rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $j->nama }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($j->deskripsi, 60) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $j->stok }} unit</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-{{ $j->stok_tersedia > 0 ? 'info' : 'secondary' }}">
                                            {{ $j->stok_tersedia }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($j->is_maintenance)
                                            <span class="text-warning font-weight-bold text-xs">Sedang Perbaikan</span>
                                        @elseif($j->stok_tersedia <= 0)
                                            <span class="text-danger font-weight-bold text-xs">Penuh / Dipinjam</span>
                                        @else
                                            <span class="text-success font-weight-bold text-xs text-uppercase">Tersedia</span>
                                        @endif
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