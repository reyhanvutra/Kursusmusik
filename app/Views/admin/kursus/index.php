<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Data Kursus & Paket</h2>

<!-- NAV TAB -->
<a href="?tab=kursus">Kursus</a> |
<a href="?tab=paket">Paket</a>

<hr>

<?php 
$tab = $_GET['tab'] ?? 'kursus'; 
?>

<?php if($tab == 'kursus'): ?>

<!-- ================= KURSUS ================= -->
<h3>Data Kursus</h3>
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
    <td><img src="/uploads/<?= $k['gambar']; ?>" width="80"></td>
    <td>
        <a href="/admin/kursus/edit/<?= $k['id']; ?>">Edit</a>
        <a href="/admin/kursus/hapus/<?= $k['id']; ?>">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php else: ?>

<!-- ================= PAKET ================= -->
<h3>Data Paket</h3>
<a href="/admin/paket/tambah">Tambah Paket</a>

<table border="1" cellpadding="10">
<tr>
    <th>Nama Paket</th>
    <th>Kursus</th>
    <th>Harga</th>
    <th>Slot</th>
<th>Instruktur</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php foreach($paket as $p): ?>
<tr>
    <td><?= $p['nama_paket']; ?></td>
    <td><?= $p['list_kursus']; ?></td>
    <td><?= $p['harga']; ?></td>
    <td><?= $p['slot']; ?></td>
    <td><?= $p['instruktur']; ?></td>
    <td><?= $p['deskripsi']; ?></td>
    <td>
        <a href="/admin/paket/edit/<?= $p['id']; ?>">Edit</a>
        <a href="/admin/paket/hapus/<?= $p['id']; ?>">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

<?= $this->endSection(); ?>