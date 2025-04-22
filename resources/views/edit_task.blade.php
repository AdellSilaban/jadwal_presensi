@extends('layout.main')
@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="home_koor"><i class="fas fa-fw fa-home"></i>
        <span>Home</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="task_mn"><i class="fas fa-list"></i>
        <span>Task Management</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="validasi_task"><i class="fas fa-check"></i>
        <span>Validasi Task</span>
    </a>
</li>
@endsection


@section('content')
<br>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4" style="text-align: center;">Edit Data Volunteer</h5>
                <br>
                <form action="{{ route('updateVlt', $volunteer->vol_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $volunteer->nama }}" required>
                        <label for="nama">Nama</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nim" name="nim" value="{{ $volunteer->nim }}" required>
                        <label for="nim">NIM</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fakultas" name="fakultas" value="{{ $volunteer->fakultas }}" required>
                        <label for="fakultas">Fakultas</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="jurusan" name="jurusan" value="{{ $volunteer->jurusan }}" required>
                        <label for="jurusan">Jurusan</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="periode" name="periode" value="{{ $volunteer->periode }}" required>
                        <label for="periode">Periode</label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/home_koor" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @else
                    <p>Data volunteer tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('topbar')

<ul class="navbar-nav ml-auto"> 
  <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
         data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" 
           aria-labelledby="userDropdown">
          {{-- <a class="dropdown-item" href="#">
              <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
              Profile
          </a> --}}
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/logout">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Logout
          </a>
      </div>
  </li>
</ul> 

@endsection