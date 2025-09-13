<style>
    :root {
        --primary-color: #001f3f;
        --secondary-color: #0074D9;
        --accent-color: #007dff;
        --text-light: #ffffff;
        --completed-color: #27ae60;
        --on-process-color: #0074D9;
        --pending-color: #e6a53dff;
    }

    .dashboard-container {
        background-image: linear-gradient(rgba(0, 31, 63, 0.7), rgba(0, 31, 63, 0.9)), url('<?= base_url("assets/images/dashboard-background.png") ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        height: 88vh;
        position: relative;
    }

    .welcome-card {
        background: rgba(255, 255, 255, 1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        max-width: 700px;
        width: 100%;
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .welcome-card.animate {
        opacity: 1;
        transform: translateY(0);
    }

    .create-btn {
        background: linear-gradient(45deg, var(--primary-color), var(--primary-color));
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .create-btn:hover {
        box-shadow: 0 0 20px rgba(255, 220, 0, 0.5);
    }

    .create-btn:before {
        content: '';
        position: absolute;
        top: -50%;
        left: -150%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        transition: all 0.8s ease;
        z-index: 0;
    }

    .create-btn:hover:before {
        transform: rotate(45deg) translate(50px, -200%);
    }

    .stats-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        max-width: 700px;
        width: 100%;
        margin-top: 3rem;
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease 0.3s, transform 0.8s ease 0.3s;
    }

    .stats-container.animate {
        opacity: 1;
        transform: translateY(0);
    }

    .stat-card {
        background: rgba(255, 255, 255, 1);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        flex-grow: 1;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .total-color {
        color: var(--total-color);
    }

    .completed-color {
        color: var(--completed-color);
    }

    .on-process-color {
        color: var(--on-process-color);
    }

    .pending-color {
        color: var(--pending-color);
    }

    .time-display {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .stats-container {
            flex-wrap: wrap;
        }

        .stat-card {
            min-width: 140px;
        }

        .welcome-card {
            margin: 0 15px;
            padding: 1.5rem;
        }
    }
</style>

<div class="container-fluid dashboard-container position-relative">
    <!-- Main content -->
    <div class="container h-100 d-flex align-items-center justify-content-center" style="flex-direction: column;">
        <div class="welcome-card" id="welcomeCard">
            <div class="p-3 text-center text-navy">
                <h3 class="mb-4">Selamat Datang, <b><?= $current_user['first_name'] . ' ' . $current_user['last_name'] ?></b>!</h3>
                <?php if ($this->auth_lib->role() === 'pelapor'): ?>
                    <p class="lead">Anda telah berhasil masuk ke sistem, silahkan lakukan pengaduan Anda</p>
                    <a href="<?= site_url('/report/create') ?>" class="btn btn-default btn-lg border-0 shadow-sm rounded-lg text-white font-weight-bold create-btn mt-4">
                        <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan Baru
                    </a>
                <?php else: ?>
                    <p class="lead">Anda telah berhasil masuk ke sistem</p>
                <?php endif; ?>
                <div class="time-display" id="timeDisplay">
                    <?= formatIndonesianDateTime(); ?>
                </div>
            </div>
        </div>
        <div class="stats-container" id="statsContainer">
            <div class="stat-card text-navy">
                <div class="stat-value total-color" id="totalReports"><?= $summary['all'] ?></div>
                <div class="stat-label">Total Pengaduan</div>
            </div>
            <div class="stat-card text-navy">
                <div class="stat-value completed-color" id="completedReports"><?= $summary['completed'] ?></div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card text-navy">
                <div class="stat-value on-process-color" id="onProcessReports"><?= $summary['on_process'] ?></div>
                <div class="stat-label">On Process</div>
            </div>
            <div class="stat-card text-navy">
                <div class="stat-value pending-color" id="pendingReports"><?= $summary['pending'] ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger fade-up animations
        setTimeout(() => {
            document.getElementById('welcomeCard').classList.add('animate');
        }, 100);

        setTimeout(() => {
            document.getElementById('statsContainer').classList.add('animate');
        }, 400);

        // Real-time clock
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const date = now.toLocaleDateString('id-ID', options);
            const time = now.toLocaleTimeString('id-ID');
            document.getElementById('timeDisplay').innerHTML = `${date} | ${time}`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    });
</script>