<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>

<h2>Dashboard Admin</h2>

<div class="card">
    <h3>Total Kursus</h3>
    <p><?= $total_kursus; ?></p>
</div>

<div class="card">
    <h3>Total User</h3>
    <p><?= $total_user; ?></p>
</div>

<div class="card">
    <h3>Transaksi Hari Ini</h3>
    <p><?= $transaksi_hari_ini; ?></p>
</div>

<?= $this->endSection(); ?>