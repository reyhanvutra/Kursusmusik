<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/detail_siswa.css'); ?>">

<div class="content-wrapper">
    <div class="detail-header-section">
        <div class="title-group">
            <h2>Informasi Detail Siswa</h2>
            <p>Data profil dan riwayat kursus siswa aktif.</p>
        </div>
        <a href="/admin/siswa" class="btn-back-custom">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php
    $today = date('Y-m-d');
    $aktif = false;
    foreach($transaksi as $t){
        if(!empty($t['detail'])){
            foreach($t['detail'] as $d){
                if($d['tanggal_selesai'] >= $today) $aktif = true;
            }
        }
    }
    ?>

    <div class="main-profile-card">
        <div class="accent-line"></div>
        
        <div class="profile-top-row">
            <h1><?= esc($s['nama']); ?></h1>
            <?php if($s['sudah_daftar'] == 0): ?>
                <span class="badge-custom" style="color:orange; border:1px solid orange;">Belum Daftar</span>
            <?php elseif($aktif): ?>
                <span class="badge-custom badge-active">Aktif</span>
            <?php else: ?>
                <span class="badge-custom" style="color:#ff3232; border:1px solid #ff3232;">Nonaktif</span>
            <?php endif; ?>
        </div>

        <div class="profile-grid">
            <div class="info-item">
                <span class="info-label">No Hp</span>
                <span class="info-value"><i class="fa-brands fa-whatsapp" style="color:#25d366;"></i> <?= esc($s['no_hp']); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Alamat</span>
                <span class="info-value"><i class="fa-solid fa-location-dot" style="color:#990000;"></i> <?= esc($s['alamat']); ?></span>
            </div>
        </div>
    </div>

    <div class="section-divider">
        <i class="fa-solid fa-receipt" style="color:#990000;"></i>
        <h3>Riwayat Transaksi</h3>
    </div>

    <?php if (empty($transaksi)): ?>
        <div class="main-profile-card" style="text-align:center; color:#444;">
            <p>Belum ada transaksi yang tercatat.</p>
        </div>
    <?php else: ?>
        <?php foreach ($transaksi as $t): ?>
            <div class="transaction-card">
                <div class="trx-card-header">
                    <div style="display:flex; gap:30px;">
                        <div>
                            <span class="info-label" style="display:block; margin-bottom:5px;">Invoice</span>
                            <span style="color:#fff; font-family:monospace;">#TRX-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div>
                            <span class="info-label" style="display:block; margin-bottom:5px;">Tanggal</span>
                            <span style="color:#888;"><?= date('d M Y', strtotime($t['tanggal'])); ?></span>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <span class="info-label" style="display:block; margin-bottom:5px;">Total Nominal</span>
                        <span style="color:#00ff64; font-size:18px; font-weight:900;">Rp <?= number_format($t['total_harga'] ?? 0, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <table class="trx-detail-table">
                    <thead>
                        <tr>
                            <th>Kursus</th>
                            <th>Kategori Dan Level</th>
                            <th>Masa Aktif</th>
                            <th style="text-align:right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($t['detail'] as $d): ?>
                            <tr>
                                <td style="color:#fff; font-weight:700;"><?= esc($d['nama_kursus']); ?></td>
                                <td>
                                    <div style="color:#ccc; font-size:14px;"><?= esc($d['kategori']); ?></div>
                                    <div style="color:#555; font-size:12px;"><?= esc($d['level']); ?></div>
                                </td>
                                <td style="color:#888; font-size:13px;">
                                    <?= date('d/m/y', strtotime($d['tanggal_mulai'])); ?> - <?= date('d/m/y', strtotime($d['tanggal_selesai'])); ?>
                                </td>
                                <td style="text-align:right;">
                                    <?php if ($d['tanggal_selesai'] >= date('Y-m-d')): ?>
                                        <span style="color:#00ff64; font-size:10px; font-weight:800;">● ACTIVE</span>
                                    <?php else: ?>
                                        <span style="color:#444; font-size:10px; font-weight:800;">EXPIRED</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>