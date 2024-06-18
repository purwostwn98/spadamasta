<?= $this->extend("/template/theme.php"); ?>
<?= $this->section("konten"); ?>
<!--  Row 1 -->
<div class="row mt-3 mb-0 justify-align-between">
    <div class="col-7">
        <h5 class="font-weight-bold">Master Mahasiswa</h5>
    </div>
    <div class="col-5">
        <p class="text-center"><a href="/superadmin/master-mahasiwa/sinkronasi" class="btn btn-primary"><i class="fa fa-cloud-download"></i> | Sinkronasi Mahasiswa</a></p>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="selProdi"><b>Program Studi:</b></label>
            <select class="form-control select2" id="selProdi">
                <option value="AL">Alabama</option>
                <option value="WY">Wyoming</option>
                <!-- Add more options as needed -->
            </select>
        </div>
    </div>
    <div class="col">
        <div class="form-group">
            <label for="selAngkatan"><b>Angkatan:</b></label>
            <select class="form-control select2" id="selAngkatan">
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <!-- Add more options as needed -->
            </select>
        </div>
    </div>

</div>
<div class="row mt-3">
    <div class="col-12 table-responsive">
        <table id="example" class="table-striped" style="width: 100%;">
            <thead class="bg-primary text-white text-center">
                <tr>
                    <th class="pt-3">#</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Angkatan</th>
                    <th>Prodi</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="px-2">D600240001</td>
                    <td class="px-2">Purwo Setiawan</td>
                    <td class="px-2">2024</td>
                    <td class="px-2">Program Studi Teknik Industri</td>
                    <td class="text-center py-1">
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="px-2">D600240002</td>
                    <td class="px-2">Cahya Setiani</td>
                    <td class="px-2">2024</td>
                    <td class="px-2">Program Studi Teknik Industri</td>
                    <td class="text-center py-1">
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td class="px-2">D600240003</td>
                    <td class="px-2">Lorem ipsum dolor sit amet consectetur adipisicing</td>
                    <td class="px-2">2024</td>
                    <td class="px-2">Program Studi Teknik Industri</td>
                    <td class="text-center py-1">
                        <button class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#selProdi').select2();
        $('#selAngkatan').select2();
        $('#example').DataTable();
    });
</script>


<?= $this->endSection(); ?>