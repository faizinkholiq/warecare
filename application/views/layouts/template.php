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
    <!-- Tippy.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css" />

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
    <!-- Tippy.js Script -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
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

        .bg-light-primary {
            background-color: #007bff17 !important;
        }

        .bg-light-success {
            background-color: #28a74530 !important;
        }

        .bg-light-warning {
            background-color: #ffc1071a !important;
        }

        .bg-light-danger {
            background-color: #dc354533 !important;
        }

        .table>thead>tr>th {
            border-top: none;
            border-bottom: 1px solid #dee2e6a8;
        }

        .btn-default {
            background-color: #fff;
        }

        .btn-default:hover {
            background-color: #f1f1f1ff;
        }

        .nav-link.active {
            background-color: #001f3f !important;
            color: #fff !important;
        }

        .dropdown-menu-md {
            max-width: 15rem;
            min-width: 12rem;
            padding-top: 0;
        }

        .content-wrapper {
            height: auto !important;
        }

        .content-wrapper>.content {
            padding: 0 !important;
        }

        .my-container {
            padding: 1rem;
        }

        .text-navy {
            color: #001f3f !important;
        }

        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            display: flex;
        }

        .progress-container.show {
            opacity: 1;
            visibility: visible;
        }

        .progress-container .progress {
            width: 20%;
            height: 2rem;
            border-radius: 1rem;
        }

        .progress-container .progress .progress-bar {
            border-radius: 1rem;
        }

        .fade-in {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <i class="fas fa-circle-notch fa-spin" style="font-size: 50px;"></i>
        </div>

        <!-- Progress Loader -->
        <div id="progressContainer" class="progress-container flex-column justify-content-center align-items-center">
            <div class="progress">
                <div class="progress-bar bg-navy"
                    id="formProgressBar"
                    role="progressbar"
                    style="width: 0%">
                </div>
            </div>
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
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right border-0 shadow">
                        <div class="media align-items-center pt-3 pr-3 pb-1 pl-3">
                            <img src="<?= base_url('assets/dist/img/anon-user.webp') ?>" alt="User Avatar" class="img-size-32 mr-3 img-circle">
                            <div class="media-body">
                                <b style="font-size: 1rem;"><?= $current_user["first_name"] . ' ' . $current_user["last_name"] ?></b>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?= site_url('auth/logout') ?>" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a style="height: 5rem;" href="<?= site_url('dashboard') ?>" class="brand-link logo-switch d-flex align-items-center justify-content-center">
                <span class="brand-text logo-xs ml-2">
                    <img style="height:1.5rem;" src="<?= base_url('assets/images/logo-small.png') ?>" alt="waringin_logo">
                </span>
                <span class="brand-text logo-xl d-flex align-items-center">
                    <img style="height:1.5rem;" src="<?= base_url('assets/images/logo-small.png') ?>" alt="waringin_logo">
                    <div class="font-weight-bold ml-2 text-lg mt-2">Waringin Group</div>
                </span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar" style="margin-top: calc(5rem + 1px);">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?= site_url('dashboard') ?>" id="menu_dashboard" class="nav-link">
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
                        <?php if ($this->auth_lib->role() === 'administrator'): ?>
                            <li class="nav-item">
                                <a href="<?= site_url('user') ?>" id="menu_user" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Manajemen User</p>
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
                                        <a href="<?= site_url('entity') ?>" id="menu_entity" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Entity</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= site_url('project') ?>" id="menu_project" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>Project</p>
                                        </a>
                                    </li>
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
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background: whitesmoke;">
            <!-- Main content -->
            <section class="content" style="height: 100%;">
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
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <strong>Copyright &copy; <?= date('Y') ?> Waringin Group</strong>
                        All rights reserved.
                    </div>
                </div>
            </div>
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
    const MAX_VALUE = 9999999999;

    const capitalizeFirst = str => str ? str.charAt(0).toUpperCase() + str.slice(1).toLowerCase() : '';

    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
        });

        $('.date-input').datetimepicker({
            format: "YYYY-MM-DD",
            useStric: true
        });

        $('#menu_' + menuId).addClass('active');

        toastr.options = {
            positionClass: "toast-top-right",
            progressBar: true,
            closeButton: true,
            preventDuplicates: true,
            timeOut: 3000,
        };

        tippy('[data-tippy-content]', {
            placement: 'bottom',
            arrow: true,
            animation: 'fade'
        });

        $('.input-currency').on('input', function(e) {
            // Get cursor position before any changes
            let cursorPosition = this.selectionStart;
            let originalValue = $(this).val();

            // Remove all non-digit characters
            let numericValue = originalValue.replace(/[^\d]/g, '');

            // If empty, set to 0
            if (numericValue === '') {
                $(this).val('0');
                return;
            }

            // Convert to number and validate range
            let numberValue = parseInt(numericValue, 10);

            // Enforce maximum value
            if (numberValue > MAX_VALUE) {
                numberValue = MAX_VALUE;
                numericValue = MAX_VALUE.toString();
            }

            // Format with thousand separators
            let formattedValue = numberValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            // Update the input value
            $(this).val(formattedValue);

            // Adjust cursor position
            let changeInLength = formattedValue.length - originalValue.length;
            this.setSelectionRange(
                Math.max(0, cursorPosition + changeInLength),
                Math.max(0, cursorPosition + changeInLength)
            );
        });

        // Validate on blur
        $('.input-currency').on('blur', function() {
            let numericValue = $(this).val().replace(/[^\d]/g, '');
            if (numericValue === '') {
                $(this).val('0');
            }
        });

        $('.input-number').on('input', function(e) {
            // Get cursor position before any changes
            let cursorPosition = this.selectionStart;
            let originalValue = $(this).val();

            // Remove all non-digit characters
            let numericValue = originalValue.replace(/[^\d]/g, '');

            // If empty, set to 0
            if (numericValue === '') {
                $(this).val('0');
                return;
            }

            // Convert to number and validate range
            let numberValue = parseInt(numericValue, 10);

            // Enforce maximum value
            if (numberValue > MAX_VALUE) {
                numberValue = MAX_VALUE;
                numericValue = MAX_VALUE.toString();
            }

            // Update the input value
            $(this).val(numberValue);
        });
    });

    function showLoading(show = true) {
        const progressContainer = document.getElementById('progressContainer');

        if (show) {
            progressContainer.classList.add('show');
            // submitBtn.disabled = true;
            // submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
        } else {
            setTimeout(() => {
                progressContainer.classList.remove('show');
                // submitBtn.disabled = false;
                // submitBtn.textContent = 'Submit Report';
            }, 500);
        }
    }

    // Update progress bar
    function updateProgress(percentage) {
        const formProgressBar = document.getElementById('formProgressBar');

        formProgressBar.style.width = percentage + '%';
        formProgressBar.setAttribute('aria-valuenow', percentage);
    }

    // Simulate progress for file upload
    function simulateProgress() {
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress >= 90) {
                progress = 90; // Don't go to 100% until the request completes
                clearInterval(interval);
            }
            updateProgress(progress);
        }, 200);

        return interval;
    }
</script>

</html>