<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/siswa" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
        </a>
        <h2 class="page-header">Edit Data Siswa</h2>
    </div>

    <div class="form-container" style="max-width: 600px;">
        <form method="post" action="/admin/siswa/update/<?= $s['id']; ?>">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= old('nama', $s['nama']) ?>" required>
            </div>

            <div class="form-group">
                <label>No. HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp', $s['no_hp']) ?>" required>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="4"><?= old('alamat', $s['alamat']) ?></textarea>
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>