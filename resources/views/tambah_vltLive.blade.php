
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="myModalLabel">Tambah Data Volunteer Baru</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="/simpanVltLive" method="POST">
                        @csrf
                        <div class="form-group">
                          <label for="nama">Nama</label>
                          <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama">
                        </div>

                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM">
                          </div>

                          <div class="form-group">
                            <label for="fakultas">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" placeholder="Masukkan Fakultas">
                          </div>
            
                          <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Masukkan Jurusan">
                          </div>

                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email">
                            <span id="email-availability"></span> </div>
                          </div>

                          <script>
                            $(document).ready(function() {
                                $('#email').on('blur', function() {
                                    var email = $(this).val();
                                    $('#email-availability').html('<i class="fas fa-spinner fa-spin"></i>'); // Tampilkan loading indicator
                            
                                    $.ajax({
                                        url: '/cek-email', // Ganti dengan route yang sesuai
                                        data: { email: email },
                                        success: function(response) {
                                            if (response.status == 'available') {
                                                $('#email-availability').html('<i class="fas fa-check-circle text-success"></i>');
                                            } else {
                                                $('#email-availability').html('<i class="fas fa-times-circle text-danger"></i>');
                                            }
                                        }
                                    });
                                });
                            });
                            </script>

                          <div class="form-group">
                            <label for="periode">Periode</label>
                            <input type="text" class="form-control" id="periode" name="periode" placeholder="Masukkan Periode">
                          </div>

                          <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <select class="form-control" id="divisi_id" name="divisi_id">
                              @foreach ($divisi as $div)
                                  @if ($div->nama_divisi === 'PKK Live')
                                      <option value="{{ $div->divisi_id }}" selected>{{ $div->nama_divisi }}</option>
                                  @endif
                              @endforeach
                          </select>
                        </div>
                        
                          <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" name="password" placeholder="Masukkan password">
                          </div>
                        
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                  </form>
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
