<!-- resources/views/pengajuans/create.blade.php -->

@extends('layout.main')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet" />

<div class="container">
    <h1>Pengajuan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row pt-2">
        <div class="col-6">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="card-title">
                        <h4>Data Mahasiswa</h4>
                    </div>
                </div>
                <div class="card-body lh-lg">
                    <div class="row">
                        <div class="col-5">
                            Nama Mahasiswa
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->nama : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            NIM
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->nim : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Email
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->email : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Whatsapp
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->whatsapp : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Jurusan
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->jurusan_mahasiswa->jurusan : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Prodi
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->prodi_mahasiswa->prodi : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Ormawa
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan->mahasiswa ? $pengajuan->mahasiswa->ormawa_mahasiswa->ormawa : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
        <div class="card">
                <div class="card-header pb-0">
                    <div class="card-title">
                        <h4>Data Pengajuan</h4>
                    </div>
                </div>
                <div class="card-body lh-lg">
                    <div class="row">
                        <div class="col-5">
                            Nama Kegiatan
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ $pengajuan ? $pengajuan->nama_kegiatan : 'Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Tgl Peminjaman
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ date('d-m-Y', strtotime ($pengajuan ? $pengajuan->tanggal_peminjaman : 'Tidak Ditemukan')) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            Tgl Pengembalian
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            {{ date('d-m-Y', strtotime ($pengajuan ? $pengajuan->tanggal_pengembalian : 'Tidak Ditemukan')) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4">
                            <label class="form-label font-weight-bolder">Daftar Alat yang Dipinjam</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-items-center mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 5%">No</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Serial</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Alat</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($detail_pengajuan as $dp)
                                        <tr>
                                            <td class="align-middle text-center"><span class="text-xs font-weight-bold">{{ $loop->iteration }}</span></td>
                                            <td class="align-middle"><span class="text-xs font-weight-bold">{{ $dp->detail_alat->serial_number ?? '-' }}</span></td>
                                            <td class="align-middle"><span class="text-xs font-weight-bold">{{ $dp->detail_alat->nama ?? 'Alat tidak ditemukan' }}</span></td>
                                            <td class="align-middle text-center"><span class="text-xs font-weight-bold">{{ $dp->qty }}</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center"><span class="text-xs">Tidak ada alat yang dipinjam</span></td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-5">
                            File Pengajuan
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            @if($pengajuan && $pengajuan->file)
                                <a href="{{ asset('uploads/pengajuan/' . $pengajuan->file) }}" target="_blank" class="btn btn-sm btn-info w-100">Lihat/Unduh File</a>
                            @else
                                <span class="text-secondary">Tidak ada file</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-5">
                            KTM
                        </div>
                        <div class="col-1">
                            :
                        </div>
                        <div class="col-6">
                            @if($pengajuan && $pengajuan->ktm)
                                <a href="{{ asset('uploads/ktm/' . $pengajuan->ktm) }}" target="_blank" class="btn btn-sm btn-primary w-100">Lihat KTM</a>
                            @else
                                <span class="text-secondary">Tidak ada KTM</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>


@endsection