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
<br>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4 text-center">Edit Jadwal Volunteer</h5>

                @if ($jadwal)
                <form action="{{ route('updateJadwal', $jadwal->jadwal_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" value="{{ $divisi->nama_divisi }}" readonly>
                        <label for="nama_divisi">Divisi</label>
                        <input type="hidden" name="divisi_id" value="{{ $divisi->divisi_id }}">
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="tgl_jadwal" name="tgl_jadwal" value="{{ $jadwal->tgl_jadwal }}">
                        <label for="tgl_jadwal">Tanggal</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="agenda" name="agenda" value="{{ $jadwal->agenda }}">
                        <label for="agenda">Agenda</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="time" class="form-control" id="jam_buka" name="jam_buka" value="{{ $jadwal->jam_buka }}">
                        <label for="jam_buka">Jam Buka Presensi</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="time" class="form-control" id="jam_tutup" name="jam_tutup" value="{{ $jadwal->jam_tutup }}">
                        <label for="jam_tutup">Jam Tutup Presensi</label>
                    </div>

                    {{-- <div class="mb-3">
                        <label class="mb-2 d-block">Petugas</label>
                        @foreach ($jadwal->volunteers as $volunteer)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="petugas_{{ $volunteer->vol_id }}" name="petugas[]" value="{{ $volunteer->vol_id }}"
                                    @if (in_array($volunteer->vol_id, $selectedVolunteers)) checked @endif>
                                <label class="form-check-label" for="petugas_{{ $volunteer->vol_id }}">{{ $volunteer->nama }}</label>
                            </div>
                        @endforeach
                    </div> --}}

                    <div class="mb-3">
                        <label class="mb-2 d-block">Petugas</label>
                        @foreach ($allVolunteers as $volunteer)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       id="petugas_{{ $volunteer->vol_id }}" 
                                       name="petugas[]" 
                                       value="{{ $volunteer->vol_id }}"
                                       @if (in_array($volunteer->vol_id, $selectedVolunteers)) checked @endif>
                                <label class="form-check-label" for="petugas_{{ $volunteer->vol_id }}">{{ $volunteer->nama }}</label>
                            </div>
                        @endforeach
                    </div>
                    

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/jadwal_vlt" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @else
                    <p class="text-center">Data jadwal tidak ditemukan.</p>
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
