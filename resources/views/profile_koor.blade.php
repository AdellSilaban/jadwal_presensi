@extends('layout.main')

@section('sidebar')
<ul class="sidebar-nav" id="sidebar-nav">
    @auth
        @php $jabatan = Auth::user()->jabatan; @endphp

        <li class="nav-heading">Manajemen Volunteer</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="home_koor">
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="sub_divisi">
                <i class="bi bi-calendar-event"></i>
                <span>Sub Divisi</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="jadwal_vlt">
                <i class="bi bi-calendar-event"></i>
                <span>Jadwal Volunteer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="data_presensi">
                <i class="bi bi-database"></i>
                <span>Data Presensi</span>
            </a>
        </li>

        {{-- Tambahkan menu ini untuk semua koordinator --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('formuploadSertif') }}">
                <i class="bi bi-upload"></i>
                <span>Upload Sertifikat</span>
            </a>
        </li>

        @if ($jabatan === 'Koordinator Divisi Creative')
            <li class="nav-heading">Manajemen Tugas</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="task_mn">
                    <i class="bi bi-list-task"></i>
                    <span>Manajemen Tugas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="validasi_task">
                    <i class="bi bi-check-circle"></i>
                    <span>Validasi Tugas</span>
                </a>
            </li>
        @elseif ($jabatan === 'Koordinator Divisi Konseling')
            <li class="nav-heading">Manajemen Tugas</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="task_mn">
                    <i class="bi bi-list-task"></i>
                    <span>Manajemen Tugas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="validasi_task">
                    <i class="bi bi-check-circle"></i>
                    <span>Validasi Tugas</span>
                </a>
            </li>
        @endif
    @endauth
</ul>
@endsection


@section('content')
<div class="container py-5">
    <div class="card mx-auto shadow rounded-4 border-0" style="max-width: 550px;">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h5 class="fw-semibold">
                    <i class="bi bi-person-fill me-2 text-dark"></i> Profil Koordinator
                </h5>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-person-fill me-2"></i>Nama
                </div>
                <div class="col-8 text-dark" style="padding-left: 0.3rem;">
                    {{ Auth::user()->nama }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-briefcase-fill me-2"></i>Jabatan
                </div>
                <div class="col-8 text-dark" style="padding-left: 0.3rem;">
                    {{ Auth::user()->jabatan }}
                </div>
            </div>

            <div class="row mb-4 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-envelope-fill me-2"></i>Email
                </div>
                <div class="col-8 text-dark" style="padding-left: 0.3rem;">
                    {{ Auth::user()->email }}
                </div>
            </div>
<br>
            <div class="text-center">
                <a href="{{ url('home_koor') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
            </div>

        </div>
    </div>

@endsection






@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">


      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->nama }}</span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $user->nama }}</h6>
            <span>{{ $user->jabatan }}</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_koor">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logout">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->
@endsection