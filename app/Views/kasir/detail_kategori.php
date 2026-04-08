<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/detail_kategori.css'); ?>">

<?php 
$bg = $kategori['gambar'] 
    ? base_url('uploads/kategori/'.$kategori['gambar']) 
    : base_url('assets/no-image.jpg'); 
?>

<div class="detail-wrapper">

    <a href="/kasir/pilih" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Kategori
    </a>

    <div class="kategori-hero">
        <img src="<?= $bg ?>" alt="<?= $kategori['nama_kategori']; ?>" class="hero-img">
        <div class="overlay"></div>

        <div class="hero-content">
            <h1><?= $kategori['nama_kategori']; ?></h1>
        </div>
    </div>

    <?php if(!empty($kategori['deskripsi'])): ?>
    <div class="kategori-deskripsi">
        <?= esc($kategori['deskripsi']); ?>
    </div>
    <?php endif; ?>

    <div class="kursus-section">
        <h2>Daftar Kursus Tersedia</h2>

        <?php if(empty($kursus)): ?>
            <div style="background: rgba(255,255,255,0.02); padding: 40px; border-radius: 20px; text-align: center; border: 1px dashed rgba(255,255,255,0.1);">
                <p style="color: #444; font-weight: 600;">Saat ini belum ada paket kursus yang tersedia untuk kategori ini.</p>
            </div>
        <?php else: ?>

        <div class="kursus-grid">
            <?php foreach($kursus as $k): ?>
            <div class="kursus-card">
                <div class="kursus-info">
                    <h3><?= esc($k['nama_kursus']); ?></h3>
                    <p>
                        <?= esc($k['deskripsi']); ?>
                    </p>
                </div>

                <div class="kursus-footer">
                    <a href="/kasir/detail/kursus/<?= $k['id']; ?>" class="btn-kursus">
                        Lihat Detail Paket 
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>

</div>

<?= $this->endSection(); ?>