@extends('layout.main')

@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="home_koor"><i class="fas fa-fw fa-home"></i>
        <span>Home</span>
    </a>
</li>

@auth
    @if (Auth::user()->jabatan === 'Koordinator Divisi Creative')
        <li class="nav-item">
            <a class="nav-link collapsed" href="task_mn"><i class="fas fa-list"></i>
                <span>Task Management</span>
            </a>
        </li>
    @endif
@endauth

<li class="nav-item">
    <a class="nav-link collapsed" href="validasi_task"><i class="fas fa-check"></i>
        <span>Validasi Task</span>
    </a>
</li>
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Manajemen Task</h1>
        <p style="font-size: 0.9rem;">Daftar tugas yang dikelola oleh koordinator divisi.</p>
        <br>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <!-- Input Pencarian -->
            <div class="flex-grow-1 me-3">
                <input type="text"
                       class="form-control shadow-sm"
                       style="max-width: 300px; border-radius: 0.65rem; padding: 0.4rem 0.9rem; font-size: 0.9rem;"
                       placeholder="Cari Deskripsi atau Petugas..."
                       id="searchInput"
                       onkeyup="cariData()" />
            </div>

            <!-- Tombol Tambah Task -->
            <a href="/tambah_task" class="btn btn-primary shadow-sm px-3 py-1 rounded-pill d-flex align-items-center gap-2"
               style="font-size: 0.9rem;">
                <i class="fas fa-plus fa-sm"></i> Tambah Task
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow rounded"
                       style="border-color: #dee2e6;">
                    <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">Deskripsi Tugas</th>
                            <th style="text-align: center;">Deadline</th>
                            <th style="text-align: center;">Petugas</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tugas as $task)
                        <tr style="background-color: #f9f9f9;">
                            <th style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</th>
                            <td style="vertical-align: middle;">{{ $task->desk_tgs }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $task->deadline }}</td>
                            <td style="vertical-align: middle;">
                                @foreach ($task->volunteers as $volunteer)
                                    {{ $volunteer->nama }}<br>
                                @endforeach
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <a href="{{ route('edit_task', $task->tugas_id) }}" class="btn btn-success btn-sm shadow">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <a href="{{ route('hapus_task', $task->tugas_id) }}" class="btn btn-danger btn-sm" onclick="confirmDelete(event, {{ $task->tugas_id }})">
                                    <i class="bi-trash"></i> Hapus
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
    function confirmDelete(event, tugas_id) {
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
                form.action = "{{ route('hapus_task', '') }}/" + tugas_id;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function cariData() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.querySelector(".table");
        const tbody = table.querySelector("tbody");
        const rows = tbody.querySelectorAll("tr");

        rows.forEach(row => {
            const deskripsi = row.cells[1]?.textContent.toUpperCase() || '';
            const petugas = row.cells[3]?.textContent.toUpperCase() || '';
            const found = deskripsi.includes(filter) || petugas.includes(filter);
            row.style.display = found ? "" : "none";
        });
    }
</script>
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
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>
</ul>
@endsection
