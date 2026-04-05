<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">

    <div class="form-header-area">
        <a href="/admin/kategori" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Kategori
        </a>
        <h2 class="page-header">Edit Kategori</h2>
    </div>

    <div class="form-container" style="max-width: 500px;">
        <form action="/admin/kategori/update/<?= $kategori['id'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" 
                       value="<?= $kategori['nama_kategori'] ?>" 
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control"><?= $kategori['deskripsi'] ?></textarea>
            </div>

            <!-- 🔥 PREVIEW GAMBAR -->
            <div class="form-group">
                <label>Gambar Saat Ini</label>

                <?php if($kategori['gambar']): ?>
                    <img src="<?= base_url('uploads/kategori/'.$kategori['gambar']) ?>" 
                         style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;margin-bottom:10px;">
                <?php else: ?>
                    <p style="color:#555;">Belum ada gambar</p>
                <?php endif; ?>
            </div>

            <!-- 🔥 UPLOAD BARU -->
            <div class="form-group">
                <label>Ganti Gambar</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn-save">Update</button>
        </form>
    </div>

</div>

<?= $this->endSection(); ?>