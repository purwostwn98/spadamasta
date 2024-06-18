<?= $this->extend("/template/theme.php"); ?>
<?= $this->section("konten"); ?>
<?php
$tahun_now = date('Y');
$tahun_min = $tahun_now - 8;
?>
<!--  Row 1 -->
<div class="row mt-3 mb-0 justify-align-between">
    <div class="col-7">
        <h5 class="font-weight-bold">Sinkronasi Data Mahasiswa</h5>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?= form_open("/master/do-import-mhs", ['class' => 'formmhs']);
        csrf_field();
        ?>
        <div class="form container">
            <label class="d-block"><strong>Pilih Angkatan</strong></label>
            <div class="form-check">
                <input class="form-check-input checkAll" onclick="checkAllAkt(this.checked);" type="checkbox" id="checkAllAngkatan" name="checkAllAngkatan" value="#">
                <label class="form-check-label" for="checkAllAngkatan">
                    <b> Pilih Semua Angkatan </b>
                </label>
            </div>

            <div class="row">
                <?php for ($i = $tahun_now; $i >= $tahun_min; $i--) { ?>
                    <div class="col-lg-4">
                        <div class="form-check">
                            <input class="form-check-input checkAngkatan" type="checkbox" id="<?= $i; ?>" name="pilihangkatan[]" value="<?= $i; ?>">
                            <label class="form-check-label" for="<?= $i; ?>">
                                Angkatan <?= $i; ?>
                            </label>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <br>
            <label class="d-block"><strong>Pilih Prgram Studi</strong></label>
            <div class="form-check">
                <input class="form-check-input" onclick="checkAllPrd(this.checked);" type="checkbox" id="checkAllProdi" name="checkAllProdi" value="#">
                <label class="form-check-label" for="checkAllProdi">
                    <b> Pilih Semua Program Studi</b>
                </label>
            </div>
            <div class="row">
                <?php foreach ($lembaga as $key => $value) { ?>
                    <div class="col-lg-6">
                        <div class="form-check">
                            <input class="form-check-input checkProdi" type="checkbox" value="<?= $value['kode_prodi']; ?>" id="<?= $value['kode_prodi']; ?>" name="programstudi[]">
                            <label class="form-check-label" for="<?= $value['kode_prodi']; ?>">
                                <?= $value['nama_prodi']; ?>
                            </label>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <br>
            <div class="row">
                <div class="col-12 content-align-center">
                    <button class="btn btn-warning btn-simpan" type="button">Import Data BTI</button>
                </div>
            </div>
        </div>
        <input type="hidden" class="csrf_pstwn" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        <?= form_close(); ?>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between mt-auto">
            <span class="fw-semibold fs-6 text-gray-400">Process Compleation</span> <span id="pbarsapadaspan" class="fw-bold fs-6">0%</span>
        </div>
    </div>
    <div class="col-md-12">
        <div class=" mx-3 w-100 bg-light mb-3" style="height:20px">
            <div class="bg-success rounded " role="progressbar" id="pbarsapada" style="width: 0%; height:20px" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="progress-import table table-striped table-responsive">
            <tr class="bg-success text-white">
                <th>No</th>
                <th>Program Studi</th>
                <th>Angkatan</th>
                <th>Jml. Simpan</th>
                <th>Jml. Diperbarui</th>
            </tr>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $(".btn-simpan").click(function() {
            $('#pbarsapadaspan').text('0%');
            $('#pbarsapada').css('width', '0%');
            $('#pbarsapada').attr('aria-valuenow', '0');

            var PRODI = Array();
            $('.checkProdi:checked').each(function() {
                PRODI.push($(this).val());
            });
            const ANGKATAN = [];
            $('.checkAngkatan:checked').each(function() {
                ANGKATAN.push($(this).val());
            });
            PRODI_AKT = [];
            PRODI.forEach(myProdi);

            function myProdi(prd, index) {
                for (let i = 0; i < ANGKATAN.length; i++) {
                    PRODI_AKT.push([prd, ANGKATAN[i]]);;
                }
            }
            proses_update()
        });

        var NO = 0;

        function proses_update() {
            var p = NO + 1;
            var persen = (p / PRODI_AKT.length) * 100;
            var csrfName = $('.csrf_pstwn').attr('name'); // CSRF Token name
            var csrfHash = $('.csrf_pstwn').val(); // CSRF hash
            if (NO < PRODI_AKT.length) {
                var prodi = PRODI_AKT[NO][0];
                var angkatan = PRODI_AKT[NO][1];
                $.ajax({
                    url: "<?= site_url('superadmin/do_sinkornasi_mahasiswa'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        prodi: prodi,
                        angkatan: angkatan,
                        [csrfName]: csrfHash
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('.btn-simpan').prop('disabled', true);
                        $('.btn-simpan').html('<i class="fa fa-spin fa-spinner"></i> Mohon tunggu ...');
                        // $('.tabelrekap').html('');
                    },
                    complete: function() {
                        // $('.btn-update').prop('disabled', false);
                        // $('.btn-update').html('<i class="fa fa-save text-white"></i> | Update CPL');
                    },
                    success: function(response) {
                        var txt = '<tr><td>' + (NO + 1) + '</td><td>' + response.berhasil.nama_prodi + '</td><td>' + response.berhasil.angkatan + '</td><td align=center>' + response.berhasil.jumlah_simpan + '</td><td align=center>' + response.berhasil.jumlah_update + '</td></tr>';
                        $('.progress-import').append(txt);
                        NO++;
                        $('.csrf_pstwn').val(response.token);
                        proses_update();
                        // update progress bar
                        $('#pbarsapadaspan').text(persen + '%');
                        $('#pbarsapada').css('width', persen + '%');
                        $('#pbarsapada').attr('aria-valuenow', persen);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
                return false;
            } else {
                console.log('selesai')
                $('.btn-simpan').prop('disabled', false);
                $('.btn-simpan').html('Improt Data BTI');
                // finish progress bar
                $('#pbarsapadaspan').text('100%');
                $('#pbarsapada').css('width', '100%');
                $('#pbarsapada').attr('aria-valuenow', '100');
            }
        }

    });
</script>
<script>
    function checkAllAkt(c) {
        $('.checkAngkatan').each(function() {
            $(this).prop('checked', c);
        });
    }

    function checkAllPrd(c) {
        $('.checkProdi').each(function() {
            $(this).prop('checked', c);
        });
    }
</script>




<?= $this->endSection(); ?>