<div class="container">
    <div class="p-5 text-center">
        <h3 class="mb-4">Selamat Datang, <b><?= $current_user['first_name'] . ' ' . $current_user['last_name'] ?></b>!</h3>
        <?php if ($this->auth_lib->role() === 'pelapor'): ?>
            <p class="lead">Anda telah berhasil masuk ke sistem, silahkan lakukan pengaduan anda</p>
            <a href="<?= site_url('/report/create') ?>" class="btn bg-navy btn-lg border-0 shadow-sm rounded-lg text-navy font-weight-bold create-btn mt-4">
                <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan Baru
            </a>
        <?php else: ?>
            <p class="lead">Anda telah berhasil masuk ke sistem</p>
        <?php endif; ?>
    </div>
</div>