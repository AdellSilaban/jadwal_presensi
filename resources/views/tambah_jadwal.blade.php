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
                <h5 class="card-title mb-4 text-center">Tambah Jadwal</h5>
                <form action="/simpanjadwal" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" value="{{ $divisi->nama_divisi }}" readonly>
                        <label for="nama_divisi">Divisi</label>
                        <input type="hidden" name="divisi_id" value="{{ $divisi->divisi_id }}">
                    </div>

                    <div id="jadwal-container"></div>

                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addJadwalBtn">
                            <i class="bi bi-plus-circle"></i> Tambah Jadwal
                        </button>
                    </div>
                    <br>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="/jadwal_vlt" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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
        </ul>
      </li>
    </ul>
</nav>
<script>
let jadwalIndex = 0;

function generateJadwalForm(index) {
  let isPertama = index === 0;

  let petugasFields = `
    <div class="mb-2">
      <label class="form-label">${isPertama ? 'Pilih Petugas' : 'Petugas Khusus:'}</label>
      <div class="row">
        @foreach ($voldiv as $ptgs)
          @if ($ptgs->status === 'Aktif' && (
                Auth::user()->jabatan === 'Koordinator Divisi Tim Ibadah Kampus' ||
                (Auth::user()->jabatan === 'Koordinator Divisi Creative' && optional($ptgs->subDivisi)->nama_subdivisi === 'PKK Live')
              ))
            <div class="form-check col-md-6">
              <input class="form-check-input" type="checkbox"
                name="${isPertama ? 'petugas[]' : 'jadwals['+index+'][petugas][]'}"
                value="{{ $ptgs->vol_id }}">
              <label class="form-check-label">{{ $ptgs->nama }}</label>
            </div>
          @endif
        @endforeach
      </div>
    </div>
  `;

  return `
    <div class="jadwal-item border rounded p-3 mb-4">
      <h6 class="fw-semibold text-primary mb-2">Jadwal ke-${index + 1}</h6>

      <div class="form-floating mb-3">
        <input type="date" class="form-control" name="jadwals[${index}][tgl_jadwal]">
        <label>Tanggal</label>
      </div>
      <div class="form-floating mb-3">
        <input type="text" class="form-control" name="jadwals[${index}][agenda]">
        <label>Agenda</label>
      </div>
      <div class="form-floating mb-3">
        <input type="time" class="form-control" name="jadwals[${index}][jam_buka]">
        @if ($errors->has("jadwals.jam_buka"))
    <div class="text-danger">{{ $errors->first("jadwals.$index.jam_buka") }}</div>
    @endif
        <label>Jam Buka</label>
        

      </div>
      <div class="form-floating mb-3">
        <input type="time" class="form-control" name="jadwals[${index}][jam_tutup]">
        @if ($errors->has("jadwals.jam_tutup"))
    <div class="text-danger">{{ $errors->first("jadwals.$index.jam_tutup") }}</div>
@endif

        <label>Jam Tutup</label>
      </div>

      ${isPertama ? petugasFields : `
        <div class="form-check mb-2">
          <input class="form-check-input toggle-petugas-khusus" type="checkbox" data-index="${index}" id="togglePetugas${index}">
          <label class="form-check-label" for="togglePetugas${index}">🔄 Petugas berbeda untuk jadwal ini?</label>
        </div>
        <small class="text-muted d-block mb-2">📌 Petugas saat ini: <span id="infoPetugas${index}">Sama seperti jadwal 1</span></small>
        <div class="petugas-khusus d-none border rounded p-3" id="petugasKhusus${index}">
          ${petugasFields}
        </div>
      `}
    </div>
  `;
}

function tambahJadwal() {
  const container = document.getElementById('jadwal-container');
  container.insertAdjacentHTML('beforeend', generateJadwalForm(jadwalIndex));
  jadwalIndex++;
}

document.addEventListener('DOMContentLoaded', () => {
  tambahJadwal();

  document.getElementById('addJadwalBtn').addEventListener('click', tambahJadwal);

  document.addEventListener('change', function (e) {
    if (e.target.classList.contains('toggle-petugas-khusus')) {
      const idx = e.target.dataset.index;
      const petugasBox = document.getElementById('petugasKhusus' + idx);
      const label = document.getElementById('infoPetugas' + idx);
      const checked = e.target.checked;

      petugasBox.classList.toggle('d-none', !checked);
      label.textContent = checked ? 'Petugas Khusus' : 'Global';
    }
  });
});
</script>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr(".timepicker", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });
</script>
<script src="/js/jadwalForm.js"></script>
@endpush
