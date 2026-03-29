<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            text-align: center;
            margin-bottom: 10px;
        }

        .filter {
            margin-bottom: 15px;
        }

        .filter p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #eee;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        .total {
            margin-top: 10px;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>LAPORAN TRANSAKSI</h2>

<div class="info">
    <p>Tanggal Cetak: <?= date('d-m-Y'); ?></p>
</div>

<!-- 🔥 FILTER DINAMIS -->
<div class="filter">

    <?php if(!empty($_GET['tanggal'])): ?>
        <p><b>Tanggal:</b> <?= date('d-m-Y', strtotime($_GET['tanggal'])); ?></p>
    <?php endif; ?>

    <?php if(!empty($_GET['bulan'])): ?>
        <p><b>Bulan:</b> <?= $_GET['bulan']; ?></p>
    <?php endif; ?>

    <?php if(!empty($_GET['nama'])): ?>
        <p><b>Nama:</b> <?= $_GET['nama']; ?></p>
    <?php endif; ?>

    <?php if(empty($_GET['tanggal']) && empty($_GET['bulan']) && empty($_GET['nama'])): ?>
        <p><b>Filter:</b> Semua Data</p>
    <?php endif; ?>

</div>

<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Item</th>
    <th>Jenis</th>
    <th>Harga Item</th>
    <th>Total Transaksi</th>
    <th>Tanggal</th>
</tr>

<?php 
$no = 1; 
$grand_total = 0;
foreach($transaksi as $t): 
    $grand_total += $t['total_harga'];
?>
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

    <td><?= ucfirst($t['tipe']); ?></td>

    <td>Rp <?= number_format($t['harga_item'] ?? 0,0,',','.'); ?></td>

    <td>Rp <?= number_format($t['total_harga'],0,',','.'); ?></td>

    <td><?= date('d-m-Y', strtotime($t['tanggal'])); ?></td>
</tr>
<?php endforeach; ?>

</table>

<!-- 🔥 TOTAL -->
<div class="total">
    Total Pendapatan: Rp <?= number_format($grand_total,0,',','.'); ?>
</div>

</body>
</html>