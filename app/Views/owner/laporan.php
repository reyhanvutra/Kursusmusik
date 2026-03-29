<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<h1>Laporan Transaksi</h1>

<form method="get" action="/owner/laporan">

    <!-- 🔥 TANGGAL -->
    <label>Tanggal:</label>
    <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>">

    <!-- 🔥 BULAN -->
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
            <option value="<?= $key ?>" <?= (isset($_GET['bulan']) && $_GET['bulan']==$key) ? 'selected' : '' ?>>
                <?= $val ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- 🔥 TAHUN -->
    <label>Tahun:</label>
    <select name="tahun">
        <option value="">--Pilih--</option>
        <?php for($t=2023; $t<=date('Y'); $t++): ?>
            <option value="<?= $t ?>" <?= (isset($_GET['tahun']) && $_GET['tahun']==$t) ? 'selected' : '' ?>>
                <?= $t ?>
            </option>
        <?php endfor; ?>
    </select>

    <!-- 🔍 NAMA -->
    <label>Nama:</label>
    <input type="text" name="nama" value="<?= $_GET['nama'] ?? '' ?>" placeholder="Cari nama...">

    <button type="submit">Filter</button>

    <!-- 🔥 CLEAR -->
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

<!-- 🔥 EXPORT PDF -->
<a href="/owner/laporan/pdf?tanggal=<?= $tanggal ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&nama=<?= $nama ?>" target="_blank">
    Export PDF
</a>

<hr>

<h3>Total: Rp <?= number_format($total,0,',','.'); ?></h3>

<!-- 🔥 NOMOR AUTO LANJUT -->
<?php 
$page = $_GET['page'] ?? 1;
$perPage = 10;
$no = 1 + ($perPage * ($page - 1));
?>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Item</th>
    <th>Total</th>
    <th>Bayar</th>
    <th>Kembali</th>
    <th>Tanggal</th>
</tr>

<?php if(!empty($transaksi)): ?>
    <?php foreach($transaksi as $t): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $t['nama_pembeli']; ?></td>

        <td>
        <?php if($t['tipe'] == 'kursus'): ?>
            <?= $t['nama_kursus']; ?>
        <?php else: ?>
            <?= $t['nama_paket']; ?>
        <?php endif; ?>
        </td>

        <td><?= number_format($t['total_harga'],0,',','.'); ?></td>
        <td><?= number_format($t['uang_bayar'],0,',','.'); ?></td>
        <td><?= number_format($t['uang_kembali'],0,',','.'); ?></td>
        <td><?= date('d-m-Y', strtotime($t['tanggal'])); ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">Data tidak ditemukan</td>
    </tr>
<?php endif; ?>

</table>

<br>

<!-- 🔥 PAGINATION -->
<?= $pager->links(); ?>
<?= $this->endSection(); ?>