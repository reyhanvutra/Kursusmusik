<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit Kursus</h2>

<form action="/admin/kursus/update/<?= $kursus['id']; ?>" method="post">

Nama: <input type="text" name="nama_kursus" value="<?= $kursus['nama_kursus']; ?>"><br><br>
Harga: <input type="number" name="harga" value="<?= $kursus['harga']; ?>"><br><br>
Instruktur: <input type="text" name="instruktur" value="<?= $kursus['instruktur']; ?>"><br><br>
Durasi: <input type="text" name="durasi" value="<?= $kursus['durasi']; ?>"><br><br>
Slot: <input type="number" name="slot" value="<?= $kursus['slot']; ?>"><br><br>

<button type="submit">Update</button>
</form>

<?= $this->endSection(); ?>