<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit Siswa</h2>

<?php if(session()->getFlashdata('error')): ?>
<p style="color:red;"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<form method="post" action="/admin/siswa/update/<?= $s['id']; ?>">
    <label>Nama:</label><br>
    <input type="text" name="nama" value="<?= old('nama', $s['nama']) ?>"><br><br>

    <label>No HP:</label><br>
    <input type="text" name="no_hp" value="<?= old('no_hp', $s['no_hp']) ?>"><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat"><?= old('alamat', $s['alamat']) ?></textarea><br><br>

    <button type="submit">Simpan</button>
</form>

<a href="/admin/siswa">← Kembali</a>

<?= $this->endSection(); ?>