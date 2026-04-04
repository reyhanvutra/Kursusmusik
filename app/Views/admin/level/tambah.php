<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah Level</h2>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<form action="/admin/level/simpan" method="post">

<input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">

Nama Level:
<input type="text" name="nama_level" required><br><br>

Urutan:
<input type="number" name="urutan" required><br><br>

Harga:
<input type="number" name="harga" min="10000" step="1000" required><br><br>

Pertemuan:
<input type="number" name="pertemuan" required><br><br>

Deskripsi:
<textarea name="deskripsi"></textarea><br><br>

<button type="submit">Simpan</button>
<a href="/admin/kursus">← Kembali</a>

</form>

<?= $this->endSection(); ?>