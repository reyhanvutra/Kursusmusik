<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Data Siswa</h2>

<form method="get" action="">
    <input type="text" name="search" placeholder="Cari nama / no HP" value="<?= esc($search ?? '') ?>">
    <button type="submit">Cari</button>
</form>

<br>

<table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse:collapse;">
<tr style="background:#f0f0f0;">
    <th>Nama</th>
    <th>No HP</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php if(empty($siswa)): ?>
<tr>
    <td colspan="4" style="text-align:center;">Tidak ada data</td>
</tr>
<?php endif; ?>

<?php foreach($siswa as $s): ?>
<tr>
    <td><?= esc($s['nama']); ?></td>
    <td><?= esc($s['no_hp']); ?></td>

    <td>
        <?php if($s['status'] == 'belum'): ?>
            <span style="color:orange; font-weight:bold;">Belum Daftar</span>

        <?php elseif($s['status'] == 'aktif'): ?>
            <span style="color:green; font-weight:bold;">Aktif</span>

        <?php else: ?>
            <span style="color:red; font-weight:bold;">Nonaktif</span>
        <?php endif; ?>
    </td>

    <td>
        <a href="/admin/siswa/detail/<?= $s['id']; ?>">Detail</a> |
        <a href="/admin/siswa/edit/<?= $s['id']; ?>">Edit</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?= $this->endSection(); ?>