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
<br>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4" style="text-align: center;">Tambah Data Volunteer</h5>
                <form action="/simpanVlt" method="POST">
                    @csrf
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
    <ul class="navbar-nav ml-auto"> 
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->nama }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" 
                 aria-labelledby="userDropdown">
                {{-- <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a> --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/logout">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul> 

@endsection
