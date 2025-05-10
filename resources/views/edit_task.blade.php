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
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4" style="text-align: center;">Edit Data Task</h5>
                <br>
                
                @if(isset($tugas))
                <form action="{{ route('updateTask', $tugas->tugas_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Tugas -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="desk_tgs" name="desk_tgs" value="{{ $tugas->desk_tgs }}" required>
                        <label for="desk_tgs">Deskripsi Tugas</label>
                    </div>

                    <!-- Deadline Tugas -->
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="deadline" name="deadline" value="{{ $tugas->deadline }}" required>
                        <label for="deadline">Deadline</label>
                    </div>

<!-- Pilih Divisi -->
<div class="mb-4">
  <label for="divisi_id" class="form-label fw-semibold">Divisi</label>
  <select class="form-select" id="divisi_id" name="divisi_id" disabled>
      <option value="{{ $divisi->divisi_id }}">{{ $divisi->nama_divisi }}</option>
  </select>
  <input type="hidden" name="divisi_id" value="{{ $divisi->divisi_id }}"> {{-- agar tetap dikirim --}}
</div>

<!-- Pilih Volunteer -->
<div class="mb-4">
  <label class="form-label fw-semibold">Volunteer Yang Bertugas</label>
  
  <div class="form-check-container d-flex flex-column gap-2">
      @foreach($allVolunteers as $vlt)
          <div class="form-check">
              <input class="form-check-input" type="checkbox" name="volunteers[]" 
                     value="{{ $vlt->vol_id }}" id="vlt{{ $vlt->vol_id }}"
                     {{ in_array($vlt->vol_id, $selectedVolunteers) ? 'checked' : '' }}>
              <label class="form-check-label" for="vlt{{ $vlt->vol_id }}">
                  {{ $vlt->nama }} - {{ $vlt->status }}
              </label>
          </div>
      @endforeach
  </div>
</div>




                    <div class="d-flex justify-content-end gap-2">
                        <a href="/task_mn" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @else
                    <p>Data tugas tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="me-2 fw-semibold text-dark">{{ $user->nama }}</span>
            <i class="bi bi-person-circle fs-4 text-primary"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $user->nama }}</h6>
            <span>{{ $user->jabatan }}</span>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_koor">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/ubah_pass">
              <i class="bi bi-key"></i>
              <span>Reset Password</span>
            </a>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logout">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->

      </li><!-- End Profile Nav -->
    </ul>
</nav>
@endsection

