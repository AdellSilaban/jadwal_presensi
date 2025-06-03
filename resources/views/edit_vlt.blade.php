@extends('layout.main')

@section('sidebar')
<ul class="sidebar-nav" id="sidebar-nav">
    @auth
        @php $jabatan = Auth::user()->jabatan; @endphp

        <li class="nav-heading">Manajemen Volunteer</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="home_koor">
                <i class="bi bi-house-door"></i>
                <span>Beranda</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="sub_divisi">
                <i class="bi bi-diagram-3"></i>
                <span>Sub Divisi</span>
            </a>
        </li>

        {{-- Tampilkan menu ini hanya jika bukan Koordinator Konseling --}}
        @if ($jabatan !== 'Koordinator Divisi Konseling')

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
        @endif

        {{-- Menu Upload Sertifikat tetap ditampilkan --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('formuploadSertif') }}">
                <i class="bi bi-upload"></i>
                <span>Upload Sertifikat</span>
            </a>
        </li>

        {{-- Menu Manajemen Tugas untuk Koordinator --}}
        @if ($jabatan === 'Koordinator Divisi Creative' || $jabatan === 'Koordinator Divisi Konseling')
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
                            <option value="Fakultas Teologi" {{ $volunteer->fakultas == 'Fakultas Teologi' ? 'selected' : '' }}>Fakultas Teologi</option>
                            <option value="Fakultas Teknologi Informasi" {{ $volunteer->fakultas == 'Fakultas Teknologi Informasi' ? 'selected' : '' }}>Fakultas Teknologi Informasi</option>
                            <option value="Fakultas Arsitektur dan Desain" {{ $volunteer->fakultas == 'Fakultas Arsitektur dan Desain' ? 'selected' : '' }}>Fakultas Arsitektur dan Desain</option>
                            <option value="Fakultas Bioteknologi" {{ $volunteer->fakultas == 'Fakultas Bioteknologi' ? 'selected' : '' }}>Fakultas Bioteknologi</option>
                            <option value="Fakultas Bisnis" {{ $volunteer->fakultas == 'Fakultas Bisnis' ? 'selected' : '' }}>Fakultas Bisnis</option>
                            <option value="Fakultas Kedokteran" {{ $volunteer->fakultas == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                            <option value="Fakultas Kependidikan dan Humaniora" {{ $volunteer->fakultas == 'Fakultas Kependidikan dan Humaniora' ? 'selected' : '' }}>Fakultas Kependidikan dan Humaniora</option>
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
                        <input type="text" class="form-control" id="bank_no_rek" name="bank_no_rek" value="{{ $volunteer->bank_no_rek }}">
                        <label for="bank_no_rek">Bank & Rekening Volunteer</label>
                    </div>
                    

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="mulai_aktif" name="mulai_aktif" value="{{ $volunteer->mulai_aktif }}">
                        <label for="mulai_aktif">Mulai Masa Aktif</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="akhir_aktif" name="akhir_aktif" value="{{ $volunteer->akhir_aktif }}">
                        <label for="akhir_aktif">Akhir Masa Aktif</label>
                    </div>

                    <div class="mb-3">
                        <select class="form-select" id="sub_divisi_id" name="sub_divisi_id">
                            <option value="">Pilih Sub Divisi</option>
                            @foreach ($subDivisi as $sub)
                                <option value="{{ $sub->sub_divisi_id }}" 
                                    {{ old('sub_divisi_id', $volunteer->sub_divisi_id ?? '') == $sub->sub_divisi_id ? 'selected' : '' }}>
                                    {{ $sub->nama_subdivisi }}
                                </option>
                            @endforeach
                        </select>
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
        "Fakultas Teologi": ["Filsafat Keilahian"],
        "Fakultas Teknologi Informasi": ["Sistem Informasi", "Informatika"],
        "Fakultas Arsitektur dan Desain": ["Arsitektur", "Desain Produk"],
       "Fakultas Bioteknologi": ["Biologi"],
        "Fakultas Bisnis": ["Manajemen", "Akuntansi"],
        "Fakultas Kedokteran": ["Kedokteran", "Profesi Dokter"],
        "Fakultas Kependidikan dan Humaniora": ["Pendidikan Bahasa Inggris", "Studi Humanitas"]
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

