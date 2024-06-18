<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="selProdi" class="text-primary"><b>Program Studi:</b></label>
            <select class="form-control select2" id="selProdi" onchange="gantiProdi()">
                <?php foreach ($prodi as $p => $pr) { ?>
                    <option <?= $pr["idlembaga_prodi"] == $idlembaga ? 'selected' : ''; ?> value="<?= $pr["idlembaga_prodi"]; ?>"><?= $pr["nama_prodi"]; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 justify-content-right">
        <p class="text-right">
            <a target="_blank" href="/superadmin/download_excel_peserta?tahunmasta=<?= $tahun_masta; ?>&idlembaga=<?= $idlembaga; ?>&idmasta=<?= $idmasta; ?>" class="btn btn-sm btn-success">Template Excel</a>
            <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#exampleModal">Unggah Excel</button>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-striped table-bordered" id="table-peserta">
            <thead class="bg-primary text-white">
                <th>No</th>
                <th>NIM</th>
                <th>Nama Peserta</th>
                <th>Angkatan</th>
                <th>Prodi</th>
                <th>Progress</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php foreach ($peserta as $key => $v) { ?>
                    <tr>
                        <td><?= $key + 1; ?></td>
                        <td><?= strtoupper($v["nim"]); ?></td>
                        <td><?= $v["nama_mahasiswa"]; ?></td>
                        <td><?= $v["angkatan"]; ?></td>
                        <td><?= $v["nama_prodi"]; ?></td>
                        <td><span class="badge badge-danger">0%</span></td>
                        <td><button class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>