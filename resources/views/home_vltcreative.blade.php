@extends('layout.main2')

@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ $volunteer->nama }}</span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $volunteer->nama }}</h6>
            <span>Divisi {{ $volunteer->nama_divisi }}</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_vltCreative">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logoutVol">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->
@endsection

@section('content')
<br>
<div id="content">
    <div class="container-fluid">

       {{-- Kartu Ringkasan --}}
<div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
    <div class="col">
        <div class="card shadow-sm rounded-4 border-0 h-100">
            <div class="card-body">
                <h6 class="card-title mb-2 text-secondary">Total Tugas</h6>
                <p class="card-text fs-3 fw-semibold text-dark mb-0">{{ $totalTask ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow-sm rounded-4 border-0 h-100">
            <div class="card-body">
                <h6 class="card-title mb-2 text-secondary">Tugas Belum Dikerjakan</h6>
                <p class="card-text fs-3 fw-semibold text-dark mb-0">{{ $totalBelum ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Alert Deadline Tinggal 2 Hari --}}
@php
$tugas2HariLagi = $tasks->filter(function ($t) {
    return $t->showAlert;
});
@endphp


@if ($tugas2HariLagi->count())
    <div class="alert alert-danger shadow-sm rounded-3">
        <strong>⚠️ Tugas dengan deadline tinggal 2 hari lagi:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($tugas2HariLagi as $t)
                <li><strong>{{ $t->desk_tgs }}</strong> — sisa 2 hari</li>
            @endforeach
        </ul>
    </div>
@endif



        {{-- Tabel Tugas --}}
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Deskripsi Tugas</th>
                                <th>Deadline</th>
                                <th>Status Tugas</th>
                                <th>Status Validasi Koordinator</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                            
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $task->desk_tgs }}</td>
                                <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                                <td>
                                    @if ($task->pivot->status === 'Tugas Selesai')
                                        <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill">Tugas Selesai</span>
                                    @elseif ($task->pivot->status === 'Sedang Dikerjakan')
                                        <span class="badge bg-warning-subtle text-warning fw-semibold px-3 py-2 rounded-pill">Sedang Dikerjakan</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2 rounded-pill">Belum Dikerjakan</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($task->pivot->status_validasi === 'Selesai')
                                        <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2 rounded-pill">Selesai</span>
                                    @elseif ($task->pivot->status === 'Revisi')
                                        <span class="badge bg-warning-subtle text-warning fw-semibold px-3 py-2 rounded-pill">Revisi</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2 rounded-pill">Pending</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="d-flex justify-content-center flex-wrap gap-2">
                                        {{-- Tombol Modal Peran --}}
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#peranModal{{ $task->tugas_id }}">
                                            <i class="bi bi-people-fill me-1"></i> Isi Peran & Lihat Tim
                                        </button>

                                     {{-- Tombol Kerjakan --}}
<form action="{{ route('updateTaskStatus', ['tugas_id' => $task->tugas_id, 'status' => 'Sedang Dikerjakan']) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-info btn-sm"
        {{ ($task->status === 'Sedang Dikerjakan' || $task->status === 'Tugas Selesai' || !$task->pivot->peran || $task->isDeadlinePassed ? 'disabled' : '' )}}
        <i class="bi bi-play-fill me-1"></i> Kerjakan
    </button>
</form>

{{-- Tombol Selesai --}}
<button type="button" class="btn btn-primary btn-sm"
    {{ ($task->status === 'Tugas Selesai' || !$task->pivot->peran || $task->isDeadlinePassed ? 'disabled' : '' )}} 
    data-bs-toggle="modal" data-bs-target="#uploadModal">
    <i class="bi bi-check2-circle me-1"></i> Selesai
</button>

                                        
                                        
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Isi Peran & Lihat Tim --}}
                            <div class="modal fade" id="peranModal{{ $task->tugas_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form action="{{ route('simpan.peran', ['tugas_id' => $task->tugas_id]) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Isi Peran & Lihat Tim</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- Daftar Tim --}}
                                                <strong class="d-block mb-2">Tim Saat Ini:</strong>
                                                <ul class="list-group mb-3">
                                                    @foreach ($task->volunteers as $v)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $v->nama }}
                                                        <span class="badge bg-secondary">{{ $v->pivot->peran ?? 'Belum isi' }}</span>
                                                    </li>
                                                    @endforeach
                                                </ul>

                                                {{-- Input Peran --}}
                                                <div class="mb-3">
                                                    <label for="peran" class="form-label">Peran Kamu</label>
                                                    <input type="text" name="peran" id="peran" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary btn-sm">Simpan Peran</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




               <!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Unggah Hasil Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Link Google Drive yang sudah diinputkan oleh koordinator -->
                <div>
                    <strong>Link Google Drive untuk Unggahan:</strong>
                    <a href="{{ $task->link_gdrive }}" target="_blank" class="btn btn-link">
                        Klik di sini untuk membuka Google Drive
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <!-- Tombol Selesai yang memicu update status tugas -->
                <form action="{{ route('updateTaskStatus', ['tugas_id' => $task->tugas_id, 'status' => 'Tugas Selesai']) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Selesai</button>
                </form>
            </div>
        </div>
    </div>
</div>


                            @empty
                            <tr>
                                <td colspan="5" class="text-muted">Belum ada tugas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
