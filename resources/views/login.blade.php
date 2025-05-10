<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

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
    <div class="card card-login shadow p-4">
      <div class="text-center mb-4">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo LPKKSK" class="logo mb-2">
        <h5 class="fw-bold mb-0">LPKKSK UKDW</h5>
        <small class="text-muted">Gunakan akunmu untuk masuk ke website.</small>
      </div>

      <!-- Alert Error -->
@if (session('error'))
<div class="alert alert-danger text-center small p-2 mb-3" role="alert">
  {{ session('error') }}
</div>
@endif


      <form method="POST" action="/ceklogin">
        @csrf
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
        </div>
        <div class="mb-4">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-login w-100">
          <i class="fas fa-sign-in-alt me-2"></i> Log In
        </button>
      </form>

      <p class="text-center mt-2 small-text">
        Belum punya akun? <a href="/register" class="text-primary text-decoration-none">Daftar di sini</a>
      </p>
      
    </div>
  </div>

  <!-- Bootstrap Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
