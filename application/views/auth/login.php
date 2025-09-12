    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WareCare</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="<?= base_url('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
        <!-- Toastr -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>

    <body class="hold-transition login-page justify-content-start bg-white" style="
        margin-top: 10rem;
        background-image: linear-gradient(rgba(247, 247, 247, 0.9), rgba(247, 247, 247, 1)), url('<?= base_url('assets/images/dashboard-background.png') ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 0; ">
        <div class=" login-box">
            <div class="login-logo">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Waringin Group Logo" class="img-fluid mb-3" style="max-width: 14rem;">
            </div>
            <!-- /.login-logo -->
            <div class="card shadow border-0 rounded-lg py-2">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Silahkan masuk</p>

                    <form action="<?= $login_url ?>" method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <!-- /.col -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg rounded-lg bg-navy btn-block">Masuk</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <footer class="text-center text-muted" style="margin-top: 5rem;">
            <div class="container">
                <strong>&copy; <?= date('Y') ?> Waringin Group</strong>
                <div class="mt-2">
                    <small>All rights reserved. Version 1.0</small>
                </div>
            </div>
        </footer>
        <!-- jQuery -->
        <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
        <!-- Bootstrap 4 -->
        <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
        <!-- Toastr -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            const notifications = {
                error: '<?= $this->session->flashdata('error') ?>'
            };

            $(document).ready(function() {
                toastr.options = {
                    positionClass: "toast-top-right",
                    progressBar: true,
                    closeButton: true,
                    preventDuplicates: true,
                    timeOut: 3000,
                };

                setTimeout(() => {
                    if (notifications.error) {
                        toastr.error(notifications.error);
                    }
                }, 100)
            });
        </script>
    </body>

    </html>