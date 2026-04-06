<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah Mentor</h2>

<a href="/admin/mentor">← Kembali</a>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;margin-top:10px;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<form method="post" action="/admin/mentor/simpan" style="margin-top:15px;">
<?= csrf_field(); ?>

<label>Nama Mentor</label><br>
<input type="text" name="nama" value="<?= old('nama'); ?>" required
       style="width:300px;padding:6px;"><br><br>

<label>Keahlian</label><br>
<input type="text" name="keahlian" value="<?= old('keahlian'); ?>" required
       style="width:300px;padding:6px;"><br><br>

<label>Status</label><br>
<select name="aktif" style="width:200px;padding:6px;">
    <option value="1">Aktif</option>
    <option value="0">Nonaktif</option>
</select><br><br>

<button type="submit" style="background:#007bff;color:white;padding:8px 15px;border:none;border-radius:5px;">
    Simpan
</button>

</form>

<?= $this->endSection(); ?>