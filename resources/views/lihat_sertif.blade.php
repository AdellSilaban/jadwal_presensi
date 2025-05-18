@extends('layout.main2')

@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ $volunteer->nama }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $volunteer->nama }}</h6>
            <span>Divisi {{ $volunteer->nama_divisi }}</span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_vlt">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('lihat_sertif') }}">
              <i class="bi bi-file-earmark-text"></i>
              <span class="ms-2">Sertifikatku</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logoutVol">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="container">
  <div class="text-center mb-4">
    <h4 class="fw-semibold text-dark">Sertifikat</h4>
    <p class="text-muted small">Berikut adalah sertifikat yang telah kamu peroleh selama menjadi volunteer.</p>
  </div>

  @if ($sertifikat)
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body text-center">
            <h5 class="fw-bold text-dark mb-2">Sertifikat Periode {{ $sertifikat->periode_ke }}</h5>
            <p class="text-muted mb-3">Terima kasih telah melayani dalam periode ini.</p>

            <a href="{{ asset('storage/' . $sertifikat->file_sertifikat) }}" target="_blank" class="btn btn-outline-primary rounded-pill">
                <i class="bi bi-box-arrow-up-right"></i> Lihat / Unduh Sertifikat
            </a>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm rounded-4 border-0 bg-light text-center p-4">
          <h6 class="text-muted mb-0">Belum ada sertifikat yang tersedia untukmu saat ini.</h6>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
