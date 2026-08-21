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

    @php
        $userRole = strtolower(auth()->user()->role->role);
        $isAdminOrKepala = in_array($userRole, ['staff admin', 'kepala humas']);
    @endphp

    <div class="row">
        <!-- Tabel Daftar Pengajuan -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6><i class="fa fa-history text-success me-2"></i> History Pengajuan Alat</h6>
                        @if($isAdminOrKepala)
                        <form action="{{ route('pengajuan.report') }}" method="GET" target="_blank" class="d-flex align-items-center gap-2">
                            <select name="bulan" class="form-select form-select-sm" style="width: auto; font-size: 12px;">
                                @php
                                    $bulanArr = [
                                        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
                                        5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
                                        9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
                                    ];
                                @endphp
                                @foreach($bulanArr as $num => $nama)
                                <option value="{{ $num }}" {{ $num == now()->month ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="tahun" value="{{ now()->year }}" min="2020" max="{{ now()->year + 1 }}" class="form-control form-control-sm" style="width: 80px; font-size: 12px;">
                            <button type="submit" class="btn btn-sm btn-danger mb-0 d-flex align-items-center gap-1" style="white-space: nowrap;">
                                <i class="fa fa-file-pdf-o"></i> Download PDF
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 border-0">Nama Kegiatan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Tgl Pengajuan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Pinjam</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Kembali</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuans as $pengajuan)
                                <tr>
                                    <td><h6 class="mb-0 text-sm ms-3">{{ $loop->iteration }}</h6></td>
                                    <td><p class="text-sm font-weight-bold mb-0 ms-2">{{ $pengajuan->nama_kegiatan }}</p></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs">{{ $pengajuan->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs">{{ date('d/m/Y', strtotime($pengajuan->tanggal_peminjaman)) }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs">{{ date('d/m/Y', strtotime($pengajuan->tanggal_pengembalian)) }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @php
                                            $status = strtolower($pengajuan->status);
                                            $statusColor = 'warning'; // default: pending
                                            $statusText = 'MENUNGGU';
                                            if($status == 'verified' || $status == 'approved') {
                                                $statusColor = 'success';
                                                $statusText = $status == 'verified' ? 'TERVERIFIKASI' : 'DISETUJUI';
                                            }
                                            if($status == 'decline' || $status == 'rejected') {
                                                $statusColor = 'danger';
                                                $statusText = 'DITOLAK';
                                            }
                                            if($status == 'finished') {
                                                $statusColor = 'primary';
                                                $statusText = 'SELESAI';
                                            }
                                        @endphp
                                        <span class="badge badge-sm bg-gradient-{{ $statusColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-info py-1 px-3 mb-0">Lihat</a>
                                        @if(strtolower(auth()->user()->role->role) == 'mahasiswa' && ($pengajuan->status == 'pending' || $pengajuan->status == 'decline'))
                                            <a href="{{ route('pengajuan.edit', $pengajuan->id) }}" class="btn btn-sm btn-warning py-1 px-3 mb-0">Ubah</a>
                                            <a href="{{ route('pengajuan.delete', $pengajuan->id) }}" class="btn btn-sm btn-danger py-1 px-3 mb-0" onclick="return confirm('Hapus pengajuan ini?')">Hapus</a>
                                        @elseif(strtolower(auth()->user()->role->role) == 'kepala humas' && $pengajuan->status == 'pending')
                                            <a href="{{ route('pengajuan.verif', $pengajuan->id) }}" class="btn btn-sm btn-success py-1 px-3 mb-0">Verifikasi</a>
                                            <a href="{{ route('pengajuan.decline', $pengajuan->id) }}" class="btn btn-sm btn-danger py-1 px-3 mb-0">Tolak</a>
                                        @elseif($pengajuan->status == 'verified' && (strtolower(auth()->user()->role->role) == 'kepala humas' || strtolower(auth()->user()->role->role) == 'staff admin' || strtolower(auth()->user()->role->role) == 'staff humas'))
                                            <a href="{{ route('pengajuan.finish', $pengajuan->id) }}" class="btn btn-sm btn-primary py-1 px-3 mb-0" onclick="return confirm('Alat sudah dikembalikan?')">Selesai</a>
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
