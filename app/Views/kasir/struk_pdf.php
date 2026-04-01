<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Struk</title>

<style>
@page {
    size: 80mm auto;
    margin: 5mm;
}

body {
    font-family: monospace;
    font-size: 12px;
    width: 100%;
    max-width: 80mm;
    margin: auto;
    color: black;
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
    float: right;
}

.bold {
    font-weight: bold;
}

.small {
    font-size: 10px;
}

.clear {
    clear: both;
}
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="center">
    <h3 style="margin:0;">KURSUS MUSIK</h3>

    <?php 
    $adaKursus = false;
    $adaPaket = false;

    foreach($detail as $d){
        if($d['tipe'] == 'kursus') $adaKursus = true;
        if($d['tipe'] == 'paket') $adaPaket = true;
    }
    ?>

    <?php if($adaKursus && !$adaPaket): ?>
        <p class="small">Struk Kursus</p>
    <?php elseif($adaPaket && !$adaKursus): ?>
        <p class="small">Struk Paket</p>
    <?php else: ?>
        <p class="small">Kursus & Paket</p>
    <?php endif; ?>
</div>

<hr>

<!-- ================= INFO ================= -->
<?php 
    $tanggal = date('d-m-Y', strtotime($t['tanggal']));
?>

<p>No: TRX-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT); ?></p>
<p>Tanggal: <?= $tanggal; ?></p>
<p>Nama: <?= $t['nama_pembeli']; ?></p>
<p>No HP: <?= $t['no_hp']; ?></p>

<hr>

<!-- ================= ITEM ================= -->
<?php foreach($detail as $d): ?>

<?php 
    $bulan = $d['bulan'] ?? 1;
    $bulan = $bulan > 0 ? $bulan : 1;

    $isKursus = ($d['tipe'] == 'kursus');
    $harga_per_bulan = $isKursus ? ($d['harga'] / $bulan) : $d['harga'];
?>

<div class="item">

    <div>
        <b><?= $d['nama']; ?></b>
        <span class="small">[<?= strtoupper($d['tipe']); ?>]</span>
    </div>

    <div>
        <?php if($isKursus): ?>
            Rp <?= number_format($harga_per_bulan,0,',','.'); ?> x <?= $bulan; ?> bulan
        <?php else: ?>
            Paket Full
        <?php endif; ?>
    </div>

    <?php if($isKursus): ?>
    <div class="small">
        Mulai: <?= $d['tanggal_mulai'] ?? '-' ?><br>
        Selesai: <?= $d['tanggal_selesai'] ?? '-' ?>
    </div>
    <?php endif; ?>

    <div class="right bold">
        Rp <?= number_format($d['harga'],0,',','.'); ?>
    </div>

    <div class="clear"></div>

</div>

<?php endforeach; ?>

<hr>

<!-- ================= BIAYA TAMBAHAN ================= -->
<?php if($t['biaya_pendaftaran'] > 0): ?>
<p>
Biaya Pendaftaran
<span class="right">
Rp <?= number_format($t['biaya_pendaftaran'],0,',','.'); ?>
</span>
</p>
<div class="clear"></div>
<?php endif; ?>

<hr>

<!-- ================= TOTAL ================= -->
<p class="bold">
Total
<span class="right">
Rp <?= number_format($t['total_harga'],0,',','.'); ?>
</span>
</p>
<div class="clear"></div>

<p>
Bayar
<span class="right">
Rp <?= number_format($t['uang_bayar'],0,',','.'); ?>
</span>
</p>
<div class="clear"></div>

<p>
Kembali
<span class="right">
Rp <?= number_format($t['uang_kembali'],0,',','.'); ?>
</span>
</p>
<div class="clear"></div>

<hr>

<!-- ================= FOOTER ================= -->
<div class="center">
    <p>Terima Kasih </p>
</div>

</body>
</html>