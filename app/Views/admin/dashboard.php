<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_dashboard.css'); ?>">

<h2 class="page-header">Dashboard Admin</h2>

<div class="dashboard-container">
    
    <div class="column-left">
        <div class="card-premium">
            <div>
                <span class="card-label">Total Kursus</span>
                <div class="card-value"><?= $total_kursus; ?> Kursus</div>
            </div>
            <div class="card-info-pill">
                <?= $kategori_list ?: '-' ?>
            </div>
        </div>

        <div class="card-premium">
            <div>
                <span class="card-label">Transaksi Hari Ini</span>
                <div class="card-value"><?= $transaksi_hari_ini; ?> Transaksi</div>
            </div>
            <div class="card-info-pill text-success">
                Total Pendapatan : Rp <?= number_format($pendapatan_hari_ini,0,',','.'); ?>
            </div>
        </div>
    </div>

    <div class="card-tall">
        <span class="card-label">Total User</span>
        <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
            <div class="card-value" style="font-size: 80px;"><?= $total_user; ?></div>
        </div>
<div class="user-list-detail">
    <?php foreach($users as $u): ?>
        <div class="user-item">
            <div class="user-info">
                <div class="user-name"><?= $u['nama']; ?></div>
                <div class="user-email"><?= $u['email']; ?></div>
            </div>
            <div class="user-role"><?= strtoupper($u['role']); ?></div>
        </div>
    <?php endforeach; ?>
</div>
    </div>

</div>
<?= $this->endSection(); ?>