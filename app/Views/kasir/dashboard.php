<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/dashboard_kasir.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <h2 class="page-header">Kasir Dashboard</h2>
        <p style="color: #555; margin-top: -5px;">Ringkasan aktivitas transaksi hari ini</p>
    </div>

    <div class="nav-buttons">
        <a href="/kasir/pilih" class="btn-nav primary">
            <i class="fa-solid fa-circle-plus"></i> MULAI TRANSAKSI
        </a>
        <a href="/kasir/siswa" class="btn-nav secondary">
            <i class="fa-solid fa-user-group"></i> DATA SISWA
        </a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h4>Pendapatan Hari Ini</h4>
            <h2 style="color: #ff4d4d;">Rp <?= number_format($pendapatan_hari_ini, 0, ',', '.'); ?></h2>
        </div>

        <div class="stat-card">
            <h4>Kursus Aktif</h4>
            <h2><?= $kursus_aktif; ?> <span>Kelas</span></h2>
        </div>

        <div class="stat-card">
            <h4>Total Transaksi</h4>
            <h2><?= $total_transaksi_hari_ini; ?> <span>Nota</span></h2>
        </div>
    </div>

    <div class="table-container">
      <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h3 style="color: white; margin: 0; font-weight: 600;">Riwayat Transaksi</h3>

    <!-- 🔥 BUTTON LIHAT SEMUA -->
    <a href="/kasir/riwayat" class="btn-nav secondary" style="padding:8px 12px;">
        Lihat Semua
    </a>
</div>
        
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelanggan / Siswa</th>
                    <th>Kursus yang Diambil</th>
                    <th>Total Bayar</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transaksi as $t): ?>
                <tr>
                    <td style="color: #777;"><?= date('d/m/Y', strtotime($t['tanggal'])); ?></td>
                    <td><strong style="color: #eee;"><?= $t['nama_pembeli']; ?></strong></td>
                    <td>
                        <?php if(!empty($t['items'])): ?>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <?php foreach($t['items'] as $item): ?>
                                    <span class="badge-item"><?= $item; ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td style="color: #ff4d4d; font-weight: 700;">
                        Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="/kasir/detail/<?= $t['id']; ?>" class="btn-detail">Detail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>