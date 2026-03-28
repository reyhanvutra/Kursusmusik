<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>Detail Transaksi</h2>

    <div style="background:#2b2b2b; padding:20px; border-radius:10px;">

        <?php 
            $tanggal = date('d F Y', strtotime($t['tanggal']));
        ?>

        <p><b>ID:</b> #<?= $t['id']; ?></p>
        <p><b>Nama:</b> <?= $t['nama_pembeli']; ?></p>
        <p><b>No HP:</b> <?= $t['no_hp']; ?></p>
        <p><b>Tanggal:</b> <?= $tanggal; ?></p>

        <hr>

        <h4>Item Dibeli</h4>

        <?php foreach($detail as $d): ?>

            <div style="margin-bottom:10px; padding:10px; background:#3a3a3a; border-radius:8px;">

                <!-- ✅ LANGSUNG PAKAI DATA DARI CONTROLLER -->
                <b><?= $d['nama']; ?></b><br>

                <small style="color:<?= $d['tipe'] == 'kursus' ? 'cyan' : 'lime'; ?>;">
                    <?= $d['tipe_label']; ?>
                </small><br>

                <?php if($d['tipe'] == 'kursus'): ?>
                    Rp <?= number_format($d['harga_per_bulan'],0,',','.'); ?> 
                    x <?= $d['bulan']; ?> bulan
                <?php else: ?>
                    Rp <?= number_format($d['harga'],0,',','.'); ?>
                <?php endif; ?>

                <br>
                <b>Total: Rp <?= number_format($d['harga'],0,',','.'); ?></b>

            </div>

        <?php endforeach; ?>

        <hr>

        <p><b>Total:</b> Rp <?= number_format($t['total_harga'],0,',','.'); ?></p>
        <p><b>Bayar:</b> Rp <?= number_format($t['uang_bayar'],0,',','.'); ?></p>
        <p><b>Kembali:</b> Rp <?= number_format($t['uang_kembali'],0,',','.'); ?></p>

        <br>

        <!-- tombol -->
        <a href="/kasir/cetak/<?= $t['id']; ?>" target="_blank"
           style="background:green;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">
           🖨️ Cetak Struk
        </a>

        <a href="/kasir/dashboard"
           style="background:#555;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">
           ⬅️ Dashboard
        </a>

    </div>

</div>

<?= $this->endSection(); ?>