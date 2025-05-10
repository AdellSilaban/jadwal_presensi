@extends('layout.main')

@section('sidebar')
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
@endsection

@section('content')

<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Data Volunteer</h1>
        <p style="font-size: 0.9rem;">Daftar seluruh volunteer dari berbagai divisi.</p>
        <br>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <!-- Search Input -->
            <div class="flex-grow-1 me-3">
                <input type="text" class="form-control shadow-sm" style="max-width: 260px; border-radius: 0.65rem; padding: 0.4rem 0.9rem; font-size: 0.9rem;" placeholder="Cari Nama, NIM, atau Email..." id="searchInput" onkeyup="cariData()" />
            </div>

            <!-- Tombol aksi -->
            <div class="d-flex gap-2">
                <a href="{{ route('export.volunteer.pdf') }}" class="btn btn-danger btn-sm shadow-sm">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6;">
                    <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col" class="text-center">Nama</th>
                            <th scope="col" class="text-center">NIM</th>
                            <th scope="col" class="text-center">Fakultas</th>
                            <th scope="col" class="text-center">Jurusan</th>
                            <th scope="col" class="text-center">Email</th>
                            <th scope="col" class="text-center">Periode</th>
                            <th scope="col" class="text-center">Divisi</th>
                            <th scope="col" class="text-center">Sub Divisi</th>
                            <th scope="col" class="text-center">Status Etik</th>
                            <th scope="col" class="text-center text-nowrap">Aksi Divisi</th>
                            <th scope="col" class="text-center text-nowrap">Tindakan Etik</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($volunteer as $vol)
                        <tr style="background-color: #f9f9f9;">
                            <th scope="row" class="text-center align-middle  text-nowrap">{{ $loop->iteration }}</th>
                            <td class="text-center align-middle text-nowrap">{{ $vol->nama }}</td>
                            <td class="text-center align-middle text-nowrap">{{ $vol->nim }}</td>
                            <td class="text-center align-middle text-nowrap">{{ $vol->fakultas }}</td>
                            <td class="text-center align-middle text-nowrap">{{ $vol->jurusan }}</td>
                            <td class="text-center align-middle text-nowrap">{{ $vol->email }}</td>
                            <td class="text-center align-middle text-nowrap">
                                {{ $vol->mulai }} - {{ $vol->akhir }}<br>
                                ({{ $vol->total_hari }} hari)
                            </td>
                            <td class="text-center align-middle text-nowrap">{{ $vol->divisi ? $vol->divisi->nama_divisi : '-' }}</td>
                            <td class="text-center align-middle text-nowrap">{{ $vol->subDivisi->nama_subdivisi ?? '-' }}</td>

                            <td class="align-middle text-center">
                                @if ($vol->status_etik == 'normal')
                                    <span class="badge bg-success text-center">Normal</span>
                                @elseif ($vol->status_etik == 'dalam_peninjauan')
                                    <span class="badge bg-warning text-center">Dalam Peninjauan</span>
                                @elseif ($vol->status_etik == 'dihentikan')
                                    <span class="badge bg-danger text-center">Dihentikan</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($vol->status_etik) }}</span>
                                @endif
                            </td>

                            <!-- Kolom Aksi Divisi -->
<td class="text-center align-middle text-nowrap">
    @php
        $isDisabled = $vol->status_etik === 'dihentikan';
    @endphp

    <div class="d-flex justify-content-center gap-1">
        <!-- Edit Divisi Button -->
        <button 
            class="btn btn-sm btn-success"
            data-bs-toggle="modal" 
            data-bs-target="#editDivisiModal{{ $vol->vol_id }}"
            {{ $isDisabled ? 'disabled' : '' }}>
            <i class="bi bi-pencil-square me-1"></i> Edit Divisi
        </button>
    </div>
</td>

<!-- Kolom Tindakan Etik -->
<td class="text-center align-middle text-nowrap">
    <div class="d-flex justify-content-center gap-1">
        <!-- Pulihkan Button (jika status 'dalam_peninjauan') -->
        @if ($vol->status_etik == 'dalam_peninjauan')
        <form method="POST" action="{{ route('pulihkanVolunteer', $vol->vol_id) }}">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
                <i class="bi bi-arrow-repeat"></i> Pulihkan
            </button>
        </form>
        @endif

           <!-- Hentikan Volunteer Button (hanya jika status bukan 'normal') -->
           @if ($vol->status_etik !== 'normal' && $vol->status_etik !== 'dihentikan')

           <form method="POST" action="{{ route('hentikanVolunteer', $vol->vol_id) }}">
               @csrf
               <button type="submit" class="btn btn-danger btn-sm">
                   <i class="bi bi-exclamation-circle me-1"></i> Hentikan
               </button>
           </form>
       @endif
   </div>
</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                
                    <!-- Modal diletakkan DI LUAR tabel -->
@foreach ($volunteer as $vol)
<div class="modal fade" id="editDivisiModal{{ $vol->vol_id }}" tabindex="-1" aria-labelledby="editDivisiLabel{{ $vol->vol_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formDivisi{{ $vol->vol_id }}" action="{{ route('update_divVol', $vol->vol_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDivisiLabel{{ $vol->vol_id }}">Edit Divisi - {{ $vol->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="divisi_id" class="form-label">Divisi</label>
                        <select name="divisi_id" class="form-select" required>
                            @foreach($divisi as $div)
                            <option value="{{ $div->divisi_id }}" {{ $vol->divisi_id == $div->divisi_id ? 'selected' : '' }}>
                                {{ $div->nama_divisi }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="konfirmasiPindah('{{ $vol->vol_id }}')">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

                
            </div>
        </div>
    </div>
</div>

<script>
    function cariData() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.querySelector(".table");
        const tbody = table.getElementsByTagName("tbody")[0];
        const rows = tbody.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            if (cells.length >= 5) {
                const nama = cells[0].textContent.toUpperCase();
                const nim = cells[1].textContent.toUpperCase();
                const email = cells[4].textContent.toUpperCase();

                if (nama.includes(filter) || nim.includes(filter) || email.includes(filter)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
</script>

<script>
    function konfirmasiPindah(vol_id) {
        Swal.fire({
            title: 'Yakin ingin memindahkan volunteer?',
            text: "Perubahan ini akan mengubah divisi volunteer secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, pindahkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`#formDivisi${vol_id}`).submit();
            }
        })
    }
</script>

<script>
    document.querySelectorAll('.btn-hentikan').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.hentikan-form');

            Swal.fire({
                title: 'Yakin ingin menghentikan volunteer ini?',
                text: 'Status etik volunteer akan diubah menjadi "dihentikan".',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hentikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
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