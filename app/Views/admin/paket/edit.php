<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit Paket Kursus</h2>

<form action="/admin/paket/update/<?= $paket['id']; ?>" method="post">

    <label>Nama Paket</label><br>
    <input type="text" name="nama_paket" value="<?= $paket['nama_paket']; ?>"><br><br>

    <label>Harga</label><br>
    <input type="number" name="harga" value="<?= $paket['harga']; ?>"><br><br>

    <label>Pilih Kursus</label><br>

    <?php 
    // ambil id kursus yang sudah dipilih
    $selected = array_column($detail, 'id_kursus');
    ?>

    <?php foreach($kursus as $k): ?>
        <input type="checkbox" name="kursus[]" value="<?= $k['id']; ?>"
            <?= in_array($k['id'], $selected) ? 'checked' : ''; ?>>
        <?= $k['nama_kursus']; ?><br>
    <?php endforeach; ?>

    <br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"><?= $paket['deskripsi']; ?></textarea><br><br>

    <button type="submit">Update</button>
    <a href="/admin/kursus?tab=paket">Kembali</a>

</form>

<?= $this->endSection(); ?>