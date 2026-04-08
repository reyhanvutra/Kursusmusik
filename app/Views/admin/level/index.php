<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="content-wrapper">
  <div class="user-header-flex">
    <div class="title-area">
        <a href="/admin/kursus" class="btn-back-link">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Kursus
        </a>
        <h2 class="page-header" style="margin: 0; color: white;">Level: <?= esc($kursus['nama_kursus']); ?></h2>
        <p style="color: #555; margin-top: 5px;">Atur tingkatan kelas, jadwal, dan ketersediaan slot.</p>
    </div>

    <a href="/admin/level/tambah/<?= $kursus['id']; ?>" class="btn-tambah">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Level Baru</span>
    </a>
</div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert success">
            <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="level-grid">
        <?php if(empty($level)): ?>
            <div class="empty-state-card">
                <i class="fa-solid fa-layer-group"></i>
                <p>Belum ada tingkatan level untuk kursus ini.</p>
            </div>
        <?php else: ?>
            <?php foreach($level as $l): ?>
            <div class="level-card">
                <div class="level-card-header">
                    <span class="order-badge"><?= $l['urutan']; ?></span>
                    <h3 class="level-name"><?= esc($l['nama_level']); ?></h3>
                </div>

                <div class="level-price">
                    <small>Biaya Kursus</small>
                    <p>Rp <?= number_format($l['harga'], 0, ',', '.'); ?></p>
                </div>

                <div class="level-info-grid">
                    <div class="info-item">
                        <i class="fa-solid fa-calendar-day"></i>
                        <span><?= $l['hari'] ?: '-'; ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-clock"></i>
                        <span><?= date('H:i', strtotime($l['jam_mulai'])); ?> - <?= date('H:i', strtotime($l['jam_selesai'])); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <span><?= $l['nama_mentor'] ?? 'Tanpa Mentor'; ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-hourglass-half"></i>
                        <span><?= $l['pertemuan']; ?>x Pertemuan</span>
                    </div>
                </div>

                <div class="slot-section">
                    <div class="slot-label">
                        <span>Slot Terisi</span>
                        <span class="<?= $l['slot_sisa'] > 0 ? 'text-success' : 'text-danger'; ?>">
                            <?= $l['slot_terpakai']; ?> / <?= $l['slot']; ?>
                        </span>
                    </div>
                    <div class="progress-bar">
                        <?php 
                            $percent = ($l['slot'] > 0) ? ($l['slot_terpakai'] / $l['slot']) * 100 : 0;
                        ?>
                        <div class="progress-fill" style="width: <?= $percent; ?>%"></div>
                    </div>
                </div>

                <div class="level-card-actions">
                    <a href="/admin/level/edit/<?= $l['id']; ?>" class="btn-action-card edit">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <a href="/admin/level/hapus/<?= $l['id']; ?>" class="btn-action-card delete" onclick="return confirm('Hapus level ini?')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>