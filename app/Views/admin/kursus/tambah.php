<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah Kursus</h2>

<form action="/admin/kursus/simpan" method="post" enctype="multipart/form-data">

Nama: <input type="text" name="nama_kursus"><br><br>
Harga: <input type="number" name="harga"><br><br>
Instruktur: <input type="text" name="instruktur"><br><br>
Durasi: <input type="text" name="durasi"><br><br>
Slot: <input type="number" name="slot"><br><br>
Deskripsi:<br>
<textarea name="deskripsi"></textarea><br><br>

Gambar: <input type="file" name="gambar"><br><br>

<button type="submit">Simpan</button>
</form>

<?= $this->endSection(); ?>