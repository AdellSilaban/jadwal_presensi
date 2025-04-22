@extends('layout.main')

@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="home_kepalaPKK"><i class="fas fa-users"></i>
        <span>Data Volunteer</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="div_kepalaPKK"><i class="fas fa-layer-group"></i>
        <span>Data Divisi</span>
    </a>
</li>
@endsection

@section('content')
    <div id="content">
        <div class="container-fluid">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Data Volunteer</h1>
                <br>
                
            </div>

            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Jurusan</th>
                            <th scope="col">Email</th>
                            <th scope="col">Periode</th>
                            <th scope="col">Divisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($volunteer as $vol)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $vol->nama }}</td>
                                <td>{{ $vol->nim }}</td>
                                <td>{{ $vol->fakultas }}</td>
                                <td>{{ $vol->jurusan }}</td>
                                <td>{{ $vol->email }}</td>
                                <td>{{ $vol->periode }}</td>
                                <td>{{ $vol->divisi ? $vol->divisi->nama_divisi : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Tambahkan pagination di sini jika diperlukan --}}
            </div>
        </div>
    </div>
@endsection

@section('topbar')
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nama }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/logout">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
@endsection