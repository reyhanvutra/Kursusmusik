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
    <h3>Total Transaksi</h3>
    <p><?= $total_transaksi; ?></p>
</div>

<?= $this->endSection(); ?>