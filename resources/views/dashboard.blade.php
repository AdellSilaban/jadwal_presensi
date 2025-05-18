@extends('layout.main')

@section('sidebar')

<ul class="sidebar-nav" id="sidebar-nav">
     <li class="nav-heading">Dashboard</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="dashboard"><i class="fas fa-users"></i>
        <span>Dashboard</span>
    </a>
</li>

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Volunteer</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="home_kepalaPKK"><i class="fas fa-users"></i>
        <span>Data Volunteer</span>
    </a>
</li>

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Divisi</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="div_kepalaPKK"><i class="fas fa-layer-group"></i>
        <span>Data Divisi</span>
    </a>
</li>

<ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">Koordinator Divisi</li>
    <li class="nav-item">
    <a class="nav-link collapsed" href="koor_kepalaPKK"><i class="fas fa-users"></i>
        <span>Data Koordinator</span>
    </a>
</li>
@endsection


@section('content')

<div id="content">
    <div class="container-fluid">
        <h1 class="h4 mb-1 text-gray-800">Dashboard Volunteer</h1>
        <br>

     <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm h-100 rounded-4 px-3 py-2">
            <div class="text-muted small">Total Volunteer</div>
            <div class="fs-4 fw-bold">{{ $total_volunteer }}</div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100 rounded-4 px-3 py-2">
            <div class="text-muted small">Volunteer Aktif</div>
            <div class="fs-4 fw-bold">{{ $vol_aktif }}</div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100 rounded-4 px-3 py-2">
            <div class="text-muted small">Volunteer Tidak Aktif</div>
            <div class="fs-4 fw-bold">{{ $vol_tidak_aktif }}</div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100 rounded-4 px-3 py-2">
            <div class="text-muted small">Total Jam Presensi</div>
            <div class="fs-4 fw-bold">{{ $total_jam_presensi }} jam</div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm h-100 rounded-4 px-3 py-2">
            <div class="text-muted small">Tugas Selesai</div>
            <div class="fs-4 fw-bold">{{ $total_tugas_selesai }}</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 px-4 py-3 h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Distribusi Volunteer per Divisi</h6>
                <canvas id="pieChartVolunteer" style="max-height: 320px;"></canvas>
                <small class="text-muted d-block mt-2 fst-italic text-center">
    *Grafik ini menampilkan distribusi volunteer aktif pada masing-masing divisi berdasarkan data terkini.*
</small>

            </div>
        </div>
    </div>
</div>




       
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const dataPie = {
        labels: {!! json_encode($volunteerPerDivisi->pluck('nama_divisi')) !!},
        datasets: [{
            label: 'Volunteer',
            data: {!! json_encode($volunteerPerDivisi->pluck('total')) !!},
            backgroundColor: [
                '#0dcaf0',
                '#198754',
                '#ffc107',
                '#6610f2',
                '#dc3545',
                '#6c757d'
            ],
            borderWidth: 1
        }]
    };

    const configPie = {
        type: 'pie',
        data: dataPie,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.label + ': ' + context.raw + ' volunteer';
                        }
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('pieChartVolunteer'), configPie);
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