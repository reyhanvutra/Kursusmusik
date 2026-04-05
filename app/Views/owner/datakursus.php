<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>

<h1>Laporan Data Kursus</h1>

<form method="get" action="/owner/data-kursus">

    <label>Nama Kursus:</label>
    <input type="text" name="nama" value="<?= $_GET['nama'] ?? '' ?>">

    <button type="submit">Filter</button>

    <a href="/owner/data-kursus">
        <button type="button">Clear</button>
    </a>

</form>

<br>

<?php
$nama = $_GET['nama'] ?? '';
?>

<a href="/owner/data-kursus/pdf?nama=<?= $nama ?>" target="_blank">
    Export PDF
</a>

<hr>

<?php 
$page = $_GET['page'] ?? 1;
$perPage = 10;
$no = 1 + ($perPage * ($page - 1));
?>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>No</th>
    <th>Kursus</th>
    <th>Kategori</th>
    <th>Level</th>
    <th>Total Terjual</th>
    <th>Total Pendapatan</th>
</tr>

<?php 
$grand = 0;
if(!empty($data)):
foreach($data as $d): 
$grand += $d['total_pendapatan'];
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $d['nama_kursus']; ?></td>
    <td><?= $d['nama_kategori']; ?></td>
    <td><?= $d['nama_level']; ?></td>
    <td><?= $d['total_terjual']; ?></td>
    <td>Rp <?= number_format($d['total_pendapatan'],0,',','.'); ?></td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="6" align="center">Data tidak ditemukan</td>
</tr>
<?php endif; ?>

</table>

<h3>Total Semua Pendapatan: Rp <?= number_format($grand,0,',','.'); ?></h3>

<br>

<?= $pager->links(); ?>

<?= $this->endSection(); ?>