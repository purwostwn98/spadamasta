<div class="row">
    <div class="col-md-6">
        <h5>Dosen / Fasilitator Masta</h5>
    </div>
    <div class="col-md-6 text-right">
        <p><button class="btn btn-primary btn-sm" onclick="modalPanitia('3')"><i class="fa fa-add"></i>Tambah Dosen</button></p>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered" id="table-dosen">
            <thead class="bg-danger text-white">
                <th>No</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php foreach ($peserta as $key => $v) { ?>
                    <tr>
                        <td><?= $key + 1; ?></td>
                        <td><?= $v["username"]; ?></td>
                        <td><?= $v["nama_pengguna"]; ?></td>
                        <td><button class="btn btn-warning btn-sm"><i class="fa fa-trash"></i></button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>