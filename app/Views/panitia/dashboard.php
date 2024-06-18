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
            <?php if (!empty($course)) { ?>
                <?php foreach ($course as $key => $v) { ?>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a target="_blank" href="/mahasiswa/open-masta?id=<?= $v["id"]; ?>" class="btn btn-primary w-100"> Buka <i>Course</i> <?= $v["judul_masta"]; ?></a>
                        </div>
                    </div>
                <?php } ?>
            <?php  } else { ?>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <p>Anda belum terdaftar sebagai peserta masta</p>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script> -->
<script>
    $(document).ready(function() {


    });
</script>


<?= $this->endSection(); ?>