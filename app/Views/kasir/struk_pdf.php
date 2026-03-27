<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk</title>

    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 250px;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        hr {
            border: 0;
            border-top: 1px dashed black;
            margin: 8px 0;
        }

        .item {
            margin-bottom: 8px;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="center">
    <h3>KURSUS MUSIK</h3>
    <p>Struk Transaksi</p>
</div>

<hr>

<p>ID: <?= $t['id']; ?></p>
<p>Tanggal: <?= $t['tanggal']; ?></p>
<p>Nama: <?= $t['nama_pembeli']; ?></p>
<p>No HP: <?= $t['no_hp']; ?></p>

<hr>

<?php foreach($detail as $d): ?>

<?php 
    $bulan = $d['bulan'] ?? 1;
    $bulan = $bulan > 0 ? $bulan : 1;

    $harga_per_bulan = $d['harga'] / $bulan;
?>

<div class="item">
    <div><?= $d['nama']; ?></div>

    <div>
        Rp <?= number_format($harga_per_bulan,0,',','.'); ?> 
        x <?= $bulan; ?> bulan
    </div>

    <div class="right bold">
        Rp <?= number_format($d['harga'],0,',','.'); ?>
    </div>
</div>

<?php endforeach; ?>

<hr>

<p>Total: 
<span class="right bold">
Rp <?= number_format($t['total_harga'],0,',','.'); ?>
</span>
</p>

<p>Bayar: 
<span class="right">
Rp <?= number_format($t['uang_bayar'],0,',','.'); ?>
</span>
</p>

<p>Kembali: 
<span class="right">
Rp <?= number_format($t['uang_kembali'],0,',','.'); ?>
</span>
</p>

<hr>

<div class="center">
    Terima Kasih
</div>

<script>
window.onload = function(){
    window.print();
}
</script>

</body>
</html>