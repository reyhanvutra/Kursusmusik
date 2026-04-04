<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <h2 class="page-header">⚙️ Pengaturan Aplikasi</h2>
        <p style="color: #666; font-size: 13px;">Kelola biaya pendaftaran kursus di sini.</p>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid rgba(0, 255, 0, 0.2); font-size: 14px;">
            <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="form-container" style="max-width: 500px; margin-top: 0;">
        <form action="/admin/setting/update" method="post">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Biaya Pendaftaran (Rp)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 15px; top: 12px; color: #555; font-weight: bold;">Rp</span>
                    <input type="number" name="biaya_pendaftaran" class="form-control" 
                           value="<?= $setting['biaya_pendaftaran'] ?>" 
                           style="padding-left: 45px;" required>
                </div>
                <small style="color: #555; margin-top: 8px; display: block;">
                    *Biaya ini akan otomatis ditambahkan pada pendaftaran siswa baru.
                </small>
            </div>

            <button type="submit" class="btn-save" style="margin-top: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>