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
    @else
        <li class="nav-item">
            <a class="nav-link collapsed" href="jadwal_vlt"><i class="fas fa-calendar-alt"></i>
                <span>Jadwal Volunteer</span>
            </a>
        </li>
    @endif
@else
    <li class="nav-item">
        <a class="nav-link collapsed" href="jadwal_vlt"><i class="fas fa-calendar-alt"></i>
            <span>Jadwal Volunteer</span>
        </a>
    </li>
@endauth

<li class="nav-item">
    <a class="nav-link collapsed" href="validasi_presensi"><i class="fas fa-check"></i>
        <span>Validasi Presensi</span>
    </a>
</li>
@endsection


@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
          <i class="fas fa-plus fa-sm"></i> Tambah Volunteer
        </a>
      </div>
    </div>
  </div>
  
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover shadow rounded" style="border-color: #dee2e6; transform: perspective(1000px) rotateX(0deg) rotateY(0deg); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                        <tr>
                            <th scope="col" style="text-align: center;">No</th>
                            <th scope="col" style="text-align: center;">Nama</th>
                            <th scope="col" style="text-align: center;">NIM</th>
                            <th scope="col" style="text-align: center;">Email</th>
                            <th scope="col" style="text-align: center;">Periode</th>
                            <th scope="col" style="text-align: center;">Status</th>
                            <th scope="col" style="text-align: center;">Divisi</th>
                            <th scope="col" style="text-align: center;">Kirim Email</th>
                            <th scope="col" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($volunteer as $vol)
                        
                            @if ($vol->divisi)
                            
                                <tr style="background-color: #f9f9f9;">
                                    <th scope="row" style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</th>
                                    <td style="text-align: center; vertical-align: middle;">{{ $vol->nama }}</td>
                                    <td style="text-align: center; vertical-align: middle;">{{ $vol->nim }}</td>
                                    <td style="text-align: center; vertical-align: middle;">{{ $vol->email }}</td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        {{-- {{ \Carbon\Carbon::parse($vol->mulai_aktif)->format('d-m-Y') }} - {{ \Carbon\Carbon::parse($vol->akhir_aktif)->format('d-m-Y') }} --}}
                                        {{ $vol->mulai_aktif }} - {{ $vol->akhir_aktif }}
                                        <br>
                                        ({{ $vol->total_hari }} hari)
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;" class="{{ $vol->status == 'Aktif' ? 'status-aktif' : 'status-tidak-aktif' }}">
                                        {{ $vol->status }}
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">{{ $vol->divisi ? $vol->divisi->nama_divisi : '-' }}</td>
                                   
                                    <td style="text-align: center; vertical-align: middle;">
                                        <a href="{{ route('kirimEmail', $vol->vol_id) }}" 
                                           class="btn btn-primary btn-sm kirimEmailButton">
                                            Kirim
                                        </a>
                                    </td>
                                    
                                    <td style="text-align: center; vertical-align: middle;">
                                        <a href="{{ route('edit_vlt', $vol->vol_id) }}" class="btn btn-success btn-sm shadow">
                                            <i class="fas fa-pen"></i> Edit
                                        </a>
                                        <a href="{{ route('hapus_vlt', $vol->vol_id) }}" class="btn btn-danger btn-sm" onclick="confirmDelete(event, {{ $vol->vol_id }})">
                                            <i class="bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, vol_id) {
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
                form.action = "{{ route('hapus_vlt', '') }}/" + vol_id;
                form.innerHTML = ` @csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<script>
    function cariData() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.querySelector(".table"); // Mendapatkan elemen tabel
        const tbody = table.getElementsByTagName("tbody")[0];
        const rows = tbody.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const namaTd = rows[i].getElementsByTagName("td")[0]; // Kolom Nama
            const nimTd = rows[i].getElementsByTagName("td")[1];   // Kolom NIM
            const emailTd = rows[i].getElementsByTagName("td")[2]; // Kolom Email

            if (namaTd || nimTd || emailTd) {
                const nama = namaTd ? namaTd.textContent.toUpperCase() : "";
                const nim = nimTd ? nimTd.textContent.toUpperCase() : "";
                const email = emailTd ? emailTd.textContent.toUpperCase() : "";

                if (nama.indexOf(filter) > -1 || nim.indexOf(filter) > -1 || email.indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
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
