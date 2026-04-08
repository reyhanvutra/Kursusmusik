<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/owner/dashboard.css'); ?>">

<div class="welcome-section">
    <h2>Ringkasan Performa</h2>
    <p>Pantau pertumbuhan bisnis kursus musik Anda secara real-time.</p>
</div>

<div class="stats-grid">
    
    <div class="stat-card featured">
        <h4>Pendapatan Bulan Ini</h4>
        <h2>Rp <?= number_format($pendapatan, 0, ',', '.'); ?></h2>
        <a href="/owner/laporan" class="card-action-pill">
            Detail Laporan <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
        </a>
    </div>

    <div class="stat-card">
        <h4>Total Transaksi</h4>
        <h2><?= $total_transaksi; ?></h2>
        <div class="card-action-pill" style="pointer-events: none; opacity: 0.6;">
            Invoices Processed
        </div>
    </div>

    <div class="stat-card">
        <h4>Siswa Aktif</h4>
        <h2 style="color: #00d4ff;"><?= $siswa_aktif; ?></h2>
        <a href="/owner/datasiswa" class="card-action-pill">
            Lihat Data Siswa
        </a>
    </div>

    <div class="stat-card">
        <h4>Kursus Aktif</h4>
        <h2><?= $kursus_aktif; ?></h2>
        <a href="/owner/kursus" class="card-action-pill">
            Kelola Kursus
        </a>
    </div>

</div>

<div class="info-notice">
    <i class="fa-solid fa-circle-info"></i>
    <div class="text-content">
        <b>Sinkronisasi Data Otomatis</b>
        <span>Laporan keuangan diperbarui setiap kali kasir menyelesaikan pembayaran baru.</span>
    </div>
</div>

<?= $this->endSection(); ?>