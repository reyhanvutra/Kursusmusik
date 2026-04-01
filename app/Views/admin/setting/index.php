<?= $this->extend('admin/layout'); ?> 
<?= $this->section('content'); ?>
<h2>Setting Biaya Pendaftaran</h2>

<?php if(session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<form action="/admin/setting/update" method="post">
    <label>Biaya Pendaftaran</label><br>
    <input type="number" name="biaya_pendaftaran" 
        value="<?= $setting['biaya_pendaftaran'] ?>" required>
    <br><br>

    <button type="submit">Simpan</button>
</form>
<?= $this->endSection(); ?>