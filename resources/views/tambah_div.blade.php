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
<br>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow rounded">
            <div class="card-body">
                <h5 class="card-title mb-4 text-center">Tambah Data Divisi</h5>
                <form action="/simpanDiv" method="POST">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" placeholder="Masukkan nama divisi">
                        <label for="nama_divisi">Nama Divisi</label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="desk_divisi" name="desk_divisi" style="height: 100px" placeholder="Masukkan deskripsi divisi"></textarea>
                        <label for="desk_divisi">Deskripsi Divisi</label>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/div_kepalaPKK" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
