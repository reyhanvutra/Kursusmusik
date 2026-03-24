<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Data Kursus</h2>

<a href="/admin/kursus/tambah">+ Tambah Kursus</a>

<table border="1" cellpadding="10">
<tr>
    <th>Nama</th>
    <th>Harga</th>
    <th>Instruktur</th>
    <th>Durasi</th>
    <th>Slot</th>
    <th>Gambar</th>
    <th>Aksi</th>
</tr>

<?php foreach($kursus as $k): ?>
<tr>
    <td><?= $k['nama_kursus']; ?></td>
    <td><?= $k['harga']; ?></td>
    <td><?= $k['instruktur']; ?></td>
    <td><?= $k['durasi']; ?></td>
    <td><?= $k['slot']; ?></td>
    <td>
        <img src="/uploads/<?= $k['gambar']; ?>" width="80">
    </td>
    <td>
        <a href="/admin/kursus/edit/<?= $k['id']; ?>">Edit</a>
        <a href="/admin/kursus/hapus/<?= $k['id']; ?>">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?= $this->endSection(); ?>