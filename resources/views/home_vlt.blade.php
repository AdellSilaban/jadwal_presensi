@extends('layout.main2')

@section('topbar')
<ul class="navbar-nav ml-auto align-items-center">
    {{-- Tombol Profile --}}
    <li class="nav-item me-2">
        <a href="/profile" class="btn btn-outline-primary btn-sm shadow-sm">
            <i class="fas fa-user me-1"></i> Profile
        </a>
    </li>

    {{-- Dropdown User (hanya logout) --}}
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $volunteer->nama }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
            aria-labelledby="userDropdown">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/logout">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-600"></i>
                Logout
            </a>
        </div>
    </li>
</ul>
@endsection



@section('content')
<br>
<div id="content">
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-md-6">
              <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                  <h5 class="card-title mb-2 text-secondary">Total Jadwal</h5>
                  <p class="card-text fs-3 fw-semibold text-dark">{{ $totalJadwal ?? '-' }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                  <h5 class="card-title mb-2 text-secondary">Total Kehadiran</h5>
                  <p class="card-text fs-3 fw-semibold text-dark">{{ $totalHadir ?? '-' }}</p>
                </div>
              </div>
            </div>
        </div>
        
          

        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Agenda</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Total Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwals as $jdwl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $jdwl->tgl_jadwal->format('d M Y') }}</td>
                                    <td class="text-capitalize">{{ $jdwl->agenda }}</td>

                                    {{-- Kolom Check In --}}
            <td>
                @if (!$jdwl->my_presensi)
                    <a href="{{ route('checkIn', $jdwl->jadwal_id) }}" class="btn btn-outline-success btn-sm rounded-pill shadow-sm">
                        <i class="fas fa-sign-in-alt"></i> Check In
                    </a>
                @else
                    <span class="badge badge-success px-3 py-2 shadow-sm">
                        <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($jdwl->my_presensi->check_in)->format('d M Y H:i') }}
                    </span>
                @endif
            </td>

            {{-- Kolom Check Out --}}
            <td>
                @if ($jdwl->my_presensi && !$jdwl->my_presensi->check_out)
                    <a href="{{ route('checkOut', $jdwl->jadwal_id) }}" class="btn btn-outline-danger btn-sm rounded-pill shadow-sm">
                        <i class="fas fa-sign-out-alt"></i> Check Out
                    </a>
                @elseif ($jdwl->my_presensi && $jdwl->my_presensi->check_out)
                    <span class="badge badge-primary px-3 py-2 shadow-sm">
                        <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($jdwl->my_presensi->check_out)->format('d M Y H:i') }}
                    </span>
                @endif
            </td>

            {{-- Kolom Total Jam --}}
            <td>
                @if ($jdwl->my_presensi && $jdwl->my_presensi->total_jam)
                    {{ $jdwl->my_presensi->total_jam }}
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
        </tr>
    @endforeach

    @if ($jadwals->isEmpty())
        <tr>
            <td colspan="6" class="text-muted text-center">Belum ada jadwal yang tersedia.</td>
        </tr>
    @endif
</tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


