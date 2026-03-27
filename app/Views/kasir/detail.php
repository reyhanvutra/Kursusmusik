<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>Detail Transaksi</h2>

    <div class="card card-dark" style="padding:20px;">

        <p><b>Nama:</b> <?= $t['nama_pembeli']; ?></p>
        <p><b>No HP:</b> <?= $t['no_hp']; ?></p>
        <p><b>Tanggal:</b> <?= $t['tanggal']; ?></p>

        <hr>

        <h4>Item Dibeli:</h4>

        <?php foreach($detail as $d): ?>
            <div style="margin-bottom:10px;">
                <?= $d['nama']; ?> 
                - Rp <?= number_format($d['harga'],0,',','.'); ?>
            </div>
        <?php endforeach; ?>

        <hr>

        <p><b>Total:</b> Rp <?= number_format($t['total_harga'],0,',','.'); ?></p>
        <p><b>Bayar:</b> Rp <?= number_format($t['uang_bayar'],0,',','.'); ?></p>
        <p><b>Kembali:</b> Rp <?= number_format($t['uang_kembali'],0,',','.'); ?></p>

        <br>

        <!-- tombol -->
        <a href="/kasir/cetak/<?= $t['id']; ?>" target="_blank"
           style="background:green;color:white;padding:8px 15px;border-radius:6px;text-decoration:none;">
           Cetak Struk
        </a>

        <a href="/kasir/dashboard"
           style="background:#555;color:white;padding:8px 15px;border-radius:6px;text-decoration:none;">
           Kembali
        </a>

    </div>

</div>

<?= $this->endSection(); ?>