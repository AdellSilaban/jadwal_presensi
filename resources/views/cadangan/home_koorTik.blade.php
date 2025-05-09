@extends('layout.main')
@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="home_koorTik"><i class="fas fa-fw fa-home"></i>
        <span>Home</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="jadwal_vltTik"><i class="fas fa-calendar-alt"></i>
        <span>Jadwal Volunteer</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="validasi_presensiTIk"><i class="fas fa-check"></i>
        <span>Validasi Presensi</span>
    </a>
</li>
@endsection


@section('content')
    <div id="content"> 
        <div class="container-fluid"> 
            <div>
                <h1 class="h3 mb-0 text-gray-800">Data Volunteer</h1>
                <br>
                <!--TAMBAH DATA BARU VOLUNTEER-->
                <a href="/tambah_vltTik" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data Volunteer Baru</a>
                        
            </div>

            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Email</th>
                            <th scope="col">Periode</th>
                            <th scope="col">Divisi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($volunteer as $vol)
                        @if ($vol->divisi)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $vol->nama }}</td>
                                <td>{{ $vol->nim }}</td>
                                <td>{{ $vol->email }}</td>
                                <td>{{ $vol->periode }}</td>
                                <td>{{ $vol->divisi->nama_divisi }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="aksi">
                                        @csrf
                                        <a href="{{ route('edit_vltLive', $vol->vol_id) }}" class="btn btn-success btn-sm shadow"><i class="bi bi-pen"></i>Edit</a> 
                                        <a href="{{ route('hapus_vltLive', $vol->vol_id) }}" class="btn btn-danger btn-sm" onclick="confirmDelete(event, {{ $vol->vol_id }})"><i class="bi-trash"></i>Hapus</a>
                                      
                                           <script>
        function confirmDelete(event, vol_id) {
            event.preventDefault();

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('hapus_vltLive', '') }}/" + vol_id; // URL yang benar
                    form.innerHTML = ` @csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                        
            </tbody>
        </table>
                            
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
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
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