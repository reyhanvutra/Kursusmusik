<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/siswa" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h2 class="page-header">Edit Data Siswa</h2>
    </div>

    <div class="form-container">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert error">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/admin/siswa/update/<?= $s['id']; ?>">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" 
                       id="nama"
                       name="nama" 
                       class="form-control" 
                       value="<?= old('nama', $s['nama']) ?>" 
                       placeholder="Contoh: Budi Santoso"
                       required>
            </div>

            <div class="form-group">
                <label for="no_hp">No. HP / WhatsApp</label>
                <input type="text" 
                       id="no_hp"
                       name="no_hp" 
                       class="form-control" 
                       value="<?= old('no_hp', $s['no_hp']) ?>" 
                       placeholder="Contoh: 08123456789"
                       required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea id="alamat"
                          name="alamat" 
                          class="form-control" 
                          rows="4" 
                          placeholder="Masukkan alamat lengkap siswa..."><?= old('alamat', $s['alamat']) ?></textarea>
            </div>

            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>