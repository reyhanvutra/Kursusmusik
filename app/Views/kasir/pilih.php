<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/pilih_kategori.css'); ?>">

<div class="form-page-wrapper">
    
    <div class="header-flex">
        <div>
            <h2 class="page-header">Pilih Kategori Kursus</h2>
            <p style="color: #666; margin-top: 5px; font-weight: 500;">Pilih kategori kelas untuk memulai transaksi baru.</p>
        </div>
        
        <a href="/kasir/dashboard" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <div class="category-grid">
        <?php foreach($kategori as $k): ?>
            <?php 
            $img = $k['gambar'] 
                ? base_url('uploads/kategori/'.$k['gambar']) 
                : base_url('assets/no-image.jpg'); 
            ?>

            <div class="category-card" style="background-image: url('<?= $img ?>');">
                
                <div class="category-overlay"></div>

                <div class="category-content">
                    <h3 class="category-title"><?= esc($k['nama_kategori']); ?></h3>

                    <p class="category-desc">
                        <?= !empty($k['deskripsi']) ? esc($k['deskripsi']) : "Manajemen pendaftaran untuk kelas " . esc($k['nama_kategori']) . " tersedia di sini."; ?>
                    </p>
                </div>

                <div class="category-footer">
                    <a href="/kasir/detail/kategori/<?= $k['id']; ?>" class="btn-action-cat">
                        PILIH KATEGORI 
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?= $this->endSection(); ?>