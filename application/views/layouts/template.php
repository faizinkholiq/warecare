<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ? $title . " | WareCare" : "WareCare" ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/daterangepicker/daterangepicker.css') ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-select/css/select.bootstrap4.min.css') ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
  <!-- Template -->
  <link rel="stylesheet" href="<?= base_url('assets/modules/template/template.css') ?>">
  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- jQuery -->
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- daterangepicker -->
  <script src="<?= base_url('assets/plugins/moment/moment.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="<?= base_url('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('assets/dist/js/adminlte.js') ?>"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/jszip/jszip.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-select/js/dataTables.select.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/datatables-select/js/select.bootstrap4.min.js') ?>"></script>
  <!-- Select2 -->
  <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
  <!-- jquery-validation -->
  <script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>
  <!-- Template -->
  <script src="<?= base_url('assets/modules/template/template.js') ?>"></script>
  <!-- Toastr -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Additional -->
  <?php
  if (!empty($js_files)) {
    foreach ($js_files as $js) {
      echo "<script type='text/javascript' src='$js'></script>";
    }
  }
  ?>
  <style>
    .input-group .select2-container--bootstrap4 {
      width: auto !important;
      flex: 1 1 auto;
    }

    .input-group>.input-group-prepend .select2-container--bootstrap4 .select2-selection--single {
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }

    .input-group>.input-group-append .select2-container--bootstrap4 .select2-selection--single {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <i class="fas fa-circle-notch fa-spin" style="font-size: 50px;"></i>
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <div class="media align-items-center p-3">
              <img src="<?= base_url('assets/dist/img/user1-128x128.jpg') ?>" alt="User Avatar" class="img-size-32 mr-3 img-circle">
              <div class="media-body">
                <b style="font-size: 1.25rem;"><?=$current_user["first_name"].' '.$current_user["last_name"]?></b>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <a href="<?=site_url('login/logout')?>" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= site_url('home') ?>" class="brand-link logo-switch">
        <span class="brand-text logo-xs font-weight-semibold" style="left: 42px;">W</span>
        <span class="brand-text logo-xl font-weight-semibold" style="left: 40px;">WareCare</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="<?= site_url('home') ?>" id="menu_dashboard" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>Home</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('report') ?>" id="menu_report" class="nav-link">
                <i class="nav-icon fas fa-bullhorn"></i>
                <p>Pengaduan</p>
              </a>
            </li>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-wrench"></i>
                <p>
                  Master
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview" style="display: block;">
                <li class="nav-item">
                  <a href="<?= site_url('company') ?>" id="menu_company" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>Perusahaan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= site_url('warehouse') ?>" id="menu_warehouse" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>Gudang</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= site_url('project') ?>" id="menu_project" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>Project</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= site_url('entity') ?>" id="menu_entity" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>Entity</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= site_url('category') ?>" id="menu_category" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>Kategori</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content p-3" style="height: 100%;">
        <?php
        if ($view) {
          $this->load->view($view);
        }
        ?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer text-center">
      <strong>Copyright &copy; Akatsuki International</strong>
      All rights reserved.
    </footer>
    <!-- /.footer -->

    <!-- Prompt -->
    <div class="modal fade" id="modal_prompt">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">New Prompt</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Prompt description...</p>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="modal_prompt_submit" class="btn btn-primary">Ok</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.prompt -->
  </div>
</body>

<script>
  const menuId = '<?php echo $menu_id; ?>';
  const modalPrompt = $("#modal_prompt");

  $(document).ready(function() {
      $('.select2').select2({
          theme: 'bootstrap4',
      });

      $('.date-input').datetimepicker({
          format: "YYYY-MM-DD",
          useStric: true
      });

      $('#menu_'+menuId).addClass('active');

      toastr.options = {
          positionClass: "toast-top-right",
          progressBar: true,
          closeButton: true,
          preventDuplicates: true, 
          timeOut: 3000,
      };
  });
</script>

</html>