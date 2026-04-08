<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/dashboard_kasir.css'); ?>">

<div class="dashboard-wrapper">
    <div class="welcome-header">
        <h2 class="page-header">Kasir Dashboard</h2>
        <a href="/kasir/pilih" class="btn-main-action">
     MULAI TRANSAKSI
        </a>
    </div>

    <div class="stats-container">
        <div class="card-premium">
            <span class="card-label">Pendapatan Hari Ini</span>
            <h2 class="card-value">Rp <?= number_format($pendapatan_hari_ini, 0, ',', '.'); ?></h2>
        </div>

        <div class="card-premium">
            <span class="card-label">Kursus Aktif</span>
            <h2 class="card-value"><?= $kursus_aktif; ?> <small style="font-size: 20px; color: #444;">Kelas</small></h2>
        </div>

        <div class="card-premium">
            <span class="card-label">Total Transaksi hari ini</span>
            <h2 class="card-value"><?= $total_transaksi_hari_ini; ?> <small style="font-size: 20px; color: #444;">Nota</small></h2>
        </div>
    </div>

    <div class="sub-nav">
        <a href="/kasir/siswa" class="btn-sub-nav">DATA SISWA</a>
        <a href="/kasir/riwayat" class="btn-sub-nav"> RIWAYAT TRANSAKSI</a>
    </div>

    <div class="glass-table-container">
        <div class="table-header-flex">
            <h3 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0; letter-spacing: 1px;">TRANSAKSI TERBARU</h3>
            <a href="/kasir/riwayat" style="color: #555; font-size: 12px; font-weight: 800; text-decoration: none;">LIHAT SEMUA</a>
        </div>
        
        <table class="modern-table">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>PELANGGAN</th>
                    <th>KURSUS</th>
                    <th>TOTAL BAYAR</th>
                    <th style="text-align: center;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transaksi as $t): ?>
                <tr>
                    <td style="color: #555; font-weight: 700;"><?= date('d/m/Y', strtotime($t['tanggal'])); ?></td>
                    <td><strong style="color: #fff;"><?= esc($t['nama_pembeli']); ?></strong></td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <?php if(!empty($t['items'])): ?>
                                <?php foreach($t['items'] as $item): ?>
                                    <span class="item-pill"><?= $item; ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="color: #fff; font-weight: 800;">Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?></td>
                    <td style="text-align: center;">
                        <a href="/kasir/detail/<?= $t['id']; ?>" class="btn-action-view">DETAIL</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>