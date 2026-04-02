<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Level - <?= $kursus['nama_kursus']; ?></h2>

<a href="/admin/level/tambah/<?= $kursus['id']; ?>">+ Tambah Level</a>
<a href="/admin/kursus" style="margin-left:20px;">← Kembali</a>

<br><br>


<table border="1" cellpadding="10" width="100%">
<tr>
    <th>Urutan</th>
    <th>Nama Level</th>
    <th>Pertemuan</th>
    <th>Harga</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php foreach($level as $l): ?>
<tr>
    <td><?= $l['urutan']; ?></td>
    <td><?= $l['nama_level']; ?></td>
    <td><?= $l['pertemuan']; ?>x</td>
    <td>Rp <?= number_format($l['harga'],0,',','.'); ?></td>
    <td><?= $l['deskripsi']; ?></td>
    <td>
        <a href="/admin/level/edit/<?= $l['id']; ?>">Edit</a> |
        <a href="/admin/level/hapus/<?= $l['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?= $this->endSection(); ?>