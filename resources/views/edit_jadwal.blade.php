@extends('layout.main')

@section('sidebar')
<li class="nav-item">
  <a class="nav-link collapsed" href="home_koor"><i class="fas fa-fw fa-home"></i>
      <span>Home</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="jadwal_vlt"><i class="fas fa-calendar-alt"></i>
      <span>Jadwal Volunteer</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="validasi_presensi"><i class="fas fa-check"></i>
      <span>Validasi Presensi</span>
  </a>
</li>
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
                        <select name="divisi_id" id="divisi_id" class="form-select">
                            @foreach ($divisi as $div)
                                <option value="{{ $div->divisi_id }}" {{ $div->divisi_id == $jadwal->divisi_id ? 'selected' : '' }}>
                                    {{ $div->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                        <label for="divisi_id">Divisi</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="tgl_jadwal" name="tgl_jadwal" value="{{ $jadwal->tgl_jadwal }}">
                        <label for="tgl_jadwal">Tanggal</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="agenda" name="agenda" value="{{ $jadwal->agenda }}">
                        <label for="agenda">Agenda</label>
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
<ul class="navbar-nav ml-auto"> 
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->nama }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" 
             aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#">
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>
</ul> 
@endsection
