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
        <form action="/admin/kategori/update/<?= $kategori['id'] ?>" method="post">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="<?= $kategori['nama_kategori'] ?>" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Kategori</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?= $kategori['deskripsi'] ?></textarea>
            </div>

            <button type="submit" class="btn-save">Update Kategori</button>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>