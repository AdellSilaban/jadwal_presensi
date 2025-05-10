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
                <h5 class="card-title mb-4" style="text-align: center;">Tambah Data Volunteer</h5>
                <form action="/simpanVlt" method="POST">
                    @csrf
                    @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama" required>
                        <label for="nama">Nama</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" 
                               class="form-control @error('nim') is-invalid @enderror" 
                               id="nim" 
                               name="nim" 
                               value="{{ old('nim') }}" 
                               placeholder="Masukkan NIM" 
                               required>
                        <label for="nim">NIM</label>

                        @error('nim')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="fakultas" name="fakultas" onchange="updateJurusan()" required>
                            <option value="">Pilih Fakultas</option>
                            <option value="FTI">Fakultas Teknologi Informasi</option>
                            <option value="FAD">Fakultas Arsitektur dan Desain</option>
                            <option value="FBIO">Fakultas Bioteknologi</option>
                            <option value="FBIS">Fakultas Bisnis</option>
                            <option value="FKED">Fakultas Kedokteran</option>
                            <option value="FKH">Fakultas Kependidikan dan Humaniora</option>
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
                        <input type="no_rek_vlt" class="form-control" id="no_rek_vlt" name="no_rek_vlt" placeholder="Masukkan Bank dan Nomor Rekening Volunteer" required>
                        <label for="no_rek_vlt">Bank & Rekening Volunteer</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" oninput="cekEmailValid()" required>
                        <label for="email">Email</label>
                        <small id="emailFeedback" class="text-danger d-none">Format email tidak valid</small>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="mulai_aktif" name="mulai_aktif" required>
                        <label for="mulai_aktif">Mulai Masa Aktif</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="akhir_aktif" name="akhir_aktif" required>
                        <label for="akhir_aktif">Akhir Masa Aktif</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" value="{{ $divisi->nama_divisi }}" readonly>
                        <label for="nama_divisi">Divisi</label>
                        <input type="hidden" name="divisi_id" value="{{ $divisi->divisi_id }}">
                    </div>

                    @if(in_array(Auth::user()->divisi->nama_divisi, ['Creative', 'Tim Ibadah Kampus']))
                    <div class="mb-3">
                        <select class="form-select" id="sub_divisi_id" name="sub_divisi_id">
                            <option value="">Pilih Sub Divisi</option>
                            @foreach ($subDivisi as $sub)
                                <option value="{{ $sub->sub_divisi_id }}">{{ $sub->nama_subdivisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif



                    
                    
                    

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/home_koor" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        
                    </div>
  </form>

  <script>
    function cekEmailValid() {
      const email = document.getElementById("email").value;
      const feedback = document.getElementById("emailFeedback");
      const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
      if (email && !pattern.test(email)) {
        feedback.classList.remove("d-none");
      } else {
        feedback.classList.add("d-none");
      }
    }
  </script>

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

        // Clear existing options
        jurusanSelect.innerHTML = '<option value="">Pilih Jurusan</option>';

        if (jurusanMap[fakultas]) {
            jurusanMap[fakultas].forEach(jrs => {
                const option = document.createElement("option");
                option.value = jrs;
                option.text = jrs;
                jurusanSelect.appendChild(option);
            });
        }
    }
</script>

</div>

        </div>
      </div>
</div>
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

