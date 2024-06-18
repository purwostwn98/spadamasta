<!doctype html>
<html lang="en">
<?php
$session = \Config\Services::session();
$level = $session->get("userdata")["level"];
?>

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?></title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>/assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/styles.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url(); ?>/node_modules/izitoast/dist/css/iziToast.min.css">
  <style>
    .highcharts-figure,
    .highcharts-data-table table {
      min-width: 320px;
      max-width: 800px;
      margin: 1em auto;
    }

    .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #ebebeb;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
    }

    .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
    }

    .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
      padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
      background: #f1f7ff;
    }

    input[type="number"] {
      min-width: 50px;
    }
  </style>
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="<?= base_url(); ?>/assets/images/logos/logo_ums_pusat.png" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu"><?= $session->get("userdata")["level"]; ?></span>
            </li>
            <?php if ($level == "mahasiswa") { ?>
              <li class="sidebar-item">
                <a class="sidebar-link <?= $sidebar[1] == 'dashboard' ? 'active' : ''; ?>" href="/mahasiswa/dashboard" aria-expanded="false">
                  <span>
                    <i class="fa fa-slack"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
            <?php } elseif ($level == "panitia") { ?>
              <li class="sidebar-item">
                <a class="sidebar-link <?= $sidebar[1] == 'dashboard' ? 'active' : ''; ?>" href="/panitia/dashboard" aria-expanded="false">
                  <span>
                    <i class="fa fa-slack"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
            <?php } elseif ($level == "superadmin") { ?>
              <li class="sidebar-item">
                <a class="sidebar-link <?= $title == 'Masta UMS | Superadmin - Dashboard' ? 'active' : ''; ?>" href="/superadmin/dashboard" aria-expanded="false">
                  <span>
                    <i class="fa fa-slack"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link <?= $sidebar[1] == 'course-masta' ? 'active' : ''; ?>" href="/superadmin/course-masta" aria-expanded="false">
                  <span>
                    <i class="fa fa-forward"></i>
                  </span>
                  <span class="hide-menu"><i>Course</i> Masta</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link <?= $sidebar[1] == 'master-mahasiswa' ? 'active' : ''; ?>" href="/superadmin/master-mahasiswa" aria-expanded="false">
                  <span>
                    <i class="fa fa-users"></i>
                  </span>
                  <span class="hide-menu">Master Mahasiswa</span>
                </a>
              </li>
            <?php } ?>

          </ul>
          <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">SPADA Masta</h6>
                <a href="https://ums.ac.id/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">ums.ac.id</a>
              </div>
              <div class="unlimited-access-img">
                <img src="<?= base_url(); ?>/assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
              </div>
            </div>
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header bg-primary">
        <nav class="navbar navbar-expand-lg navbar-dark ">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <!-- <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a> -->
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <a href="#" class="btn btn-primary">Hi, <?= $session->get("userdata")["namauser"]; ?></a>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="<?= base_url(); ?>/assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="/logout" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">

        <!-- //isiii -->
        <?= $this->renderSection("konten"); ?>

        <!-- <footer class="footer mt-auto py-2 d-flex bg-dark">
          <div class="container text-center">
            <span class="text-white">&copy; 2024 Spada Masta</span>
          </div>
        </footer> -->
      </div>
    </div>
  </div>
  <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <!-- <script src="<?= base_url(); ?>/assets/libs/jquery/dist/jquery.min.js"></script> -->
  <script src="<?= base_url(); ?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>/assets/js/sidebarmenu.js"></script>
  <script src="<?= base_url(); ?>/assets/js/app.min.js"></script>
  <script src="<?= base_url(); ?>/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="<?= base_url(); ?>/assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="<?= base_url(); ?>/assets/js/dashboard.js"></script>
  <script src="https://use.fontawesome.com/d241ccdcfe.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script src="<?= base_url(); ?>/node_modules/izitoast/dist/js/iziToast.min.js" type="text/javascript"></script>

</body>

</html>