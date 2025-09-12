<div class="container-fluid position-relative" style="
    background-image: linear-gradient(rgba(0, 31, 63, 0.7), rgba(0, 31, 63, 0.9)), url('<?= base_url("assets/images/dashboard-background.png") ?>');
    background-size: cover; 
    background-position: center; 
    background-repeat: no-repeat; 
    margin: 0; 
    padding: 0;
    height: 88vh;">
    <div class="container h-100 d-flex align-items-center justify-content-center">
        <div class="p-5 text-center text-white">
            <h3 class="mb-4">Selamat Datang, <b><?= $current_user['first_name'] . ' ' . $current_user['last_name'] ?></b>!</h3>
            <?php if ($this->auth_lib->role() === 'pelapor'): ?>
                <p class="lead">Anda telah berhasil masuk ke sistem, silahkan lakukan pengaduan anda</p>
                <a href="<?= site_url('/report/create') ?>" class="btn btn-default btn-lg border-0 shadow-sm rounded-lg text-navy font-weight-bold create-btn mt-4">
                    <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan Baru
                </a>
            <?php else: ?>
                <p class="lead">Anda telah berhasil masuk ke sistem</p>
            <?php endif; ?>
        </div>
    </div>
</div>