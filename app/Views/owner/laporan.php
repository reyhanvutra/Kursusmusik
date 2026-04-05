<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>

<h1>Laporan Transaksi Kursus</h1>

<form method="get" action="/owner/laporan">

    <label>Tanggal:</label>
    <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>">

    <label>Bulan:</label>
    <?php 
    $bulanList = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
        5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
        9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];
    ?>
    <select name="bulan">
        <option value="">--Pilih--</option>
        <?php foreach($bulanList as $key => $val): ?>
            <option value="<?= $key ?>" <?= (($_GET['bulan'] ?? '')==$key)?'selected':'' ?>>
                <?= $val ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Tahun:</label>
    <select name="tahun">
        <option value="">--Pilih--</option>
        <?php for($t=2023; $t<=date('Y'); $t++): ?>
            <option value="<?= $t ?>" <?= (($_GET['tahun'] ?? '')==$t)?'selected':'' ?>>
                <?= $t ?>
            </option>
        <?php endfor; ?>
    </select>

    <label>Nama Siswa:</label>
    <input type="text" name="nama" value="<?= $_GET['nama'] ?? '' ?>">

    <button type="submit">Filter</button>

    <a href="/owner/laporan">
        <button type="button">Clear</button>
    </a>

</form>

<br>

<?php
$tanggal = $_GET['tanggal'] ?? '';
$bulan   = $_GET['bulan'] ?? '';
$tahun   = $_GET['tahun'] ?? '';
$nama    = $_GET['nama'] ?? '';
?>

<a href="/owner/laporan/pdf?tanggal=<?= $tanggal ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&nama=<?= $nama ?>" target="_blank">
    Export PDF
</a>

<hr>

<h3>Total Pendapatan: Rp <?= number_format($total,0,',','.'); ?></h3>

<?php 
$page = $_GET['page'] ?? 1;
$perPage = 10;
$no = 1 + ($perPage * ($page - 1));
?>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>No</th>
    <th>Nama Siswa</th>
    <th>Kursus</th>
    <th>Kategori</th>
    <th>Level</th>
    <th>Total</th>
    <th>Bayar</th>
    <th>Kembali</th>
    <th>Tanggal</th>
</tr>

<?php if(!empty($transaksi)): ?>
    <?php foreach($transaksi as $t): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $t['nama_siswa']; ?></td>
        <td><?= $t['nama_kursus']; ?></td>
        <td><?= $t['nama_kategori']; ?></td>
        <td><?= $t['nama_level']; ?></td>
        <td>Rp <?= number_format($t['total_harga'],0,',','.'); ?></td>
        <td>Rp <?= number_format($t['uang_bayar'],0,',','.'); ?></td>
        <td>Rp <?= number_format($t['uang_kembali'],0,',','.'); ?></td>
        <td><?= date('d-m-Y', strtotime($t['tanggal'])); ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" align="center">Data tidak ditemukan</td>
    </tr>
<?php endif; ?>

</table>

<br>

<?= $pager->links(); ?>

<?= $this->endSection(); ?>