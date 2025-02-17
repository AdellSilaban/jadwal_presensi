
<div class="modal fade" id="editJadwal" tabindex="-1" role="dialog" aria-labelledby="editJadwalModel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editJadwalModel">Edit Jadwal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="tgl_jadwal">Tanggal</label>
              <input type="date" class="form-control" id="tgl_jadwal">
            </div>

            <div class="form-group">
                <label for="agenda">Agenda</label>
                <input type="text" class="form-control" id="agenda" placeholder="Masukkan Agenda">
              </div>

              <div class="form-group">
                <label for="petugas">Petugas</label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="petugas1">
                  <label class="form-check-label" for="petugas1">Adel</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="petugas2">
                  <label class="form-check-label" for="petugas2">Budi</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="petugas3">
                  <label class="form-check-label" for="petugas3">Cici</label>
                </div>
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