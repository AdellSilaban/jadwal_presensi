@extends('layout.main')
@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="home_koorcrv"><i class="fas fa-fw fa-home"></i>
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
<div class="row">  {{-- Tambahkan row untuk grid system --}}
  <div class="col-md-6 mx-auto"> {{-- Tambahkan col-md-8 dan mx-auto untuk center --}}
      <div class="form-group">
        <div class="modal-body">
            <h5 class="modal-title" id="editVltLabel">Tambah Data Volunteer</h5>
            <br>
        <form action="/simpanVltcrv" method="POST">
            @csrf
            <div class="form-group"> 
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama"  placeholder="Masukkan nama">
          </div>

          <div class="form-group">
              <label for="nim">NIM</label>
              <input type="text" class="form-control" id="nim" name="nim"  placeholder="Masukkan NIM">
            </div>

            <div class="form-group">
              <label for="fakultas">Fakultas</label>
              <input type="text" class="form-control" id="fakultas" name="fakultas"  placeholder="Masukkan Fakultas">
            </div>

            <div class="form-group">
              <label for="jurusan">Jurusan</label>
              <input type="text" class="form-control" id="jurusan" name="jurusan"  placeholder="Masukkan Jurusan">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email"  placeholder="Masukkan Email">
            </div>

            <div class="form-group">
              <label for="periode">Periode</label>
              <input type="text" class="form-control" id="periode" name="periode"  placeholder="Masukkan Periode">
            </div>

            <div class="form-group">
              <label for="divisi">Divisi</label>
              <select name="divisi_id" id="divisi_id" class="form-control">
                @foreach ($divisi as $div)  {{-- Pastikan nama variabelnya $divisi --}}
                     <option value="{{ $div->divisi_id }}">{{ $div->nama_divisi }}</option>
                @endforeach
            </select>
          </div> 

            <div class="form-group">
              <label for="pass_vlt">Password</label>
              <input type="text" class="form-control" id="pass_vlt" name="pass_vlt"  placeholder="Masukkan password">
            </div>

          <div class="modal-footer">
      
        <button type="submit" class="btn btn-primary">Simpan</button> 
    </div>
  </form>
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
