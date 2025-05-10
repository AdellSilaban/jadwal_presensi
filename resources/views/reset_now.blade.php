<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password</title>

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
        <small class="text-muted">Silakan reset password akunmu di bawah ini.</small>
      </div>

      <form action="/updatePassword" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
          <input type="password" id="password" name="password" class="form-control" placeholder="Password Baru" required>
        </div>

        <div class="mb-1">
          <input type="password" id="confirm_password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
        </div>

        <small class="text-muted">Pastikan password dan konfirmasi sama.</small>
        <div id="password_match_error" class="text-danger small mt-1" style="display: none;">
          Password tidak cocok.
        </div>

        <button type="submit" class="btn btn-login w-100 mt-3">
          <i class="fas fa-save me-2"></i> Simpan Password
        </button>
      </form>
    </div>
  </div>

  <!-- Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirm_password');
      const passwordMatchError = document.getElementById('password_match_error');

      const validateMatch = () => {
        if (passwordInput.value !== confirmPasswordInput.value && confirmPasswordInput.value !== '') {
          passwordMatchError.style.display = 'block';
        } else {
          passwordMatchError.style.display = 'none';
        }
      };

      passwordInput.addEventListener('keyup', validateMatch);
      confirmPasswordInput.addEventListener('keyup', validateMatch);
    });
  </script>

  <!-- Bootstrap Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
