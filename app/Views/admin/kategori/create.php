<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">

    <div class="form-header-area">
        <a href="/admin/kategori" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Kategori
        </a>
        <h2 class="page-header">Tambah Kategori Baru</h2>
    </div>

    <div class="form-container" style="max-width: 500px;">
        <form action="/admin/kategori/store" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"></textarea>
            </div>

            <!-- 🔥 UPLOAD GAMBAR -->
            <div class="form-group">
                <label>Gambar Kategori</label>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn-save">Simpan</button>
        </form>
    </div>

</div>
<?= $this->endSection(); ?>