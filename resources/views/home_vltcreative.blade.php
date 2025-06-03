@extends('layout.main2')    

@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="me-2 fw-semibold text-dark">{{ $volunteer->nama }}</span>
            <i class="bi bi-person-circle fs-4 text-primary"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $volunteer->nama }}</h6>
            <span> Divisi {{ $volunteer->divisi->nama_divisi }}</span>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_vlt">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>

           <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/lihat_sertif">
              <i class="bi bi-person"></i>
              <span>Sertifikatku</span>
            </a>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logoutVol">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>
        </ul><!-- End Profile Dropdown Items -->

      </li><!-- End Profile Nav -->
    </ul>
</nav>
@endsection

@section('content')
@section('content')
<div class="container-fluid px-3 mt-3 mb-5">

    <div class="row mb-3">
      <div class="col-md-auto">
        <div class="card shadow-sm rounded-4 border-0" style="max-width: 400px;">
          <div class="card-body py-2 px-3">
            <strong class="text-dark small">Aturan Tugas</strong>
            <span class="text-muted small"> | Penting</span>
            <ul class="ps-3 mt-2 mb-0 small">
              <li class="text-danger">Perhatikan <strong>tanggal deadline</strong> setiap tugas.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

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

    <div class="card shadow-lg border-0 animate__animated animate__fadeIn">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-white">
                        <tr>
                            <th>No</th>
                            <th>Deskripsi Tugas</th>
                            <th>Deadline</th>
                            <th>Status Tugas</th>
                            <th>Status Validasi</th>
                            <th>Catatan Revisi</th>
                            <th style="min-width: 280px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $task->desk_tgs }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d-m-Y')}}</td>
                            <td>
                                @if ($task->pivot->status === 'Tugas Selesai')
                                    <span class="badge pastel-badge-blue px-3 py-2 shadow-sm">Tugas Selesai</span>
                                @elseif ($task->pivot->status === 'Sedang Dikerjakan')
                                    <span class="badge bg-warning-subtle text-warning fw-semibold px-2 py-1 rounded-pill">Sedang Dikerjakan</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold px-2 py-1 rounded-pill">Belum Dikerjakan</span>
                                @endif
                            </td>
                            <td>
                                @if ($task->pivot->status_validasi === 'Selesai')
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1 rounded-pill">Selesai</span>
                                @elseif ($task->pivot->status_validasi === 'Revisi')
                                    <span class="badge bg-warning-subtle text-warning fw-semibold px-2 py-1 rounded-pill">Revisi</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary fw-semibold px-2 py-1 rounded-pill">Pending</span>
                                @endif
                            </td>
                            <td class="text-start">{{ $task->pivot->revisi_catatan ?? '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center flex-nowrap gap-2">
                                    <button class="btn btn-warning btn-sm px-1 py-0 fw-normal hover-scale" data-bs-toggle="modal" data-bs-target="#peranModal{{ $task->tugas_id }}" {{ $task->pivot->status === 'Tugas Selesai' ? 'disabled' : '' }}>
                                        <i class="bi bi-people-fill me-1"></i> Isi peran
                                    </button>

                                    <form action="{{ route('updateTaskStatus', ['tugas_id' => $task->tugas_id, 'status' => 'Sedang Dikerjakan']) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm px-2 py-1 hover-scale"
                                            {{ ($task->pivot->status !== 'Belum Dikerjakan' || !$task->pivot->peran) ? 'disabled' : '' }}>
                                            <i class="bi bi-play-fill me-1"></i> Kerjakan
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-primary btn-sm px-1 py-0 fw-normal hover-scale"
                                        {{ (
                                            !$task->pivot->peran ||
                                            $task->isDeadlinePassed ||
                                            !(
                                                $task->pivot->status === 'Sedang Dikerjakan' &&
                                                (
                                                    $task->pivot->status_validasi === 'Revisi' ||
                                                    $task->pivot->status_validasi === 'Pending'
                                                )
                                            )
                                        ) ? 'disabled' : '' }}
                                        data-bs-toggle="modal" data-bs-target="#uploadModal{{ $task->tugas_id }}">
                                        <i class="bi bi-check2-circle me-1"></i> Selesai
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Isi Peran --}}
                        <div class="modal fade" id="peranModal{{ $task->tugas_id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <form action="{{ route('simpan.peran', ['tugas_id' => $task->tugas_id]) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Isi Peran & Lihat Tim</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <strong class="d-block mb-2">Tim Saat Ini:</strong>
                                            <ul class="list-group mb-3">
                                                @foreach ($task->volunteers as $v)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $v->nama }}
                                                    <span class="badge bg-secondary">{{ $v->pivot->peran ?? 'Belum isi' }}</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                            <div class="mb-3">
                                                <label for="peran" class="form-label">Peran Kamu</label>
                                                <input type="text" name="peran" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary btn-sm">Simpan Peran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Upload --}}
                        <div class="modal fade" id="uploadModal{{ $task->tugas_id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Unggah Hasil Pekerjaan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <strong>Link Google Drive untuk Unggahan:</strong>
                                        <a href="{{ $task->link_gdrive }}" target="_blank" class="btn btn-link">Klik di sini</a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
                            <td colspan="7" class="text-muted text-center">Belum ada tugas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection


