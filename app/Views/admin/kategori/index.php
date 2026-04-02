<?= $this->extend('admin/layout'); ?> 
<?= $this->section('content'); ?>
<h2>Kategori Kursus</h2>

<a href="/admin/kategori/create">+ Tambah Kategori</a>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama Kategori</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>

    <?php $no=1; foreach($kategori as $k): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $k['nama_kategori'] ?></td>
        <td><?= $k['deskripsi'] ?></td>
        <td>
            <a href="/admin/kategori/edit/<?= $k['id'] ?>">Edit</a>
            <a href="/admin/kategori/delete/<?= $k['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?= $this->endSection(); ?>