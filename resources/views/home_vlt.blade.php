@extends('layout.main2')    

@section('topbar')
<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ $volunteer->nama }}</span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $volunteer->nama }}</h6>
            <span>Divisi {{ $volunteer->nama_divisi }}</span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/profile_vlt">
              <i class="bi bi-person"></i>
              <span>Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="/logoutVol">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->
@endsection

@section('content')
<div class="row g-3 mb-3 justify-content-center">
    <div class="col-md-4 col-12">
      <div class="card shadow-sm rounded-4 border-0 card-summary">
        <div class="card-body">
          <div class="text-muted small">Total Jadwal</div>
          <div class="fs-4 fw-bold text-dark">{{ $totalJadwal ?? 0 }}</div>
        </div>
      </div>
    </div>
  
    <div class="col-md-4 col-12">
      <div class="card shadow-sm rounded-4 border-0 card-summary">
        <div class="card-body">
          <div class="text-muted small">Total Jam Hadir</div>
          <div class="fs-4 fw-bold text-dark">{{ $totalHadir ?? 0 }}</div>
        </div>
      </div>
    </div>
  
    <div class="col-md-4 col-12">
      <div class="card shadow-sm rounded-4 border-0 card-summary">
        <div class="card-body">
          <strong class="text-dark">Aturan Presensi</strong>
          <span class="text-muted small"> | Penting</span>
          <ul class="ps-3 mt-2 mb-0 small">
            <li class="text-success">Presensi hanya dapat dilakukan di hari <strong>H</strong>.</li>
            <li class="text-primary">Lokasi harus berada di dalam kampus <strong>UKDW</strong>.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

    <div class="text-center mb-3">
      <h5 class="fw-semibold text-dark">Data Jadwal</h5>
    </div>

    <div class="card shadow-lg border-0 animate__animated animate__fadeIn">
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
                <th>Deskripsi Tugas</th>
                <th>Total Waktu</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($jadwals as $jdwl)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $jdwl->tgl_jadwal->format('d M Y') }}</td>
                <td class="text-capitalize">{{ $jdwl->agenda }}</td>
                <td>
                  @if (!$jdwl->my_presensi)
                      <form method="POST" action="{{ route('checkIn', ['jadwal_id' => $jdwl->jadwal_id]) }}" class="form-checkin">
                          @csrf
                          <input type="hidden" name="latitude" class="lat-field">
                          <input type="hidden" name="longitude" class="lng-field">
                          <button type="button"
                              class="btn btn-outline-success btn-sm rounded-pill shadow-sm hover-scale btn-checkin {{ $jdwl->canCheckIn ? '' : 'disabled' }}"
                              {{ $jdwl->canCheckIn ? '' : 'disabled' }}>
                              <i class="fas fa-sign-in-alt"></i> Check In
                          </button>
                      </form>
                  @elseif($jdwl->my_presensi->check_in)
                      <span class="badge pastel-badge-blue px-3 py-2 shadow-sm">
                          <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($jdwl->my_presensi->check_in)->format('d M Y H:i') }}
                      </span>
                  @else
                      <span class="text-muted">-</span>
                  @endif
              </td>
              
              
              <td>
                @if ($jdwl->my_presensi && !$jdwl->my_presensi->check_out)
                    @php
                        $now = \Carbon\Carbon::now();
                        $jamTutup = \Carbon\Carbon::parse($jdwl->jam_tutup);
                        $canCheckOut = $jdwl->is_today && $now->lessThan($jamTutup);
                    @endphp
                    <form method="POST" action="{{ route('checkOut', ['jadwal_id' => $jdwl->jadwal_id]) }}" class="form-checkout">
                        @csrf
                        <input type="hidden" name="desk_tgs" class="desk-tgs-field">
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm rounded-pill shadow-sm hover-scale btn-trigger-checkout {{ $canCheckOut ? '' : 'disabled' }}"
                            data-jadwal-id="{{ $jdwl->jadwal_id }}"
                            {{ $canCheckOut ? '' : 'disabled' }}>
                            <i class="fas fa-sign-out-alt"></i> Check Out
                        </button>
                    </form>
                @elseif ($jdwl->my_presensi && $jdwl->my_presensi->check_out)
                    <span class="badge pastel-badge-pink px-3 py-2 shadow-sm">
                        <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($jdwl->my_presensi->check_out)->format('d M Y H:i') }}
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            
            
            
            <td>
              @if ($jdwl->my_presensi && $jdwl->my_presensi->desk_tgs)
                {{ $jdwl->my_presensi->desk_tgs }}
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            

                  
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

<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Deskripsi Tugas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <textarea id="modalDeskTgs" class="form-control" rows="3" placeholder="Tugas yang dikerjakan..."></textarea>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="button" id="btnSubmitCheckout" class="btn btn-danger">Check Out</button>
          </div>
      </div>
  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="{{ asset('assets/css/sb-admin-2.css') }}">
<style>
  .hover-scale:hover {
    transform: scale(1.05);
    transition: 0.3s ease;
  }
  .icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
  }
  .bg-primary-light {
    background-color: #ebf2ff;
  }
  .bg-success-light {
    background-color: #e6f4ea;
  }
  .bullet {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
  }
</style>
@endpush

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-checkin');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const form = btn.closest('form');
            const latField = form.querySelector('.lat-field');
            const lngField = form.querySelector('.lng-field');

            console.log("🟢 Tombol diklik. Cari lokasi...");

            if (!navigator.geolocation) {
                alert("Browser tidak mendukung lokasi.");
                return;
            }

            navigator.geolocation.getCurrentPosition(function (pos) {
                latField.value = pos.coords.latitude;
                lngField.value = pos.coords.longitude;

                console.log("📍 Lokasi:", pos.coords.latitude, pos.coords.longitude);
                console.log("🚀 Submit form ke:", form.action);

                form.submit();
            }, function (err) {
                alert("Gagal mendapatkan lokasi: " + err.message);
            });
        });
    });
});

  </script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      let currentForm = null;
  
      document.querySelectorAll('.btn-trigger-checkout').forEach(button => {
          button.addEventListener('click', function () {
              currentForm = this.closest('form');
              document.getElementById('modalDeskTgs').value = '';
              const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
              modal.show();
          });
      });
  
      document.getElementById('btnSubmitCheckout').addEventListener('click', function () {
          const deskripsi = document.getElementById('modalDeskTgs').value.trim();
  
          if (!deskripsi) {
              alert('Deskripsi tugas tidak boleh kosong!');
              return;
          }
  
          if (currentForm) {
              const hiddenInput = currentForm.querySelector('.desk-tgs-field');
              if (hiddenInput) {
                  hiddenInput.value = deskripsi;
                  currentForm.submit();
              } else {
                  alert('Form tidak ditemukan atau input hilang.');
              }
          }
      });
  });
  </script>
  
  </script>
  
  
@endsection  

