<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/level/index/<?= $id_kursus; ?>" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Level
        </a>
        <h2 class="page-header">Tambah Level Baru</h2>
    </div>

    <div class="form-container" style="max-width: 600px;">
        <form action="/admin/level/simpan" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">

            <div class="form-group">
                <label>Nama Level</label>
                <input type="text" name="nama_level" class="form-control" placeholder="Contoh: Grade 1 / Beginner" required>
            </div>

            <div class="form-grid" style="gap: 15px;">
                <div class="form-column" style="min-width: 100px;">
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="urutan" class="form-control" placeholder="1" required>
                    </div>
                </div>
                <div class="form-column">
                    <div class="form-group">
                        <label>Pertemuan</label>
                        <input type="number" name="pertemuan" class="form-control" placeholder="12" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" min="10000" step="1000" placeholder="350000" required>
            </div>

            <div class="form-group">
                <label>Deskripsi Level</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn-save">Simpan Level</button>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>