<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/dashboard_kasir.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/kasir/pilih_kategori.css'); ?>">

<div class="form-page-wrapper">
    
    <div class="table-header" style="margin-bottom: 20px;">
        <div>
            <h2 class="page-header">Pilih Kategori Kursus</h2>
            <p style="color: #666; margin-top: -5px;">Pilih kategori yang sesuai untuk memulai pendaftaran atau transaksi</p>
        </div>
        
        <a href="/kasir/dashboard" class="btn-detail" style="display: flex; align-items: center; gap: 8px; padding: 12px 20px;">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

   <div class="category-grid">
    <?php foreach($kategori as $k): ?>

        <?php 
        $bg = $k['gambar'] 
            ? base_url('uploads/kategori/'.$k['gambar']) 
            : base_url('assets/no-image.jpg'); 
        ?>

        <div class="category-card"
             style="background-image: url('<?= $bg ?>');">

            <!-- OVERLAY -->
            <div class="category-overlay"></div>

            <div class="category-content">
                <h3><?= $k['nama_kategori']; ?></h3>

                <?php if(!empty($k['deskripsi'])): ?>
                    <p><?= $k['deskripsi']; ?></p>
                <?php endif; ?>
            </div>

            <div class="category-footer">
                <a href="/kasir/detail/kategori/<?= $k['id']; ?>" class="btn-action-cat">
                    Pilih Kategori 
                    <i class="fa-solid fa-chevron-right" style="margin-left: 10px; font-size: 11px;"></i>
                </a>
            </div>

        </div>
    <?php endforeach; ?>
</div>

</div>

<?= $this->endSection(); ?>