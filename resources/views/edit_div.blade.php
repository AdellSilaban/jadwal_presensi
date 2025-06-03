@extends('layout.main')

@section('sidebar')

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Dashboard</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="dasboard"><i class="fas fa-users"></i>
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
<br>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4 text-center">Edit Data Divisi</h5>
                <form action="/updateDiv/{{ $divisi->divisi_id }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" 
                               value="{{ $divisi->nama_divisi }}" placeholder="Masukkan nama divisi">
                        <label for="nama_divisi">Nama Divisi</label>
                    </div>

                    <div id="poin-container">
                        <label class="form-label">Deskripsi Divisi (per poin):</label>
              @foreach ($divisi->desk_div as $desk)
    <div class="input-group mb-2">
        <span class="input-group-text">•</span>
        <input type="text" name="deskripsi[]" class="form-control" value="{{ $desk->deskripsi }}">
        <input type="hidden" name="deskripsi_id[]" value="{{ $desk->deskripsi_id }}">
    </div>
@endforeach



                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="tambahPoin()">+ Tambah Poin</button>
                    

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/div_kepalaPKK" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function tambahPoin() {
    const container = document.getElementById('poin-container');
    const inputGroup = document.createElement('div');
    inputGroup.className = 'input-group mb-2';
    inputGroup.innerHTML = `
        <span class="input-group-text">•</span>
        <input type="text" name="deskripsi[]" class="form-control" placeholder="Tulis poin...">
        <input type="hidden" name="deskripsi_id[]" value="">
    `;
    container.appendChild(inputGroup);
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



