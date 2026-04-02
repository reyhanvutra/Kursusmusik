    <?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit Level</h2>

<form action="/admin/level/update/<?= $level['id']; ?>" method="post">

Nama Level:
<input type="text" name="nama_level" value="<?= $level['nama_level']; ?>" required><br><br>

Urutan:
<input type="number" name="urutan" value="<?= $level['urutan']; ?>" required><br><br>

Harga:
<input type="number" name="harga" value="<?= $level['harga']; ?>" required><br><br>

Pertemuan:
<input type="number" name="pertemuan" value="<?= $level['pertemuan']; ?>" required><br><br>

Deskripsi:
<textarea name="deskripsi"><?= $level['deskripsi']; ?></textarea><br><br>

<button type="submit">Update</button>
<a href="/admin/kursus">← Kembali</a>

</form>

<?= $this->endSection(); ?>