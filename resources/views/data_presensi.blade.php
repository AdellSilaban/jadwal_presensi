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
    <h1 class="h4 mb-3 text-gray-800">Data Presensi Volunteer</h1>
    <p class="mb-4 text-muted small">Pilih volunteer untuk melihat detail data presensinya.</p>

    <form action="{{ route('filterPresensi') }}" method="GET" id="filterForm">
        <div class="d-flex justify-content-end align-items-center">
            <select name="vol_id" class="form-select form-select-sm w-auto shadow-sm" id="volunteerSelect" onchange="this.form.submit()">
                <option value="">Pilih Volunteer</option>
                @foreach($volunteers as $vol)
                    <option value="{{ $vol->vol_id }}" {{ request('vol_id') == $vol->vol_id ? 'selected' : '' }}>
                        {{ $vol->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
    <br>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6;">
                <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                    <tr>
                        <th scope="col" style="text-align: center;">No</th>
                        <th scope="col"style="text-align: center;">Tanggal</th>
                        <th scope="col" style="text-align: center;">Nama</th>
                        <th scope="col" style="text-align: center;">Check In</th>
                        <th scope="col" style="text-align: center;">Check Out</th>
                        <th scope="col" style="text-align: center;">Deskripsi Tugas</th>
                        <th scope="col" style="text-align: center;">Total Jam</th>
                        <th scope="col" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($filterApplied && $presensi->count())
                        @php $no = 1; @endphp
                        @foreach ($presensi as $p)
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                <td style="text-align: center;">{{ $p->volunteer->nama }}</td>
                                <td style="text-align: center;">{{ $p->check_in }}</td>
                                <td style="text-align: center;">{{ $p->check_out }}</td>
                                <td style="text-align: center;">{{ $p->desk_tgs ?? '-' }}</td>
                                <td style="text-align: center;">
                                    @if ($p->check_in && $p->check_out)
                                        {{ \Carbon\Carbon::parse($p->check_in)->diff(\Carbon\Carbon::parse($p->check_out))->format('%H:%I:%S') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('edit_presensi', $p->presensi_id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center text-muted">Silakan pilih volunteer untuk melihat data presensinya.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-end fw-bold text-success" style="text-align: center;">
                            Total Jam Kerja Keseluruhan: {{ $totalHours }} Jam
                        </td>
                    </tr>
                </tfoot>
            </table>
            @if($filterApplied)
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('download.presensi', ['vol_id' => request('vol_id')]) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>
            </div>
            @endif
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


