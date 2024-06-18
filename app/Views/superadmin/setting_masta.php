<?= $this->extend("/template/theme.php"); ?>
<?= $this->section("konten"); ?>
<!--  Row 1 -->
<input type="hidden" name="id" id="idmastacourse" value="<?= $course["id"]; ?>">
<div class="row">
    <div class="col-md-8">
        <table class="table" style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 30%;">Nama</td>
                    <td style="width: 5%;">:</td>
                    <td class="text-primary"><b><?= $course["judul_masta"]; ?></b></td>
                </tr>
                <tr>
                    <td>Tahun Masta</td>
                    <td>:</td>
                    <td><?= $course["tahun_masta"]; ?></td>
                </tr>
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>:</td>
                    <td><?= $tgl_mulai; ?></td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>:</td>
                    <td><?= $tgl_selesai; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="card">
        <div class="card-title mt-2">
            <div class="row">
                <div class="col-md-5">
                    <button class="btn btn-sm btn-primary" onclick="loadPeserta('<?= $course['id']; ?>', 'lmbg1023')">Peserta</button> |
                    <button class="btn btn-sm btn-warning" onclick="loadPanitia('<?= $course['id']; ?>')">Panitia</button> |
                    <button class="btn btn-sm btn-danger" onclick="loadDosen('<?= $course['id']; ?>')">Dosen / Fasilitator</button>
                </div>
            </div>
        </div>
        <div class="card-body" id="tabelbd">
            <table class="table table-striped table-bordered">
                <thead>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Peserta</th>
                    <th>Angkatan</th>
                    <th>Prodi</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6">Pilih tombol filter</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal unggah peserta -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= form_open_multipart("", ['class' => 'formUnggahExcel']); ?>
            <?= csrf_field(); ?>
            <!-- <input type="hidden" value="" class="prodi" name="idmk">
            <input type="hidden" value="" class="p" name="periode">
            <input type="hidden" value="" name="dsn"> -->
            <div class="modal-body bdmdl">
                <p>Pilih file excel</p>
                <input type="file" class="form-control" name="file_excel">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btnAjukan">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- modal tambah panitia -->
<div class="mdpan"></div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('.formUnggahExcel').submit(function(e) {
            e.preventDefault();
            let form = $('.formUnggahExcel')[0];
            let data = new FormData(form);
            $.ajax({
                type: "post",
                url: "<?= site_url('superadmin/unggah-peserta-excel'); ?>",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    $('.bdmdl').html('<p><i class="fa fa-spin fa-spinner"></i> Proses mengunggah file...</p>');
                    $('.btnAjukan').prop('disabled', true);
                    $('.btnAjukan').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {

                },
                success: function(response) {
                    if (response.success == true) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: response.jmlsimpan + " peserta disimpan",
                            position: 'topRight'
                        });
                        $('.btnAjukan').prop('disabled', false);
                        $('.btnAjukan').html("Simpan");
                        $('.bdmdl').html(`<p>Pilih file excel</p><input type="file" class="form-control flupload" name="file_excel">`);
                        $("input[name='csrf_test_name']").val(response.token);
                        $("#exampleModal").modal('hide');
                        var idlembaga = $("#selProdi").val();
                        var id = $("#idmastacourse").val();
                        loadPeserta(id, idlembaga);
                    } else {
                        iziToast.error({
                            title: 'Gagal!',
                            message: response.pesan,
                            position: 'topRight'
                        });
                        $('.btnAjukan').prop('disabled', false);
                        $('.btnAjukan').html("Simpan");
                        $('.bdmdl').html(`<p>Pilih file excel</p><input type="file" class="form-control flupload" name="file_excel">`);
                        $("input[name='csrf_test_name']").val(response.token);
                        $("#exampleModal").modal('hide');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
            return false;
        });

    });
</script>

<script>
    function loadPeserta(id, idlembaga) {
        $.ajax({
            url: "<?= site_url('superadmin/dinamis/load_setting_peserta'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                idlembaga: idlembaga
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                $("#tabelbd").html(response.tabel);
                $("#table-peserta").DataTable();
                $('#selProdi').select2();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function loadPanitia(id) {
        $.ajax({
            url: "<?= site_url('superadmin/dinamis/load_setting_panitia'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                $("#tabelbd").html(response.tabel);
                $("#table-panitia").DataTable();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function loadDosen(id) {
        $.ajax({
            url: "<?= site_url('superadmin/dinamis/load_setting_dosen'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                $("#tabelbd").html(response.tabel);
                $("#table-dosen").DataTable();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
</script>

<script>
    function modalPanitia(role) {
        $.ajax({
            url: "<?= site_url('superadmin/dinamis/modal_cari_panitia'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                role: role
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                $(".mdpan").html(response.modal);
                $("#modalTambahPanitia").modal("show");
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function btnCloseModal() {
        $(".modal").modal("hide");
    }

    function handleSearch() {
        var key = $(".inputcari").val();
        var role = $(".role").val();
        $.ajax({
            url: "<?= site_url('superadmin/dinamis/hasil_cari_panitia'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                key: key,
                role: role
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                $(".bdcari").html(response.tr);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function makePanitia(params) {
        var id = $("#idmastacourse").val();
        var csrfName = 'csrf_test_name'; // CSRF Token name
        var csrfHash = $("input[name='csrf_test_name']").val(); // CSRF hash
        $.ajax({
            url: "<?= site_url('superadmin/do_jadikan_panitia'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                params: params,
                idmasta: id,
                [csrfName]: csrfHash
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                if (response.status == true) {
                    iziToast.success({
                        title: 'Berhasil!',
                        message: response.pesan,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        title: 'Gagal!',
                        message: response.pesan,
                        position: 'topRight'
                    });
                }

                if (response.role == 4) {
                    loadPanitia(id)
                } else {
                    loadDosen(id)
                }
                $("input[name='csrf_test_name']").val(response.token);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
</script>

<script>
    function gantiProdi() {
        var idlembaga = $("#selProdi").val();
        var id = $("#idmastacourse").val();
        loadPeserta(id, idlembaga);
    }
</script>


<?= $this->endSection(); ?>