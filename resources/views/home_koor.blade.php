@extends('layout.main')

@section('sidebar')
<ul class="sidebar-nav" id="sidebar-nav">
    @auth
        @php $jabatan = Auth::user()->jabatan; @endphp

        <li class="nav-heading">Manajemen Volunteer</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="home_koor">
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="sub_divisi">
                <i class="bi bi-calendar-event"></i>
                <span>Sub Divisi</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="jadwal_vlt">
                <i class="bi bi-calendar-event"></i>
                <span>Jadwal Volunteer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="data_presensi">
                <i class="bi bi-database"></i>
                <span>Data Presensi</span>
            </a>
        </li>

        {{-- Tambahkan menu ini untuk semua koordinator --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('formuploadSertif') }}">
                <i class="bi bi-upload"></i>
                <span>Upload Sertifikat</span>
            </a>
        </li>

        @if ($jabatan === 'Koordinator Divisi Creative')
            <li class="nav-heading">Manajemen Tugas</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="task_mn">
                    <i class="bi bi-list-task"></i>
                    <span>Manajemen Tugas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="validasi_task">
                    <i class="bi bi-check-circle"></i>
                    <span>Validasi Tugas</span>
                </a>
            </li>
        @elseif ($jabatan === 'Koordinator Divisi Konseling')
            <li class="nav-heading">Manajemen Tugas</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="task_mn">
                    <i class="bi bi-list-task"></i>
                    <span>Manajemen Tugas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="validasi_task">
                    <i class="bi bi-check-circle"></i>
                    <span>Validasi Tugas</span>
                </a>
            </li>
        @endif
    @endauth
</ul>
@endsection




@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Manajemen Data Volunteer</h1>
        <p style="font-size: 0.9rem;">
            Daftar volunteer yang sudah bergabung.
        </p> 
        <br>
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <!-- Search Input -->
            <div class="flex-grow-1 me-3">
                <input type="text"
                       class="form-control shadow-sm"
                       style="max-width: 260px; border-radius: 0.65rem; padding: 0.4rem 0.9rem; font-size: 0.9rem;"
                       placeholder="Cari Nama, NIM, atau Email..."
                       id="searchInput"
                       onkeyup="cariData()" />
            </div>
            <!-- Tombol Tambah -->
            <a href="/tambah_vlt" class="btn btn-primary shadow-sm px-3 py-1 rounded-pill d-flex align-items-center gap-2"
               style="font-size: 0.9rem;">
              <i class="bi bi-person-plus"></i> Tambah Volunteer
            </a>
        </div>
    </div>
</div>



<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6;">
            <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                <tr>
                    <th scope="col" style="text-align: center;">No</th>
                    <th scope="col" style="text-align: center;">Nama</th>
                    <th scope="col" style="text-align: center;">NIM</th>
                    <th scope="col" style="text-align: center;">Email</th>
                    <th scope="col" style="text-align: center;">Periode</th>
                    <th scope="col" style="text-align: center;">Status</th>
                    <th scope="col" style="text-align: center;">Status Etik</th>
                    <th scope="col" style="text-align: center;">Sub Divisi</th>
                    <th scope="col" style="text-align: center;">Kirim Email</th>
                    <th scope="col" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($volunteer as $vol)
                    @if ($vol->divisi)
                        <tr style="background-color: #f9f9f9;">
                            <th scope="row" class="align-middle text-center">{{ $loop->iteration }}</th>
                            <td class="align-middle text-center">{{ $vol->nama }}</td>
                            <td class="align-middle text-center">{{ $vol->nim }}</td>
                            <td class="align-middle text-center">{{ $vol->email }}</td>
                            <td class="align-middle text-center">
                                {{ $vol->mulai_aktif }} - {{ $vol->akhir_aktif }}
                                <br> ({{ $vol->total_hari }} hari)
                            </td>
                            <td class="align-middle text-center {{ $vol->status == 'Aktif' ? 'status-aktif' : 'status-tidak-aktif' }}">
                                {{ $vol->status }}
                            </td>

                            <td class="align-middle text-center">
                                @if ($vol->status_etik == 'normal')
                                    <span class="badge bg-success text-center">Normal</span>
                                @elseif ($vol->status_etik == 'dalam_peninjauan')
                                    <span class="badge bg-warning text-center">Dalam Peninjauan</span>
                                @elseif ($vol->status_etik == 'dihentikan')
                                    <span class="badge bg-danger text-center">Dihentikan</span>
                                @else
                                    <span class="badge bg-secondary d-block text-center">{{ ucfirst($vol->status_etik) }}</span>
                                @endif
                            </td>                                    
                            
                            <td class="align-middle text-center">
                                {{ $vol->subDivisi ? $vol->subDivisi->nama_subdivisi : '-' }}
                            </td>
                            

                            <td class="align-middle text-center">
                                @if ($vol->status_etik === 'dihentikan')
                                    <button class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" disabled>
                                        <i class="bi bi-envelope-fill"></i> Kirim
                                    </button>
                                @else
                                    <a href="{{ route('kirimEmail', $vol->vol_id) }}" 
                                       class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 kirimEmailButton">
                                        <i class="bi bi-envelope-fill"></i> Kirim
                                    </a>
                                @endif
                            </td>
                            
                            
                            <td class="align-middle text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                                    <!-- Button Detail -->
                                    <button type="button" class="btn btn-info btn-sm d-inline-flex align-items-center"
                                            data-bs-toggle="modal" data-bs-target="#detailModal-{{ $vol->vol_id }}">
                                        <i class="bi bi-info-circle me-1"></i> Detail
                                    </button>
                            
                                    @if ($vol->status_etik === 'dihentikan')
                                        <button class="btn btn-success btn-sm d-inline-flex align-items-center" disabled>
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                    @else
                                        <a href="{{ route('edit_vlt', $vol->vol_id) }}"
                                           class="btn btn-success btn-sm d-inline-flex align-items-center">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                    @endif

                                    @if ($vol->status_etik === 'normal')
                                    <a href="{{ route('ajukanPeninjauan', $vol->vol_id) }}"
                                    data-id="{{ $vol->vol_id }}"
                                    class="btn btn-warning btn-sm d-inline-flex align-items-center ajukan-peninjauan-btn">
                                        <i class="bi bi-exclamation-circle me-1"></i> Tinjau
                                    </a>
                                @endif

                                
                                
                            
                                    <a href="{{ route('hapus_vlt', $vol->vol_id) }}"
                                       class="btn btn-danger btn-sm d-inline-flex align-items-center"
                                       onclick="confirmDelete(event, {{ $vol->vol_id }})">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </a>
                                </div>
                            
                                <!-- Form Hidden Submit Peninjauan -->
                                <form id="formPeninjauan" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </td>
                            

                    <!-- Modal Detail untuk setiap volunteer -->
<div class="modal fade" id="detailModal-{{ $vol->vol_id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $vol->vol_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel-{{ $vol->vol_id }}">Detail Volunteer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tampilkan detail volunteer -->
                <p><strong>Nama:</strong> {{ $vol->nama ?? '-' }}</p>
                <p><strong>NIM:</strong> {{ $vol->nim ?? '-' }}</p>
                <p><strong>Fakultas:</strong> {{ $vol->fakultas ?? '-' }}</p>
                <p><strong>Jurusan:</strong> {{ $vol->jurusan ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $vol->email ?? '-' }}</p>
                <p><strong>Bank/No Rekening:</strong> {{ $vol->no_rek_vlt ?? '-' }}</p>
                <p><strong>Mulai Aktif:</strong> {{ $vol->mulai_aktif ?? '-' }}</p>
                <p><strong>Akhir Aktif:</strong> {{ $vol->akhir_aktif ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $vol->status ?? '-' }}</p>
                <p><strong>Status Etik:</strong> {{ $vol->status_etik ?? '-' }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

            </tbody>
        </table>
    </div>
</div>
<script>
function confirmDelete(event, vol_id) {
    event.preventDefault(); // Mencegah aksi default (link)

    // Menampilkan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus dan tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Membuat form delete secara dinamis
            var form = document.createElement("form");
            form.method = "POST";
            form.action = '/hapus_vlt/' + vol_id;

            // Menambahkan csrf token
            var token = document.createElement("input");
            token.type = "hidden";
            token.name = "_token";
            token.value = "{{ csrf_token() }}";
            form.appendChild(token);

            // Menambahkan method DELETE
            var method = document.createElement("input");
            method.type = "hidden";
            method.name = "_method";
            method.value = "DELETE";
            form.appendChild(method);

            // Menambahkan form ke body dan submit
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<script>
    function cariData() {
        // Ambil input dan ubah ke huruf kecil
        var input = document.getElementById("searchInput").value.toLowerCase();
        var table = document.querySelector("table");
        var tr = table.getElementsByTagName("tr");

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            var text = row.textContent.toLowerCase();
            if (text.includes(input)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.ajukan-peninjauan-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const vol_id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Ajukan Peninjauan?',
                    text: 'Yakin ingin meninjau ulang volunteer ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tinjau',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/ajukanPeninjauan/${vol_id}`;
                    }
                });
            });
        });
    });
</script>

<!-- SweetAlert2 -->
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'Oke'
        });
    });
</script>
@endif




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

