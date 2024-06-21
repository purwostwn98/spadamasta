<div class="row">
    <div class="col-md-6">
        <h5>Panitia Masta</h5>
    </div>
    <div class="col-md-6 text-right">
        <p><button class="btn btn-primary btn-sm" onclick="modalPanitia('4', '<?= $keterangan; ?>')"><i class="fa fa-add"></i>Tambah <?= $keterangan == 'duta' ? 'Duta Masta' : 'Panitia'; ?></button></p>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered" id="table-panitia">
            <thead class="<?= $keterangan == 'duta' ? 'bg-warning' : 'bg-secondary'; ?> text-white">
                <th>No</th>
                <th>ID</th>
                <th>Nama</th>
                <?php if ($keterangan == "duta") { ?>
                    <th>Fakultas</th>
                <?php } ?>
                <th>Action</th>
            </thead>
            <tbody>
                <?php foreach ($peserta as $key => $v) { ?>
                    <tr>
                        <td><?= $key + 1; ?></td>
                        <td><?= $v["username"]; ?></td>
                        <td><?= $v["nama_pengguna"]; ?></td>
                        <?php if ($keterangan == "duta") { ?>
                            <td><?= $v["nama_fakultas"]; ?></td>
                        <?php } ?>
                        <td><button class="btn btn-warning btn-sm"><i class="fa fa-trash"></i></button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>