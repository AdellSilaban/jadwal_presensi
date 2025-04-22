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
                <form action="/simpan_task" method="POST">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" value="{{ $divisi->nama_divisi }}" readonly>
                        <label for="nama_divisi">Divisi</label>
                        <input type="hidden" name="divisi_id" value="{{ $divisi->divisi_id }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="desk_tgs" class="form-label">Deskripsi Tugas</label>
                        <textarea class="form-control" id="desk_tgs" name="desk_tgs" placeholder="Masukkan Deskripsi Tugas" rows="4"></textarea>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="deadline" name="deadline">
                        <label for="deadline">Deadline</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Petugas</label>
                        <div>
                            @foreach ($voldiv as $ptgs)
                                @if ($ptgs->status == 'Aktif')
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="petugas_{{ $ptgs->vol_id }}" name="petugas[]" value="{{ $ptgs->vol_id }}">
                                        <label class="form-check-label" for="petugas_{{ $ptgs->vol_id }}">{{ $ptgs->nama }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/home_koor" class="btn btn-secondary">Kembali</a>
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
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->nama }}</span>
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