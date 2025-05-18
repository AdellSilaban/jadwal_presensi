@extends('layout.main2')    

@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="me-2 fw-semibold text-dark">{{ $volunteer->nama }}</span>
            <i class="bi bi-person-circle fs-4 text-primary"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $volunteer->nama }}</h6>
            <span> Divisi {{ $volunteer->divisi->nama_divisi }}</span>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_vlt">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logoutVol">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->

      </li><!-- End Profile Nav -->
    </ul>
</nav>
@endsection

@section('content')
<div class="container py-5">
    <div class="card mx-auto shadow rounded-4 border-0 animate__animated animate__fadeInUp" style="max-width: 550px;">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h5 class="fw-semibold">
                    <i class="bi bi-person-fill me-2 text-dark"></i> Profil Volunteer
                </h5>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-person-fill me-2"></i>Nama
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->nama }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-credit-card-fill me-2"></i>NIM
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->nim }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-building me-2"></i>Fakultas
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->fakultas }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-journal-bookmark-fill me-2"></i>Jurusan
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->jurusan }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-diagram-3-fill me-2"></i>Divisi
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->divisi->nama_divisi }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-info-circle-fill me-2"></i>Status
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->status }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-shield-check me-2"></i>Status Etik
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->status_etik }}
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-calendar-event-fill me-2"></i>Aktif
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->mulai_aktif_formatted }} - {{ $volunteer->akhir_aktif_formatted }}
                </div>
            </div>

            <div class="row mb-4 align-items-center">
                <div class="col-4 text-primary fw-semibold">
                    <i class="bi bi-envelope-fill me-2"></i>Email
                </div>
                <div class="col-8 text-dark">
                    {{ $volunteer->email }}
                </div>
            </div>

            <div class="text-center">
              @php
                  $namaDivisi = $volunteer->divisi->nama_divisi;
              @endphp
          
            <a href="{{ url($kembaliLink) }}" class="btn btn-outline-secondary rounded-pill px-4">
              <i class="bi bi-arrow-left-circle me-1"></i> Kembali
          </a>
        
          

        </div>
    </div>
</div>
@endsection
