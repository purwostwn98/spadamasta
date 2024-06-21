<?= $this->extend("/template/theme.php"); ?>
<?= $this->section("konten"); ?>
<!--  Row 1 -->
<?php $session = \Config\Services::session(); ?>

<div class=" p-3 align-items-center">
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-auto justify-content-center" style="background-color: #fec14f;">
                    <div class="text-center"> <img src="<?= base_url(); ?>/assets/images/profile/user-1.jpg" alt="John Doe" class="mr-auto ml-auto mt-2 mb-2 rounded-circle" style="width:80px;"> </div>
                </div>
                <div class="col-md-7">
                    <div class="media-body mt-3">
                        <p class="mb-0">السَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ</p>
                        <h4><small>Selamat datang,</small> <b class="text-primary"><?= $session->get('userdata')['namauser']; ?></b></h4>
                    </div>
                </div>
            </div>
            <?php if (empty($course)) { ?>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <p>Anda belum terdaftar sebagai peserta masta</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- <hr style="background-color: #fec14f; height: 5px; border: none;"> -->
<div class="row border p-3">
    <div class="col-md-12">
        <?php if (!empty($course)) { ?>
            <?php foreach ($course as $key => $v) { ?>
                <div class="row">
                    <div class="col-md-6">
                        <a target="_blank" href="/mahasiswa/open-masta?id=<?= $v["id"]; ?>" class="btn btn-primary w-100"> Buka <i>Course</i> <?= $v["judul_masta"]; ?></a>
                        <a target="_blank" href="/mahasiswa/open-masta?id=<?= $v["id"]; ?>" class="btn text-primary w-100 mt-2" style="background-color: #fec14f;"> <i class="fa fa-print"></i> Cetak Sertifikat <?= $v["judul_masta"]; ?></a>
                    </div>
                    <?php if ($key == 0) { ?>
                        <div class="col-md-6 justify-content-center">
                            <figure style="width: 100%;">
                                <div id="container"></div>
                            </figure>
                        </div>
                    <?php  } ?>
                </div>
            <?php } ?>
        <?php  } else { ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <p>-</p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    $(document).ready(function() {


    });
</script>

<script>
    Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Progress Masta'
        },
        tooltip: {
            valueSuffix: '%'
        },
        subtitle: {
            text: '<?= strtoupper($session->get("userdata")["iduser"]); ?>'
        },
        plotOptions: {
            series: {
                colors: ['#2f3185', '#fec14f'],
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: [{
                    enabled: true,
                    distance: 20
                }, {
                    enabled: true,
                    distance: -40,
                    format: '{point.percentage:.1f}%',
                    style: {
                        fontSize: '1.2em',
                        textOutline: 'none',
                        opacity: 0.7
                    },
                    filter: {
                        operator: '>',
                        property: 'percentage',
                        value: 10
                    }
                }]
            }
        },
        series: [{
            name: 'Percentage',
            colorByPoint: true,
            data: [{
                    name: 'Progress',
                    sliced: false,
                    selected: false,
                    y: 80
                },
                {
                    name: '-',
                    y: 20
                },
            ]
        }]
    });
</script>


<?= $this->endSection(); ?>