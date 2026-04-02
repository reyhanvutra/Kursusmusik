<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Struk</title>

<style>
@page {
    margin: 5mm;
}

body {
    font-family: monospace;
    font-size: 11px;
    width: 100%;
}

.container {
    width: 70mm; /* 🔥 lebih aman dari 80mm */
    margin: auto;
}

.center { text-align:center; }
.right { float:right; }
.clear { clear:both; }

hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 6px 0;
}

.item {
    margin-bottom: 5px;
}

.small {
    font-size: 10px;
}
</style>
</head>

<body>

<div class="container">

<div class="center">
    <h3 style="margin:0;">KURSUS MUSIK</h3>
    <small>Struk Pembayaran</small>
</div>

<hr>

<p>No: TRX-<?= str_pad($t['id'],5,'0',STR_PAD_LEFT); ?></p>
<p>Tanggal: <?= date('d-m-Y', strtotime($t['tanggal'])); ?></p>
<p>Nama: <?= $t['nama_pembeli']; ?></p>

<hr>

<?php foreach($detail as $d): ?>

<div class="item">

<b><?= $d['nama']; ?></b><br>

<?php if($d['tipe'] == 'kursus'): ?>
    <?= number_format($d['harga'] / $d['bulan'],0,',','.'); ?> x <?= $d['bulan']; ?> bulan<br>
    <span class="small">
        <?= date('d-m-Y', strtotime($d['tanggal_mulai'])); ?> 
        s/d 
        <?= date('d-m-Y', strtotime($d['tanggal_selesai'])); ?>
    </span>
<?php endif; ?>

<div>
    <span class="right">
        Rp <?= number_format($d['harga'],0,',','.'); ?>
    </span>
    <div class="clear"></div>
</div>

</div>

<hr>

<?php endforeach; ?>

<?php if($t['biaya_pendaftaran'] > 0): ?>
<p>
Biaya Daftar
<span class="right">
Rp <?= number_format($t['biaya_pendaftaran'],0,',','.'); ?>
</span>
</p>
<div class="clear"></div>
<?php endif; ?>

<hr>

<p>
<b>Total</b>
<span class="right">
Rp <?= number_format($t['total_harga'],0,',','.'); ?>
</span>
</p>

<p>
Bayar
<span class="right">
Rp <?= number_format($t['uang_bayar'],0,',','.'); ?>
</span>
</p>

<p>
Kembali
<span class="right">
Rp <?= number_format($t['uang_kembali'],0,',','.'); ?>
</span>
</p>

<hr>

<div class="center">
    Terima Kasih 🙏
</div>

</div>

</body>
</html>