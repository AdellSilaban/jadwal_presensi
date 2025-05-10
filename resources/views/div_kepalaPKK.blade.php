@extends('layout.main')

@section('sidebar')
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
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Data Divisi</h1>
        <p style="font-size: 0.9rem;">Daftar divisi yang tersedia.</p>
        <br>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <!-- Search Input -->
            <div class="flex-grow-1 me-3">
                <input type="text"
                       class="form-control shadow-sm"
                       style="max-width: 260px; border-radius: 0.65rem; padding: 0.4rem 0.9rem; font-size: 0.9rem;"
                       placeholder="Cari Nama Divisi..."
                       id="searchInput"
                       onkeyup="cariData()" />
            </div>

            <!-- Tambah Divisi -->
            <a href="/tambah_div" class="btn btn-primary shadow-sm px-3 py-1 rounded-pill d-flex align-items-center gap-2"
               style="font-size: 0.9rem;">
                <i class="bi bi-person-plus"></i> Tambah Data Divisi
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6;">
                    <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Divisi</th>
                            <th class="text-center">Deskripsi Divisi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisi as $div)
                            <tr style="background-color: #f9f9f9;">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $div->nama_divisi }}</td>
                                <td class="text-center">{{ $div->desk_divisi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('edit_div', $div->divisi_id) }}" class="btn btn-success btn-sm shadow">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a> 
                                    <a href="{{ route('hapus_div', $div->divisi_id) }}" class="btn btn-danger btn-sm" 
                                       onclick="confirmDelete(event, {{ $div->divisi_id }})">
                                        <i class="bi bi-trash"></i> Hapus
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
    function confirmDelete(event, divisi_id) {
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
                form.action = "{{ route('hapus_div', '') }}/" + divisi_id;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function cariData() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const rows = document.querySelectorAll("tbody tr");

        rows.forEach(row => {
            const namaDiv = row.children[1].textContent.toUpperCase();
            row.style.display = namaDiv.includes(filter) ? "" : "none";
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

