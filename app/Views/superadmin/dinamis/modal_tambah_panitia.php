<!-- digunakan untuk dosen juga -->
<!-- Modal unggah panitia -->
<div class="modal fade" id="modalTambahPanitia" tabindex="-1" role="dialog" aria-labelledby="modalTambahPanitiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <input type="hidden" value="<?= $role; ?>" class="role" name="role">
            <input type="hidden" value="<?= $keterangan; ?>" class="keterangan" name="keterangan">

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4>Tambah <?= $role == 4 ? 'Panitia' : 'Dosen'; ?></h4>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex">
                            <input class="form-control me-2 inputcari" type="search" placeholder="Cari dosen berdasarkan uniid / nama" aria-label="Search">
                            <button class="btn btn-outline-success" type="button" onclick="handleSearch()">Search</button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="tabel-cari">
                    <thead class="bg-info text-white">
                        <tr>
                            <th>No</th>
                            <th>Uniid</th>
                            <th>Nama</th>
                            <th><i>Action</i></th>
                        </tr>
                    </thead>
                    <tbody class="bdcari">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="btnCloseModal()">Close</button>
                <button type="submit" class="btn btn-primary btnAjukan">Simpan</button>
            </div>
        </div>
    </div>
</div>