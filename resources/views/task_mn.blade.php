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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Manajemen Task</h1>
        <p style="font-size: 0.9rem;">Daftar tugas yang dikelola oleh koordinator divisi.</p>
        <br>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <!-- Input Pencarian -->
            <div class="flex-grow-1 me-3">
                <input type="text"
                       class="form-control shadow-sm"
                       style="max-width: 300px; border-radius: 0.65rem; padding: 0.4rem 0.9rem; font-size: 0.9rem;"
                       placeholder="Cari Deskripsi atau Petugas..."
                       id="searchInput"
                       onkeyup="cariData()" />
            </div>

            <!-- Tombol Tambah Task -->
            <a href="/tambah_task" class="btn btn-primary shadow-sm px-3 py-1 rounded-pill d-flex align-items-center gap-2"
               style="font-size: 0.9rem;">
                <i class="bi bi-person-plus"></i> Tambah Task
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow rounded"
                       style="border-color: #dee2e6;">
                    <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">Deskripsi Tugas</th>
                            <th style="text-align: center;">Deadline</th>
                            <th style="text-align: center;">Link Gdrive</th>
                            <th style="text-align: center;">Petugas</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tugas as $task)
                        <tr style="background-color: #f9f9f9;">
                            <th style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</th>
                            <td style="vertical-align: middle; vertical-align: middle;">{{ $task->desk_tgs }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $task->deadline }}</td>
                            <td>
                                <a href="{{ $task->link_gdrive }}" target="_blank" class="text-decoration-none">
                                    {{ Str::limit($task->link_gdrive, 40) }}  <!-- Menampilkan hanya sebagian link untuk kejelasan -->
                                </a>
                            </td>
                            <td style="vertical-align: middle;">
                                @foreach ($task->volunteers as $volunteer)
                                    {{ $volunteer->nama }}<br>
                                @endforeach
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="{{ route('edit_task', $task->tugas_id) }}" class="btn btn-success btn-sm shadow">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                <a href="{{ route('hapus_task', $task->tugas_id) }}" class="btn btn-danger btn-sm" onclick="confirmDelete(event, {{ $task->tugas_id }})">
                                    <i class="bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, tugas_id) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('hapus_task', '') }}/" + tugas_id;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function cariData() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.querySelector(".table");
        const tbody = table.querySelector("tbody");
        const rows = tbody.querySelectorAll("tr");

        rows.forEach(row => {
            const deskripsi = row.cells[1]?.textContent.toUpperCase() || '';
            const petugas = row.cells[3]?.textContent.toUpperCase() || '';
            const found = deskripsi.includes(filter) || petugas.includes(filter);
            row.style.display = found ? "" : "none";
        });
    }
</script>
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

