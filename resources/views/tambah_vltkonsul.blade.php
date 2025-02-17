
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Tambah Data Volunteer Divisi Konseling</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" class="form-control" id="nama" placeholder="Masukkan nama">
            </div>

            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text" class="form-control" id="nim" placeholder="Masukkan NIM">
              </div>

              <div class="form-group">
                <label for="fakultas">Fakultas</label>
                <input type="text" class="form-control" id="fakultas" placeholder="Masukkan Fakultas">
              </div>

              <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <input type="text" class="form-control" id="fakultas" placeholder="Masukkan Jurusan">
              </div>

              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Masukkan Email">
              </div>

              <div class="form-group">
                <label for="periode">Periode</label>
                <input type="text" class="form-control" id="periode" placeholder="Masukkan Periode">
              </div>

              <div class="form-group">
                <label for="divisi">Divisi</label>
                <select class="form-control" id="divisi">
                    <option value="divisi1">Divisi 1</option>
                    <option value="divisi2">Divisi 2</option>
                    <option value="divisi3">Divisi 3</option>
                    </select>
            </div>

              <div class="form-group">
                <label for="pass_vlt">Password</label>
                <input type="text" class="form-control" id="pass_vlt" placeholder="Masukkan password">
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
        $('#email').on('blur', function() {
            var email = $(this).val();
            if (!validateEmail(email)) {
                // Tampilkan pesan error
                $('#emailHelp').text('Alamat email tidak valid.');
            } else {
                // Kirim permintaan AJAX ke server untuk validasi lebih lanjut
                $.ajax({
                    url: '/cek-email',
                    data: { email: email },
                    success: function(response) {
                        if (response.status == 'error') {
                            $('#emailHelp').text(response.message);
                        }
                    }
                });
            }
        });
    });
</script>
