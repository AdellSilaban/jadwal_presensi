@extends('layout.main')

@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="/home_vltcreative" ><i class="fas fa-fw fa-calendar"></i>
        <span>Task Management</span>
    </a>
</li>
@endsection


@section('content')
<br>
    <div id="content"> 
        <div class="container-fluid"> 
            <div>
                <h1 class="h3 mb-0 text-gray-800">Silahkan Kerjakan Task Management di Bawah Ini!</h1>
                <br>
                <br>

            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Tugas</th>
                            <th scope="col">Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Membuat Feed Instagram</td>
                            <td>20/10/2025</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="aksi">
                                {{-- <form method="post"> --}}
                                  @csrf
                                  <button type="submit" name="action" value="dikerjakan" class="btn btn-warning btn-sm shadow"><i class="bi-hourglass"></i> Kerjakan</button> 
                                    <button type="submit" name="action" value="selesai" class="btn btn-success btn-sm"><i class="bi-check-circle-fill"></i>  Selesai</button> 

                                {{-- </form> --}}
                                </td>
                        </tr> 
                    </tbody>
                </table>
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
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Adell Silaban</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" 
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/logout">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul> 

@endsection