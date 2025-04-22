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
<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Manajemen Data Jadwal Volunteer</h1>
        <p style="font-size: 0.9rem;">
            Menampilkan seluruh jadwal aktivitas volunteer.
        </p> 
  <br>
  
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <!-- Search Input -->
        <div class="flex-grow-1 me-3">
          <input type="text"
                 class="form-control shadow-sm"
                 style="max-width: 260px; border-radius: 0.65rem; padding: 0.4rem 0.9rem; font-size: 0.9rem;"
                 placeholder="Cari data...."
                 id="searchInput"
                 onkeyup="cariData()" />
        </div>
  
        <!-- Tombol Tambah -->
        <a href="/tambah_jadwal" class="btn btn-primary shadow-sm px-3 py-1 rounded-pill d-flex align-items-center gap-2"
           style="font-size: 0.9rem;">
          <i class="fas fa-plus fa-sm"></i> Tambah Jadwal
        </a>
      </div>
    </div>
  </div>
  
    
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover shadow rounded" style="border-color: #dee2e6; transform: perspective(1000px) rotateX(0deg) rotateY(0deg); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <thead style="background: linear-gradient(to right, #f0f0f0, #e0e0e0);">
                        <tr>
                            <th scope="col" style="text-align: center;">No</th>
                            <th scope="col" style="text-align: center;">Tanggal Kegiatan</th>
                            <th scope="col" style="text-align: center;">Agenda</th>
                            <th scope="col" style="text-align: center;">Petugas</th>
                            <th scope="col" style="text-align: center;">Divisi</th>
                            <th scope="col" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $jdwl)
                            <tr style="background-color: #f9f9f9;">
                                <th scope="row" style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</th>
                                <td style="text-align: center; vertical-align: middle;">{{ $jdwl->tgl_jadwal->format('d-m-Y') }}</td>
                                <td style="text-align: center; vertical-align: middle;">{{ $jdwl->agenda }}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    @foreach ($jdwl->volunteers as $volunteer)
                                        {{ $volunteer->nama }} <br>
                                    @endforeach
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    {{ $jdwl->divisi->nama_divisi }}
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="{{ route('edit_jadwal', $jdwl->jadwal_id) }}" class="btn btn-success btn-sm shadow">
                                        <i class="bi bi-pen"></i> Edit
                                    </a>
                                    <a href="{{ route('hapus_jdwl', $jdwl->jadwal_id) }}" class="btn btn-danger btn-sm" onclick="confirmDelete(event, {{ $jdwl->jadwal_id }})">
                                        <i class="bi-trash"></i> Hapus
                                    </a>


                                           <script>
                                            function confirmDelete(event, jadwal_id) {
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
                                                        form.action = "{{ route('hapus_jdwl', '') }}/" + jadwal_id; // URL yang benar
                                                        form.innerHTML = ` @csrf @method('DELETE')`;
                                                        document.body.appendChild(form);
                                                        form.submit();
                                                    }
                                                });
                                            }
                                        </script>
                                    
                                </td>
                            </tr>

                            <script>
                                function cariData() {
                                    const input = document.getElementById("searchInput");
                                    const filter = input.value.toUpperCase();
                                    const table = document.querySelector(".table"); // Mendapatkan elemen tabel
                                    const tbody = table.getElementsByTagName("tbody")[0];
                                    const rows = tbody.getElementsByTagName("tr");
                            
                                    for (let i = 0; i < rows.length; i++) {
                                        const tgl_jadwaltd = rows[i].getElementsByTagName("td")[0]; // Kolom Nama
                                        const agendatd = rows[i].getElementsByTagName("td")[1];   // Kolom NIM
                                        const petugastd = rows[i].getElementsByTagName("td")[2]; // Kolom Email
                            
                                        if (tgl_jadwaltd || agendatd || petugastd) {
                                            const tgl_jadwal = tgl_jadwaltd ? tgl_jadwaltd.textContent.toUpperCase() : "";
                                            const agenda = agendatd ? agendatd.textContent.toUpperCase() : "";
                                            const petugas = petugastd ? petugastd.textContent.toUpperCase() : "";
                            
                                            if (tgl_jadwal.indexOf(filter) > -1 || agenda.indexOf(filter) > -1 || petugas.indexOf(filter) > -1) {
                                                rows[i].style.display = "";
                                            } else {
                                                rows[i].style.display = "none";
                                            }
                                        }
                                    }
                                }
                            </script>
                    @endforeach
                        
            </tbody>
        </table>
                            
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