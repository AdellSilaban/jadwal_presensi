<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrasi</title>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
</head>

<body>
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card card-login shadow p-4" style="max-width: 420px; width: 100%;">
      <div class="text-center mb-4">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo LPKKSK" class="logo mb-2" style="height: 60px;">
        <h5 class="fw-bold mb-0">LPKKSK UKDW</h5>
        <small class="text-muted">Isi data lengkapmu untuk daftar akun.</small>
      </div>

      <form action="/simpanRegis" method="POST">
        @csrf
        <div class="mb-2">
          <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <div class="mb-2">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-2">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

       <!-- Jabatan -->
<div class="mb-2">
  <select id="jabatan" name="jabatan" class="form-control custom-select" required>
    <option value="" disabled selected>Pilih Jabatan</option>
    <option value="Koordinator Divisi Creative">Koordinator Divisi Creative</option>
    <option value="Koordinator Divisi Tim Ibadah Kampus">Koordinator Divisi Tim Ibadah Kampus</option>
    <option value="Koordinator Divisi Konseling">Koordinator Divisi Konseling</option>
  </select>
</div>

<!-- Divisi -->
<div class="mb-3">
  <select name="divisi_id" class="form-control custom-select" required>
    <option value="" disabled selected>Pilih Divisi</option>
    @foreach ($divisi as $div)
      <option value="{{ $div->divisi_id }}">{{ $div->nama_divisi }}</option>
    @endforeach
  </select>
</div>

          

        <button type="submit" class="btn btn-login w-100">
          <i class="fas fa-user-plus me-2"></i> Daftar
        </button>
      </form>

      @if ($errors->any())
        <div class="alert alert-danger mt-3 small">
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <p class="text-center mt-3 small-text">
        Sudah punya akun? <a href="/login" class="text-primary text-decoration-none">Login di sini</a>
      </p>
    </div>
  </div>
</body>

</html>
