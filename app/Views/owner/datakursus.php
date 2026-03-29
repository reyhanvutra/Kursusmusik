<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<h1>Laporan Kursus & Paket</h1>

<form method="get" action="/owner/data-kursus">

    <label>Nama:</label>
    <input type="text" name="nama" value="<?= $_GET['nama'] ?? '' ?>">

    <label>Tipe:</label>
    <select name="tipe">
        <option value="">--Semua--</option>
        <option value="kursus" <?= (($_GET['tipe'] ?? '')=='kursus')?'selected':'' ?>>Kursus</option>
        <option value="paket" <?= (($_GET['tipe'] ?? '')=='paket')?'selected':'' ?>>Paket</option>
    </select>

    <button type="submit">Filter</button>

    <a href="/owner/data-kursus">
        <button type="button">Clear</button>
    </a>

</form>

<br>

<?php
$nama = $_GET['nama'] ?? '';
$tipe = $_GET['tipe'] ?? '';
?>

<a href="/owner/data-kursus/pdf?nama=<?= $nama ?>&tipe=<?= $tipe ?>" target="_blank">
    Export PDF
</a>

<hr>

<!-- 🔥 NOMOR LANJUT -->
<?php 
$page = $_GET['page'] ?? 1;
$perPage = 10;
$no = 1 + ($perPage * ($page - 1));
?>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Tipe</th>
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

    <td>
        <?= $d['tipe']=='kursus' ? $d['nama_kursus'] : $d['nama_paket']; ?>
    </td>

    <td><?= ucfirst($d['tipe']); ?></td>

    <td><?= $d['total_terjual']; ?></td>

    <td>Rp <?= number_format($d['total_pendapatan'],0,',','.'); ?></td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="5">Data tidak ditemukan</td>
</tr>
<?php endif; ?>

</table>

<h3>Total Semua Pendapatan: Rp <?= number_format($grand,0,',','.'); ?></h3>

<br>

<!-- 🔥 PAGINATION -->
<?= $pager->links(); ?>
<?= $this->endSection(); ?>