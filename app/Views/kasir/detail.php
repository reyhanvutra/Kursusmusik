<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

<h2 style="margin-bottom:15px;">📄 Detail Transaksi</h2>

<div style="background:#2b2b2b; padding:25px; border-radius:12px; color:white;">

<?php $tanggal = date('d F Y', strtotime($t['tanggal'])); ?>

<!-- HEADER -->
<div style="margin-bottom:15px;">
    <div><b>No:</b> TRX-<?= str_pad($t['id'],5,'0',STR_PAD_LEFT); ?></div>
    <div><b>Nama:</b> <?= $t['nama_pembeli']; ?></div>
    <div><b>No HP:</b> <?= $t['no_hp']; ?></div>
    <div><b>Tanggal:</b> <?= $tanggal; ?></div>
</div>

<hr>

<h3>🧾 Item Dibeli</h3>

<?php foreach($detail as $d): ?>

<div style="background:#3a3a3a; padding:15px; border-radius:10px; margin-bottom:12px;">

    <div style="font-weight:bold; font-size:16px;">
        <?= $d['nama']; ?>
    </div>

    <div style="color:cyan; font-size:13px;">
        <?= $d['tipe_label']; ?>
    </div>

    <hr style="border-color:#555;">

   <?php if($d['tipe'] == 'kursus'): ?>

    <div>
        💰 Rp <?= number_format($d['harga_per_bulan'],0,',','.'); ?> 
        x <?= $d['bulan']; ?> bulan
    </div>

    <div style="font-size:13px; color:#ccc;">
        📅 <?= $d['tanggal_mulai_f']; ?> → <?= $d['tanggal_selesai_f']; ?>
    </div>

    <!-- 🔥 KHUSUS PERPANJANG -->
    <?php if(!empty($d['is_perpanjang'])): ?>
        <div style="
            margin-top:10px;
            padding:12px;
            background:#2a2a2a;
            border-left:5px solid orange;
            border-radius:8px;
        ">
            <div style="color:orange; font-weight:bold;">
                🔄 Transaksi Perpanjang
            </div>

            <div style="font-size:13px; margin-top:5px; color:#ccc;">
                ➕ Tambah <?= $d['bulan']; ?> bulan<br>
                📅 Dari: <?= $d['tanggal_mulai_f']; ?><br>
                📅 Sampai: <?= $d['tanggal_selesai_f']; ?>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>

        <div>📦 Paket Full</div>

    <?php endif; ?>

    <div style="margin-top:8px; font-weight:bold; color:lime;">
        Total: Rp <?= number_format($d['harga'],0,',','.'); ?>
    </div>

</div>

<?php endforeach; ?>

<hr>

<?php if($t['biaya_pendaftaran'] > 0): ?>
<p>Biaya Pendaftaran: <b>Rp <?= number_format($t['biaya_pendaftaran'],0,',','.'); ?></b></p>
<?php endif; ?>

<h3>Total: Rp <?= number_format($t['total_harga'],0,',','.'); ?></h3>

<p>Bayar: Rp <?= number_format($t['uang_bayar'],0,',','.'); ?></p>
<p>Kembali: Rp <?= number_format($t['uang_kembali'],0,',','.'); ?></p>

<br>

<a href="/kasir/cetak/<?= $t['id']; ?>" target="_blank"
style="background:green;padding:10px 18px;border-radius:8px;color:white;text-decoration:none;">
🖨️ Cetak Struk
</a>

<?php 
$back = $_SERVER['HTTP_REFERER'] ?? '/kasir/dashboard';
?>

<a href="<?= $back; ?>"
style="background:#555;padding:10px 18px;border-radius:8px;color:white;text-decoration:none;">
⬅️ Kembali
</a>

</div>

</div>

<?= $this->endSection(); ?>