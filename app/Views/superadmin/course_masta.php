<?= $this->extend("/template/theme.php"); ?>
<?= $this->section("konten"); ?>
<!--  Row 1 -->
<div class="row my-3 d-flex justify-content-between">
    <div class="col-md-7">
        <h5 class="font-weight-bold">Daftar <i>Course</i> Masta</h5>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap"><i class="fa fa-calendar-plus-o"></i> | Tambah <i>Course</i> Masta</button>
    </div>
</div>
<div class="row">
    <div class="col-12 table-responsive">
        <table id="example" class="table table-striped table-bordered">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th class="text-center" rowspan="2">#</th>
                    <th class="text-center" rowspan="2">Nama</th>
                    <th class="text-center" rowspan="2">Tahun</th>
                    <th colspan="2" class="text-center">Peserta</th>
                    <th class="text-center" rowspan="2">Action</th>
                </tr>
                <tr>
                    <th class="text-center">Semua Peserta</th>
                    <th class="text-center"><i>Joined Course</i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($course as $key => $v) { ?>
                    <tr>
                        <td class="text-center"><?= $key + 1; ?></td>
                        <td><?= $v['nama']; ?></td>
                        <td class="text-center"><?= $v['tahun']; ?></td>
                        <td class="text-center"><?= $v["semuapeserta"]; ?></td>
                        <td class="text-center"><?= $v["joinedpeserta"]; ?></td>
                        <td>
                            <a href="/superadmin/open-masta?id=<?= $v["id"]; ?>" class="btn btn-sm btn-primary" target="_blank">Buka Course</a> |
                            <a href="/superadmin/setting-masta?id=<?= $v["id"]; ?>" class="btn btn-sm btn-warning"><i class="fa fa-cog"></i></a> |
                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Large Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="exampleModalLabel">Buat <i>Course</i> Masta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/superadmin/do-create-course" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Nama / Judul Masta</label>
                        <input type="text" class="form-control" id="recipient-name" name="judul_masta" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-tahun" class="col-form-label">Tahun Masta</label>
                        <input type="number" class="form-control" id="recipient-tahun" name="tahun_masta" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-tgl_mulai" class="col-form-label">Tanggal Mulai</label>
                        <input type="datetime-local" class="form-control" id="recipient-tgl_mulai" name="tgl_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-tgl_selesai" class="col-form-label">Tanggal Selesai</label>
                        <input type="datetime-local" class="form-control" id="recipient-tgl_selesai" name="tgl_selesai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submitMasta">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>


<?= $this->endSection(); ?>