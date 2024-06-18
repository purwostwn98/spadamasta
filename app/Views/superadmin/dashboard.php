<?= $this->extend("/template/theme.php"); ?>
<?= $this->section("konten"); ?>
<!--  Row 1 -->
<?php $session = \Config\Services::session(); ?>
<div class="media border p-3 align-items-center">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-auto justify-content-center bg-warning">
                    <div class="text-center"> <img src="<?= base_url(); ?>/assets/images/profile/user-1.jpg" alt="John Doe" class="mr-auto ml-auto mt-2 mb-2 rounded-circle" style="width:80px;"> </div>
                </div>
                <div class="col-md-7">
                    <div class="media-body mt-3">
                        <p class="mb-0">السَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ</p>
                        <h4><small>Selamat datang,</small> <b class="text-primary"><?= $session->get('userdata')['namauser']; ?></b></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {


    });
</script>

<script>
    function load_mahasiswa_angkatan(jenjang, prodi, tahun) {
        $.ajax({
            url: "<?= site_url('dinamis/load_mhs_angkatan'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                jenjang: jenjang,
                prodi: prodi,
                tahun: tahun
            },
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                $(".tbd").html(response.tr);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
</script>


<?= $this->endSection(); ?>