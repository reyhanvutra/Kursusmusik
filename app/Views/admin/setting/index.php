<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <h2 class="page-header">Pengaturan Aplikasi</h2>
        <p style="color: #888; font-size: 13px; margin: 5px 0 0 0;">Kelola biaya pendaftaran kursus secara global.</p>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert success" style="width: 100%; max-width: 800px;">
            <i class="fa-solid fa-circle-check"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form action="/admin/setting/update" method="post">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label for="biaya">Biaya Pendaftaran (Rp)</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <span style="position: absolute; left: 15px; color: #888; font-weight: bold; font-size: 14px; pointer-events: none;">
                        Rp
                    </span>
                    <input type="number" 
                           id="biaya"
                           name="biaya_pendaftaran" 
                           class="form-control" 
                           value="<?= $setting['biaya_pendaftaran'] ?>" 
                           style="padding-left: 45px;" 
                           placeholder="0"
                           required>
                </div>
                <small style="color: #666; margin-top: 8px; display: block; font-style: italic; font-size: 11px;">
                    *Biaya ini akan otomatis muncul pada tagihan pendaftaran siswa baru.
                </small>
            </div>

            <button type="submit" class="btn-save">
                 Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>