@extends('layout.main2')

@section('topbar')
  <li class="nav-item d-flex align-items-center gap-2">
    <a href="/home_vltcreative" class="btn btn-sm text-dark border rounded-pill shadow-sm px-3 d-flex align-items-center gap-1">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
          <li class="nav-item dropdown pe-3">
    
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
              <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
              <span class="d-none d-md-block dropdown-toggle ps-2">{{ $volunteer->nama }}</span>
            </a><!-- End Profile Iamge Icon -->
    
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header">
                <h6>{{ $volunteer->nama }}</h6>
                <span>Divisi {{ $volunteer->nama_divisi }}</span>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
    
              <li>
                <a class="dropdown-item d-flex align-items-center" href="/profile_vltCreative">
                  <i class="bi bi-person"></i>
                  <span>Profile</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
    
              <li>
                <a class="dropdown-item d-flex align-items-center" href="/logoutVol">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Logout</span>
                </a>
              </li>
    
            </ul><!-- End Profile Dropdown Items -->
          </li><!-- End Profile Nav -->
    
        </ul>
      </nav><!-- End Icons Navigation -->
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm rounded-4 border-0 animate__animated animate__fadeInUp">
          <div class="card-body">
            <h4 class="mb-4 fw-semibold text-primary text-center">Profil Volunteer</h4>
            <dl class="row mb-0 small">
              <dt class="col-sm-5 text-muted">Nama</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->nama }}</dd>
  
              <dt class="col-sm-5 text-muted">NIM</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->nim }}</dd>
  
              <dt class="col-sm-5 text-muted">Fakultas</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->fakultas }}</dd>
  
              <dt class="col-sm-5 text-muted">Jurusan</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->jurusan }}</dd>
  
              <dt class="col-sm-5 text-muted">Status</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->status }}</dd>
  
              <dt class="col-sm-5 text-muted">Tanggal Aktif</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->mulai_aktif_formatted }} - {{ $volunteer->akhir_aktif_formatted }}</dd>
  
              <dt class="col-sm-5 text-muted">Email</dt>
              <dd class="col-sm-7 text-dark">{{ $volunteer->email }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection  