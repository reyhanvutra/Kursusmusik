<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    
    <div class="form-header-area">
        <a href="/admin/mentor" class="btn-back">
             <i class="fa-solid fa-arrow-left"></i>Kembali</a>
        <h2 class="page-header">Tambah Mentor</h2>
    </div>

    <div class="form-container">
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert error">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/admin/mentor/simpan">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="nama">Nama Mentor</label>
                <input type="text" 
                       id="nama" 
                       name="nama" 
                       class="form-control" 
                       placeholder="Masukkan nama lengkap..."
                       value="<?= old('nama'); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="keahlian">Keahlian</label>
                <input type="text" 
                       id="keahlian" 
                       name="keahlian" 
                       class="form-control" 
                       placeholder="Contoh: Gitar, Piano, Vokal..."
                       value="<?= old('keahlian'); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="aktif">Status</label>
                <select name="aktif" id="aktif" class="form-control">
                    <option value="1" <?= old('aktif') == '1' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="0" <?= old('aktif') == '0' ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn-save">
                Simpan
            </button>

        </form>
    </div>
</div>

<?= $this->endSection(); ?>