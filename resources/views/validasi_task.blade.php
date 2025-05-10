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
<div class="container-fluid px-4 py-4">
    <h1 class="h4 mb-3 text-gray-800">Validasi Task Desain</h1>
    <p class="mb-4 text-muted small">Daftar semua volunteer yang mengerjakan tugas, baik yang statusnya <strong>Pending</strong>, <strong>Selesai</strong>, atau <strong>Revisi</strong>.</p>

    <!-- Filter -->
    <form action="{{ route('validasi_task') }}" method="GET">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <select name="desk_tgs" class="form-select form-select-sm w-auto shadow-sm" onchange="this.form.submit()">
                <option value="">Pilih Tugas</option>
                @foreach($desk_tgs as $deskripsi)
                    <option value="{{ $deskripsi->desk_tgs }}" {{ request('desk_tgs') == $deskripsi->desk_tgs ? 'selected' : '' }}>
                        {{ $deskripsi->desk_tgs }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6;">
                <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                    <tr>
                        <th>No</th>
                        <th style="text-align: center;">Nama Volunteer</th>
                        <th style="text-align: center;">Deskripsi Tugas</th>
                        <th style="text-align: center;">Deadline</th>
                        <th style="text-align: center;">Status Tugas</th>
                        <th style="text-align: center;">Status Validasi</th>
                        <th style="text-align: center;">Catatan</th>
                        <th style="width: 150px; text-align: center;">Validasi Tugas</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$filterApplied)
                        <tr>
                            <td colspan="8" class="text-muted text-center">Silakan pilih tugas untuk melihat data.</td>
                        </tr>
                    @elseif($tugasFiltered->isEmpty())
                        <tr>
                            <td colspan="8" class="text-muted">Tidak ada data tugas dengan status validasi.</td>
                        </tr>
                    @else
                        @php $no = 1; @endphp
                        @foreach($tugasFiltered as $task)
                            @foreach($task->volunteers as $vlt)
                                <tr>
                                    <td style="text-align: center;">{{ $no++ }}</td>
                                    <td style="text-align: center;">{{ $vlt->nama }}</td>
                                    <td style="text-align: center;">{{ $task->desk_tgs }}</td>
                                    <td style="text-align: center;">{{ $task->deadline }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ $vlt->pivot->status === 'Tugas Selesai' ? 'success' : 'warning' }}">
                                            {{ $vlt->pivot->status }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-{{ 
                                            $vlt->pivot->status_validasi === 'Pending' ? 'info' : 
                                            ($vlt->pivot->status_validasi === 'Selesai' ? 'success' : 'warning') 
                                        }}">
                                            {{ $vlt->pivot->status_validasi }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">{{ $vlt->pivot->revisi_catatan ?? '-' }}</td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2 flex-nowrap">
                                    
                                            <!-- Tombol Revisi -->
                                            <button type="button"
                                                    class="btn btn-sm btn-warning d-flex align-items-center gap-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalRevisi{{ $task->tugas_id }}_{{ $vlt->vol_id }}">
                                                <i class="bi bi-pencil-square"></i> Revisi
                                            </button>
                                    
                                            <!-- Tombol Selesai -->
                                            <form action="{{ route('validasi.task.submit') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="tugas_id" value="{{ $task->tugas_id }}">
                                                <input type="hidden" name="vol_id" value="{{ $vlt->vol_id }}">
                                                <input type="hidden" name="status_validasi" value="Selesai">
                                                <button type="submit" class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                                    <i class="bi bi-check-circle"></i> Selesai
                                                </button>
                                            </form>
                                    
                                        </div>
                                    </td>
                                    

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalRevisi{{ $task->tugas_id }}_{{ $vlt->vol_id }}" tabindex="-1" aria-labelledby="modalRevisiLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('validasi.task.submit') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalRevisiLabel">Revisi Tugas</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="tugas_id" value="{{ $task->tugas_id }}">
                                                            <input type="hidden" name="vol_id" value="{{ $vlt->vol_id }}">
                                                            <input type="hidden" name="status_validasi" value="Revisi">
                                                            <div class="mb-3">
                                                                <label for="revisi_catatan" class="form-label">Catatan Revisi</label>
                                                                <textarea name="revisi_catatan" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning">Simpan Revisi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>
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


