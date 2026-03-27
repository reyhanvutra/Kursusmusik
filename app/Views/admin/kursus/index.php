<?= $this->extend('admin/layout'); ?> 
<?= $this->section('content'); ?>

<h2>Data Kursus & Paket</h2>

<!-- NAV TAB -->
<a href="?tab=kursus">Kursus</a> |
<a href="?tab=paket">Paket</a>

<hr>

<?php $tab = $_GET['tab'] ?? 'kursus'; ?>

<?php if($tab == 'kursus'): ?>

<!-- ================= KURSUS ================= -->
<h3>Data Kursus</h3>
<a href="/admin/kursus/tambah">+ Tambah Kursus</a>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>Nama</th>
    <th>Harga</th>
    <th>Instruktur</th>
    <th>Durasi</th>
    <th>Jadwal</th>
    <th>Slot</th>
    <th>Gambar</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php foreach($kursus as $k): ?>
<tr>
    <td><?= $k['nama_kursus']; ?></td>
    <td>Rp <?= number_format($k['harga'],0,',','.'); ?></td>
    <td><?= $k['instruktur']; ?></td>
    <td><?= $k['durasi']; ?></td>
    <td><?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?></td>
    <td><?= $k['slot']; ?></td>
    <td>
        <?php if($k['gambar']): ?>
            <img src="/uploads/<?= $k['gambar']; ?>" width="70">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td><?= $k['deskripsi']; ?></td>
    <td>
        <a href="/admin/kursus/edit/<?= $k['id']; ?>">Edit</a> |
        <a href="/admin/kursus/hapus/<?= $k['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php else: ?>

<!-- ================= PAKET ================= -->
<h3>Data Paket</h3>
<a href="/admin/paket/tambah">+ Tambah Paket</a>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>Nama Paket</th>
    <th>Jumlah Kursus</th>
    <th>List Kursus</th>
    <th>Harga</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php foreach($paket as $p): ?>
<tr>
    <td><?= $p['nama_paket']; ?></td>
    <td><?= substr_count($p['list_kursus'], ',') + 1; ?></td>
    <td><?= $p['list_kursus']; ?></td>
    <td>Rp <?= number_format($p['harga'],0,',','.'); ?></td>
    <td><?= $p['deskripsi']; ?></td>
    <td>
        <a href="/admin/paket/edit/<?= $p['id']; ?>">Edit</a> |
        <a href="/admin/paket/hapus/<?= $p['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

<?= $this->endSection(); ?>