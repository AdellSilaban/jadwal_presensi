<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Volunteer</title>

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

      <form method="POST" action="/cekloginVol">
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

      <p class="text-center mt-4 small-text">
        Jika anda lupa password, bisa datangi koordinator divisi<br>untuk reset password.
      </p>
    </div>
  </div>

  <!-- Bootstrap Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
