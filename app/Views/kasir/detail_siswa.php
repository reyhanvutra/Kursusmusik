<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/siswa_detail.css'); ?>">

<div class="profile-container">
     

    <div class="main-profile-card">
        <div class="profile-header">
            <div class="profile-name">
                <h2><?= $siswa['nama']; ?></h2>
                <p><i class="fa-solid fa-phone"></i> <?= $siswa['no_hp']; ?> — <i class="fa-solid fa-location-dot"></i> <?= $siswa['alamat']; ?></p>
            </div>
            <div class="stats-pill-wrapper">
                <div class="stat-pill">
                    <span class="stat-label">Aktif</span>
                    <span class="stat-value" style="color:var(--success);"><?= $total_aktif; ?></span>
                </div>
                <div class="stat-pill">
                    <span class="stat-label">Riwayat</span>
                    <span class="stat-value"><?= $total_riwayat; ?></span>
                </div>
            </div>
        </div>
    </div>

    <h3 style="color:white; margin-bottom:20px; letter-spacing:1px; font-weight:800;">DATA KURSUS</h3>

    <?php if(empty($kursus)): ?>
        <div class="course-group" style="text-align:center; color:var(--text-dim);">
            Siswa belum mengambil kursus apapun.
        </div>
    <?php else: ?>
        <?php foreach($kursus as $k): ?>
        <div class="course-group">
            <div class="course-title">
                <i class="fa-solid fa-book-open" style="color:var(--accent);"></i>
                <?= $k['nama']; ?>
            </div>

            <?php foreach($k['data'] as $d): ?>
            <div class="history-item">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <?php if($d['status'] == 'aktif'): ?>
                            <span class="status-badge status-aktif">🟢 Aktif</span>
                        <?php else: ?>
                            <span class="status-badge status-selesai">🔴 Selesai</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($d['status'] == 'aktif'): ?>
                        <div style="font-size:12px; font-weight:700; color:<?= ($d['sisa_hari'] <= 7) ? 'orange' : 'var(--text-dim)'; ?>;">
                            <?= ($d['sisa_hari'] <= 7) ? '⚠️' : ''; ?> <?= $d['sisa_hari']; ?> Hari Tersisa
                        </div>
                    <?php endif; ?>
                </div>

                <div class="detail-grid">
                    <div class="detail-node">
                        <small>Mulai Kursus</small>
                        <b><?= date('d M Y', strtotime($d['mulai_awal'])); ?></b>
                    </div>
                   <div class="detail-node">
    <small>Status Perpanjangan</small>
    <b style="color:#00d4ff;">
        <?= $d['label_perpanjang']; ?>
    </b>
</div>
                    <div class="detail-node">
                        <small>Berakhir Pada</small>
                        <b><?= date('d M Y', strtotime($d['selesai'])); ?></b>
                    </div>
                    <div class="detail-node">
                        <small>Total Durasi</small>
                        <b><?= $d['durasi_hari']; ?> Hari</b>
                    </div>
                </div>

                <?php if($d['status'] == 'aktif'): ?>
                <div style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.03); padding-top:15px; text-align:right;">
                    <a href="/kasir/perpanjang/<?= $d['id_detail']; ?>" class="btn-perpanjang">
                        <i class="fa-solid fa-rotate"></i> Perpanjang Kursus
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div style="margin-top:20px;">
       <a href="/kasir/siswa" class="btn-back-siswa">
            Kembali 
        </a>
    </div>

</div>

<?= $this->endSection(); ?>