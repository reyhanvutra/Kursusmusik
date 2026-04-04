<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/level/index/<?= $level['id_kursus']; ?>" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
        </a>
        <h2 class="page-header">Edit Level</h2>
    </div>

    <div class="form-container" style="max-width: 600px;">
        <form action="/admin/level/update/<?= $level['id']; ?>" method="post">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label>Nama Level</label>
                <input type="text" name="nama_level" class="form-control" value="<?= $level['nama_level']; ?>" required>
            </div>

            <div class="form-grid" style="gap: 15px;">
                <div class="form-column" style="min-width: 100px;">
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="<?= $level['urutan']; ?>" required>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label>Pertemuan</label>
                        <input type="number" name="pertemuan" class="form-control" value="<?= $level['pertemuan']; ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $level['harga']; ?>" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Level</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= $level['deskripsi']; ?></textarea>
            </div>

            <button type="submit" class="btn-save">Update Level</button>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>