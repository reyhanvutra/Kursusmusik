<h2>Edit Kategori</h2>

<form action="/admin/kategori/update/<?= $kategori['id'] ?>" method="post">
    <label>Nama Kategori</label><br>
    <input type="text" name="nama_kategori" value="<?= $kategori['nama_kategori'] ?>" required><br><br>

    <label>Deskripsi</label><br>
    <textarea name="deskripsi"><?= $kategori['deskripsi'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>