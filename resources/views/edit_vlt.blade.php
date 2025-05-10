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
<br>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4 text-center">Edit Data Volunteer</h5>

                @if ($volunteer)
                <form action="{{ route('updateVlt', $volunteer->vol_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $volunteer->nama }}">
                        <label for="nama">Nama</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nim" name="nim" value="{{ $volunteer->nim }}">
                        <label for="nim">NIM</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="fakultas" name="fakultas" onchange="updateJurusan()" required>
                            <option value="">Pilih Fakultas</option>
                            <option value="FTI" {{ $volunteer->fakultas == 'FTI' ? 'selected' : '' }}>Fakultas Teknologi Informasi</option>
                            <option value="FAD" {{ $volunteer->fakultas == 'FAD' ? 'selected' : '' }}>Fakultas Arsitektur dan Desain</option>
                            <option value="FBIO" {{ $volunteer->fakultas == 'FBIO' ? 'selected' : '' }}>Fakultas Bioteknologi</option>
                            <option value="FBIS" {{ $volunteer->fakultas == 'FBIS' ? 'selected' : '' }}>Fakultas Bisnis</option>
                            <option value="FKED" {{ $volunteer->fakultas == 'FKED' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                            <option value="FKH" {{ $volunteer->fakultas == 'FKH' ? 'selected' : '' }}>Fakultas Kependidikan dan Humaniora</option>
                        </select>
                        <label for="fakultas">Fakultas</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <select class="form-select" id="jurusan" name="jurusan" required>
                            <option value="">Pilih Jurusan</option>
                        </select>
                        <label for="jurusan">Jurusan</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="email" name="email" value="{{ $volunteer->email }}">
                        <label for="fakultas">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="no_rek_vlt" class="form-control" id="no_rek_vlt" name="no_rek_vlt" value="{{ $volunteer->no_rek_vlt }}">
                        <label for="no_rek_vlt">Bank & Rekening Volunteer</label>
                    </div>
                    

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="mulai_aktif" name="mulai_aktif" value="{{ $volunteer->mulai_aktif }}">
                        <label for="mulai_aktif">Mulai Masa Aktif</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="akhir_aktif" name="akhir_aktif" value="{{ $volunteer->akhir_aktif }}">
                        <label for="akhir_aktif">Akhir Masa Aktif</label>
                    </div>
                    <br>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/home_koor" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @else
                    <p class="text-center">Data volunteer tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const jurusanMap = {
        FTI: ["Sistem Informasi", "Informatika"],
        FAD: ["Arsitektur", "Desain Produk"],
        FBIO: ["Biologi"],
        FBIS: ["Manajemen", "Akuntansi"],
        FKED: ["Kedokteran"],
        FKH: ["Pendidikan Bahasa Inggris", "Studi Humanitas"]
    };

    function updateJurusan() {
        const fakultas = document.getElementById("fakultas").value;
        const jurusanSelect = document.getElementById("jurusan");
        jurusanSelect.innerHTML = '<option value="">Pilih Jurusan</option>';

        if (jurusanMap[fakultas]) {
            jurusanMap[fakultas].forEach(jrs => {
                const option = document.createElement("option");
                option.value = jrs;
                option.text = jrs;
                if (jrs === "{{ $volunteer->jurusan }}") {
                    option.selected = true;
                }
                jurusanSelect.appendChild(option);
            });
        }
    }

    // Panggil saat halaman pertama kali dibuka
    window.onload = updateJurusan;
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

