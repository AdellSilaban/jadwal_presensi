<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>

    <!-- Fonts & Styles -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="assets/css/sb-admin-2.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="assets/img/login.jpg" class="img-fluid" alt="Login Image">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Registrasi</h1>
                                    </div>

                                    {{-- FORM REGISTRASI --}}
                                    <form action="/simpanRegis" method="POST" class="user">
                                        @csrf

                                        <div class="form-group">
                                            <input type="text" name="nama" class="form-control form-control-user" placeholder="Nama Lengkap" required>
                                        </div>

                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user" placeholder="Alamat Email" required>
                                        </div>

                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" placeholder="Password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="jabatan">Jabatan</label>
                                            <select id="jabatan" name="jabatan" class="form-control" onchange="toggleDivisi()" required>
                                                <option value="" disabled selected>Pilih Jabatan</option>
                                                <option value="Koordinator Divisi PKK Live">Koordinator Divisi PKK Live</option>
                                                <option value="Koordinator Divisi Tim Ibadah_Kampus">Koordinator Divisi Tim Ibadah Kampus</option>
                                                <option value="Koordinator Divisi Konseling">Koordinator Divisi Konseling</option>
                                                <option value="Koordinator Divisi Creative">Koordinator Divisi Creative</option>
                                                <option value="Kepala LPKKSK">Kepala LPKKSK</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="divisiWrapper">
                                            <label for="divisi_id">Divisi</label>
                                            <select name="divisi_id" id="divisi_id" class="form-control">
                                                <option value="" disabled selected>Pilih Divisi</option>
                                                @foreach ($divisi as $div)
                                                    <option value="{{ $div->divisi_id }}">{{ $div->nama_divisi }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        

                                        <button class="btn btn-primary btn-user btn-block" type="submit">Daftar</button>
                                    </form>

                                    @if ($errors->any())
                                        <div class="alert alert-danger mt-3">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.js"></script>

    <script>
        function toggleDivisi() {
            const jabatan = document.getElementById("jabatan").value;
            const divisiWrapper = document.getElementById("divisiWrapper");
            const divisiSelect = document.getElementById("divisi_id");
    
            if (jabatan === "Kepala LPKKSK") {
                divisiWrapper.style.display = "none";
                divisiSelect.removeAttribute("name"); // agar tidak dikirim ke backend
            } else {
                divisiWrapper.style.display = "block";
                divisiSelect.setAttribute("name", "divisi_id");
            }
        }
    
        window.onload = toggleDivisi;
    </script>
    
</body>

</html>
