@extends('layout.main')
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
                <h5 class="card-title mb-4 text-center">Edit Jam Presensi</h5>

                @if ($presensi)
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('updatePresensi', $presensi->presensi_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Volunteer --}}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control text-muted" id="nama" value="{{ $presensi->volunteer->nama }}" readonly>
                        <label for="nama">Nama Volunteer</label>
                    </div>

                    {{-- NIM --}}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control text-muted" id="nim" value="{{ $presensi->volunteer->nim }}" readonly>
                        <label for="nim">NIM</label>
                    </div>

                    {{-- Tanggal --}}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control text-muted" id="tanggal" value="{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d-m-Y') }}" readonly>
                        <label for="tanggal">Tanggal</label>
                    </div>

                    {{-- Check In --}}
                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control" name="check_in" id="check_in"
                            value="{{ \Carbon\Carbon::parse($presensi->check_in)->format('Y-m-d\TH:i') }}" required>
                        <label for="check_in">Check In</label>
                    </div>

                    {{-- Check Out --}}
                    <div class="form-floating mb-3">
                        <input type="datetime-local" class="form-control" name="check_out" id="check_out"
                            value="{{ \Carbon\Carbon::parse($presensi->check_out)->format('Y-m-d\TH:i') }}" required>
                        <label for="check_out">Check Out</label>
                    </div>

                    {{-- Deskripsi Tugas --}}
                    <div class="form-floating mb-3">
                        <textarea class="form-control text-muted" readonly>{{ $presensi->desk_tgs ?? '-' }}</textarea>
                        <label>Deskripsi Tugas</label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/data_presensi" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
                @else
                    <p class="text-center">Data presensi tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
