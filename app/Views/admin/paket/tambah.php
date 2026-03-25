<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah Paket Kursus</h2>

<form action="/admin/paket/simpan" method="post">

    Nama Paket:<br>
    <input type="text" name="nama_paket"><br><br>

    Harga:<br>
    <input type="number" name="harga"><br><br>

    Slot:<br>
    <input type="number" name="slot"><br><br>

    Instruktur:<br>
    <input type="text" name="instruktur"><br><br>

    Pilih Kursus:<br>
    <?php foreach($kursus as $k): ?>
        <input type="checkbox" name="kursus[]" value="<?= $k['id']; ?>">
        <?= $k['nama_kursus']; ?><br>
    <?php endforeach; ?>

    <br>

    Deskripsi:<br>
    <textarea name="deskripsi"></textarea><br><br>

    <button type="submit">Simpan</button>
    <a href="/admin/kursus?tab=paket">Kembali</a>

</form>

<?= $this->endSection(); ?>