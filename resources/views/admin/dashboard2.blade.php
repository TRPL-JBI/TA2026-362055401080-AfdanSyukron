@extends('layout.main')
@section('content')

<div class="row">
  @if(strtolower(auth()->user()->role->role) == 'mahasiswa')
  <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
    <div class="card h-100" style="background-color: #004DB4 !important;">
      <div class="card-body p-3 d-flex align-items-center justify-content-center">
        <a href="{{ route('pengajuan.create') }}" class="text-white font-weight-bolder stretched-link d-flex align-items-center" style="font-size: 1.1rem; text-decoration: none;">
          <i class="fas fa-plus me-2 fs-4"></i> Tambah Pengajuan Alat
        </a>
      </div>
    </div>
  </div>
  @endif

  <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengajuan</p>
              <h5 class="font-weight-bolder">
                {{ $totalPengajuan }}
              </h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"></span>
                Permohonan Masuk
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
              <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(strtolower(auth()->user()->role->role) != 'mahasiswa')
  <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Total User & Mahasiswa</p>
              <h5 class="font-weight-bolder">
                {{ $totalUser + $totalMahasiswa }}
              </h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"></span>
                Terdaftar di Sistem
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
              <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Stok Alat Tersedia</p>
              <h5 class="font-weight-bolder">
                {{ $totalAlat }}
              </h5>
              <p class="mb-0">
                <span class="text-danger text-sm font-weight-bolder"></span>
                Unit Alat SIPMAS
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
              <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@if(strtolower(auth()->user()->role->role) == 'mahasiswa')
<div class="row mt-4">
  <div class="col-lg-12 mb-lg-0 mb-4">
    <div class="card shadow-sm border-0 bg-transparent shadow-none">
      <img src="{{ asset('assets/img/tata-cara-sipmas.png') }}" alt="Tata Cara Penggunaan Aplikasi SIPMAS" style="border-radius: 15px; width: 100%; height: auto; object-fit: cover;">
    </div>
  </div>
</div>
@endif

@if(strtolower(auth()->user()->role->role) != 'mahasiswa')
<div class="row mt-4">
  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-header pb-0 p-3 bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
          <h6 class="mb-0 font-weight-bold">Pengajuan Terbaru</h6>
          <a href="{{ route('pengajuan.list') }}" class="text-sm font-weight-bold text-primary">Lihat Semua</a>
        </div>
      </div>
      <div class="card-body p-3">
        <div class="table-responsive">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MAHASISWA</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NAMA KEGIATAN</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TANGGAL</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STATUS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentSubmissions as $submission)
              <tr>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div>
                        <div class="avatar avatar-sm bg-gradient-secondary me-3 d-flex align-items-center justify-content-center">
                            <span class="text-white text-xs font-weight-bold">{{ strtoupper(substr($submission->mahasiswa->nama ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', $submission->mahasiswa->nama ?? ' ')[1] ?? '', 0, 1)) }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm">{{ $submission->mahasiswa->nama ?? 'Tidak Diketahui' }}</h6>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="text-sm font-weight-bold mb-0">{{ $submission->nama_kegiatan }}</p>
                </td>

                <td class="align-middle text-center">
                  <span class="text-secondary text-xs font-weight-bold">{{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y') }}</span>
                </td>
                <td class="align-middle text-center text-sm">
                   @php
                       $status = strtolower($submission->status);
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
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<!-- <div class="row mt-4">
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card ">
      <div class="card-header pb-0 p-3">
        <div class="d-flex justify-content-between">
          <h6 class="mb-2">Sales by Country</h6>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center ">
          <tbody>
            <tr>
              <td class="w-30">
                <div class="d-flex px-2 py-1 align-items-center">
                  <div>
                    <img src="./assets/img/icons/flags/US.png" alt="Country flag">
                  </div>
                  <div class="ms-4">
                    <p class="text-xs font-weight-bold mb-0">Country:</p>
                    <h6 class="text-sm mb-0">United States</h6>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Sales:</p>
                  <h6 class="text-sm mb-0">2500</h6>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Value:</p>
                  <h6 class="text-sm mb-0">$230,900</h6>
                </div>
              </td>
              <td class="align-middle text-sm">
                <div class="col text-center">
                  <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                  <h6 class="text-sm mb-0">29.9%</h6>
                </div>
              </td>
            </tr>
            <tr>
              <td class="w-30">
                <div class="d-flex px-2 py-1 align-items-center">
                  <div>
                    <img src="./assets/img/icons/flags/DE.png" alt="Country flag">
                  </div>
                  <div class="ms-4">
                    <p class="text-xs font-weight-bold mb-0">Country:</p>
                    <h6 class="text-sm mb-0">Germany</h6>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Sales:</p>
                  <h6 class="text-sm mb-0">3.900</h6>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Value:</p>
                  <h6 class="text-sm mb-0">$440,000</h6>
                </div>
              </td>
              <td class="align-middle text-sm">
                <div class="col text-center">
                  <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                  <h6 class="text-sm mb-0">40.22%</h6>
                </div>
              </td>
            </tr>
            <tr>
              <td class="w-30">
                <div class="d-flex px-2 py-1 align-items-center">
                  <div>
                    <img src="./assets/img/icons/flags/GB.png" alt="Country flag">
                  </div>
                  <div class="ms-4">
                    <p class="text-xs font-weight-bold mb-0">Country:</p>
                    <h6 class="text-sm mb-0">Great Britain</h6>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Sales:</p>
                  <h6 class="text-sm mb-0">1.400</h6>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Value:</p>
                  <h6 class="text-sm mb-0">$190,700</h6>
                </div>
              </td>
              <td class="align-middle text-sm">
                <div class="col text-center">
                  <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                  <h6 class="text-sm mb-0">23.44%</h6>
                </div>
              </td>
            </tr>
            <tr>
              <td class="w-30">
                <div class="d-flex px-2 py-1 align-items-center">
                  <div>
                    <img src="./assets/img/icons/flags/BR.png" alt="Country flag">
                  </div>
                  <div class="ms-4">
                    <p class="text-xs font-weight-bold mb-0">Country:</p>
                    <h6 class="text-sm mb-0">Brasil</h6>
                  </div>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Sales:</p>
                  <h6 class="text-sm mb-0">562</h6>
                </div>
              </td>
              <td>
                <div class="text-center">
                  <p class="text-xs font-weight-bold mb-0">Value:</p>
                  <h6 class="text-sm mb-0">$143,960</h6>
                </div>
              </td>
              <td class="align-middle text-sm">
                <div class="col text-center">
                  <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                  <h6 class="text-sm mb-0">32.14%</h6>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">Categories</h6>
      </div>
      <div class="card-body p-3">
        <ul class="list-group">
          <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                <i class="ni ni-mobile-button text-white opacity-10"></i>
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-1 text-dark text-sm">Devices</h6>
                <span class="text-xs">250 in stock, <span class="font-weight-bold">346+ sold</span></span>
              </div>
            </div>
            <div class="d-flex">
              <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
            </div>
          </li>
          <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                <i class="ni ni-tag text-white opacity-10"></i>
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                <span class="text-xs">123 closed, <span class="font-weight-bold">15 open</span></span>
              </div>
            </div>
            <div class="d-flex">
              <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
            </div>
          </li>
          <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                <i class="ni ni-box-2 text-white opacity-10"></i>
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                <span class="text-xs">1 is active, <span class="font-weight-bold">40 closed</span></span>
              </div>
            </div>
            <div class="d-flex">
              <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
            </div>
          </li>
          <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
            <div class="d-flex align-items-center">
              <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                <i class="ni ni-satisfied text-white opacity-10"></i>
              </div>
              <div class="d-flex flex-column">
                <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                <span class="text-xs font-weight-bold">+ 430</span>
              </div>
            </div>
            <div class="d-flex">
              <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div> -->

@endsection
