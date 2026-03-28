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
    text-align: right;
}

.bold {
    font-weight: bold;
}

.small {
    font-size: 10px;
}

/* tombol tidak ikut ke print */
.no-print {
    margin-top: 10px;
}

@media print {
    .no-print {
        display: none;
    }
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

<p>ID: <?= $t['id']; ?></p>
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
        <b>
            <?= $isKursus ? '' : ''; ?> 
            <?= $d['nama']; ?>
        </b>
        <span class="small">
            [<?= strtoupper($d['tipe']); ?>]
        </span>
    </div>

    <div>
        <?php if($isKursus): ?>
            Rp <?= number_format($harga_per_bulan,0,',','.'); ?> 
            x <?= $bulan; ?> bulan
        <?php else: ?>
            Paket Full
        <?php endif; ?>
    </div>

    <div class="right bold">
        Rp <?= number_format($d['harga'],0,',','.'); ?>
    </div>

</div>

<?php endforeach; ?>

<hr>

<!-- ================= TOTAL ================= -->
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

<!-- ================= FOOTER ================= -->
<div class="center">
    <p>Terima Kasih </p>
</div>


</body>
</html>