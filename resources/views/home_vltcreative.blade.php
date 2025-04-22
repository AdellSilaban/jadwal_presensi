@extends('layout.main2')

@section('topbar')
<ul class="navbar-nav ml-auto align-items-center">
    <li class="nav-item me-2">
        <a href="/profile" class="btn btn-outline-primary btn-sm shadow-sm">
            <i class="fas fa-user me-1"></i> Profile
        </a>
    </li>
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $volunteer->nama }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
             aria-labelledby="userDropdown">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-600"></i> Logout
            </a>
        </div>
    </li>
</ul>
@endsection

@section('content')
<br>
<div id="content">
    <div class="container-fluid">

        {{-- Kartu Ringkasan --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-2 text-secondary">Total Tugas</h5>
                        <p class="card-text fs-3 fw-semibold text-dark">{{ $totalTask ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-2 text-secondary">Tugas Belum Dikerjakan</h5>
                        <p class="card-text fs-3 fw-semibold text-dark">{{ $totalBelum ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-2 text-secondary">Tugas Selesai</h5>
                        <p class="card-text fs-3 fw-semibold text-dark">{{ $totalSelesai ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

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
                                    @elseif ($task->status === 'Sedang Dikerjakan')
                                        <span class="badge bg-warning-subtle text-warning fw-semibold px-3 py-2 rounded-pill">Sedang Dikerjakan</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2 rounded-pill">Belum Dikerjakan</span>
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
                                                {{ ($task->status === 'Sedang Dikerjakan' || $task->status === 'Tugas Selesai' || !$task->pivot->peran) ? 'disabled' : '' }}>
                                                <i class="bi bi-play-fill me-1"></i> Kerjakan
                                            </button>
                                        </form>

                                        {{-- Tombol Selesai --}}
                                        <form action="{{ route('updateTaskStatus', ['tugas_id' => $task->tugas_id, 'status' => 'Tugas Selesai']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                {{ ($task->status === 'Tugas Selesai' || !$task->pivot->peran) ? 'disabled' : '' }}>
                                                <i class="bi bi-check2-circle me-1"></i> Selesai
                                            </button>
                                        </form>
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
