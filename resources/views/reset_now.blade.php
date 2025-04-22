<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Reset Password</title>

    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="../assets/css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Reset Password Account</h1>
                                    </div>
                                    <form action="/updatePassword" method="POST" class="user">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="form-group">
                                            <input type="password" id="password" name="password" class="form-control form-control-user" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="confirm_password" name="password_confirmation" placeholder="Konfirmasi Password Baru">
                                            <small id="passwordHelpBlock" class="form-text text-muted">
                                                Pastikan password yang Anda masukkan sama dengan konfirmasi password.
                                            </small>
                                            <div id="password_match_error" class="text-danger" style="display: none;">
                                                Password tidak cocok.
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" type="submit">Save</button>
                                        <hr>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="../assets/js/sb-admin-2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordMatchError = document.getElementById('password_match_error');

            confirmPasswordInput.addEventListener('keyup', function() {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    passwordMatchError.style.display = 'block';
                } else {
                    passwordMatchError.style.display = 'none';
                }
            });

            // Optional: Tambahkan event listener untuk password input jika ingin validasi real-time
            passwordInput.addEventListener('keyup', function() {
                if (passwordInput.value !== confirmPasswordInput.value && confirmPasswordInput.value !== '') {
                    passwordMatchError.style.display = 'block';
                } else {
                    passwordMatchError.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>