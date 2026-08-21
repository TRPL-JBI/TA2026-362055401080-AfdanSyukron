<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route('dashboard2') }}" style="margin-left: -5px !important;">
        <img src="https://poliwangi.ac.id/wp-content/uploads/2021/02/logo-poliwangi.png" class="navbar-brand-img h-100 me-0" alt="logo_poliwangi" style="transform: scale(1.1);">
        <span class="font-weight-bold" style="font-size: 1.15rem; margin-left: 15px;">SIPMAS</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <!-- <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'dashboard2' ? 'active' : '' }} " href="{{ route('dashboard2') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-tachometer-alt text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Beranda</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'pengajuan' ? 'active' : '' }} " href="{{ route('pengajuan') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-boxes text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Daftar Alat</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'pengajuan.list' ? 'active' : '' }} " href="{{ route('pengajuan.list') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file-invoice text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Daftar Pengajuan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'pengajuan.history' ? 'active' : '' }} " href="{{ route('pengajuan.history') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-history text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Riwayat Pengajuan</span>
          </a>
        </li>

        @if(in_array(strtolower(auth()->user()->role->role ?? ''), ['staff admin', 'staff humas', 'admin']))
        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'mahasiswa' ? 'active' : '' }} " href="{{ route('mahasiswa') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user-graduate text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Mahasiswa</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'jurusan' ? 'active' : '' }} " href="{{ route('jurusan') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-university text-secondary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Jurusan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'prodi' ? 'active' : '' }} " href="{{ route('prodi') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-book-open text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Prodi</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'ormawa' ? 'active' : '' }} " href="{{ route('ormawa') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-flag text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Ormawa</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'alat' ? 'active' : '' }} " href="{{ route('alat') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-tools text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Alat</span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'pengajuan' ? 'active' : '' }} " href="{{ route('pengajuan') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Pengajuan Alat</span>
          </a>
        </li> -->

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'role' ? 'active' : '' }} " href="{{ route('role') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user-shield text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Peran</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ \Request::route()->getName() == 'user' ? 'active' : '' }} " href="{{ route('user') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-users-cog text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Pengguna</span>
          </a>
        </li>
        @endif
        <!-- <li class="nav-item">
          <a class="nav-link " href="./pages/tables.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/billing.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Billing</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/virtual-reality.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Virtual Reality</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/rtl.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">RTL</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/profile.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/sign-in.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/sign-up.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li> -->
      </ul>
    </div>
  </aside>