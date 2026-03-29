<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kursus & Paket</title>
    <style>
        body {
            font-family: Arial;
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

<h2>LAPORAN KURSUS & PAKET</h2>

<div class="info">
    <p>Tanggal Cetak: <?= date('d-m-Y'); ?></p>
</div>

<!-- 🔥 FILTER INFO DINAMIS -->
<div class="filter">
    <?php if(!empty($_GET['nama'])): ?>
        <p>Filter Nama: <?= $_GET['nama']; ?></p>
    <?php endif; ?>

    <?php if(!empty($_GET['tipe'])): ?>
        <p>Filter Tipe: <?= ucfirst($_GET['tipe']); ?></p>
    <?php endif; ?>

    <?php if(empty($_GET['nama']) && empty($_GET['tipe'])): ?>
        <p>Semua Data</p>
    <?php endif; ?>
</div>

<table>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Tipe</th>
    <th>Total Terjual</th>
    <th>Total Pendapatan</th>
</tr>

<?php 
$no=1; 
$grand = 0;
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
<?php endforeach; ?>

</table>

<div class="total">
    Total Pendapatan: Rp <?= number_format($grand,0,',','.'); ?>
</div>

</body>
</html>