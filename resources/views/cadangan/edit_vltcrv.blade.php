@extends('layout.main')
@section('sidebar')
<li class="nav-item">
    <a class="nav-link collapsed" href="home_koorcrv"><i class="fas fa-fw fa-home"></i>
        <span>Home</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="task_mn"><i class="fas fa-list"></i>
        <span>Task Management</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="validasi_task"><i class="fas fa-check"></i>
        <span>Validasi Task</span>
    </a>
</li>
@endsection

@section('content')
    <br>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="form-group">
                <div class="modal-body">
                    <h5 class="modal-title" id="editVltLabel">Edit Data Volunteer</h5>
                    <br>
                    <div class="modal-body">
                        @if ($volunteer)  {{-- Periksa apakah $volunteer ada --}}
                            <form action="{{ route('updateVltcrv', $volunteer->vol_id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $volunteer->nama }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" class="form-control" id="nim" name="nim" value="{{ $volunteer->nim }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="fakultas">Fakultas</label>
                                    <input type="text" class="form-control" id="fakultas" name="fakultas" value="{{ $volunteer->fakultas }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="jurusan">Jurusan</label>
                                    <input type="text" class="form-control" id="jurusan" name="jurusan" value="{{ $volunteer->jurusan }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="periode">Periode</label>
                                    <input type="text" class="form-control" id="periode" name="periode" value="{{ $volunteer->periode }}" required>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>  {{-- PERBAIKAN: tombol submit --}}
                                </div>
                            </form>
                        @else
                            <p>Data volunteer tidak ditemukan.</p>  {{-- Pesan jika $volunteer null --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection