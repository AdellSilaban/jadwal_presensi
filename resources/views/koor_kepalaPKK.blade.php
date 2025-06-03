@extends('layout.main')

@section('sidebar')

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Dashboard</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="dashboard"><i class="fas fa-users"></i>
        <span>Dashboard</span>
    </a>
</li>

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Volunteer</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="home_kepalaPKK"><i class="fas fa-users"></i>
        <span>Data Volunteer</span>
    </a>
</li>

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Divisi</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="div_kepalaPKK"><i class="fas fa-layer-group"></i>
        <span>Data Divisi</span>
    </a>
</li>

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Koordinator Divisi</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="koor_kepalaPKK"><i class="fas fa-users"></i>
        <span>Data Koordinator</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-1 text-gray-800">Data Koordinator Divisi</h1>
    <p class="mb-4" style="font-size: 0.9rem;">Daftar koordinator dari semua divisi.</p>

    @if (session('flash'))
        <div class="alert alert-{{ session('flash_type') }}">{{ session('flash') }}</div>
    @endif

    <div class="card-body">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6;">
                <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Divisi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($koordinator as $koor)
                    <tr style="background-color: #f9f9f9;">
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="text-center align-middle">{{ $koor->nama }}</td>
                        <td class="text-center align-middle">{{ $koor->email }}</td>
                        <td class="text-center align-middle">{{ $koor->divisi->nama_divisi ?? '-' }}</td>
                        <td class="text-center align-middle">
                            <span class="badge bg-{{ $koor->status == 'Aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($koor->status) }}
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            @if ($koor->status === 'Aktif') {{-- lowercase, karena status di DB-nya pasti lowercase --}}
                                <form action="{{ route('nonaktifKoor', $koor->user_id) }}" method="POST" class="form-nonaktif-{{ $koor->id }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" class="btn btn-sm btn-danger btn-nonaktif" data-id="{{ $koor->id }}">
                                        <i class="bi bi-x-circle"></i> Nonaktifkan
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                    </tr>
                @endforeach

                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-nonaktif').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const form = document.querySelector('.form-nonaktif-' + id);

                Swal.fire({
                    title: 'Yakin ingin nonaktifkan koordinator ini?',
                    text: 'Aksi ini akan menonaktifkan akses akun tersebut.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, nonaktifkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>


            </tbody>
        </table>
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
