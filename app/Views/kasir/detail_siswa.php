<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

<h2 style="margin-bottom:20px;">👤 Detail Siswa</h2>

<!-- ================= INFO ================= -->
<div style="background:#2b2b2b;padding:20px;border-radius:12px;color:white;margin-bottom:20px;">

    <h3>Informasi Siswa</h3>
    <hr style="border-color:#444;">

    <b><?= $siswa['nama']; ?></b><br>
    <?= $siswa['no_hp']; ?><br>
    <?= $siswa['alamat']; ?>

    <hr style="border-color:#444; margin:15px 0;">

    <div style="display:flex; gap:20px;">
        <div>🎯 Aktif: <b style="color:lime;"><?= $total_aktif; ?></b></div>
        <div>📊 Total Riwayat Transaksi: <b><?= $total_riwayat; ?></b></div>
    </div>

</div>

<!-- ================= KURSUS ================= -->
<h3 style="margin-bottom:10px;">📚 Kursus</h3>

<?php if(empty($kursus)): ?>
    <div style="background:#2b2b2b;padding:15px;border-radius:10px;color:#aaa;">
        Tidak ada kursus
    </div>
<?php else: ?>

<?php foreach($kursus as $k): ?>

<div style="background:#2b2b2b;padding:20px;margin-bottom:20px;border-radius:12px;color:white;">

    <h3><?= $k['nama']; ?></h3>
    <hr style="border-color:#444;">

    <?php foreach($k['data'] as $d): ?>

    <div style="background:#1f1f1f;padding:15px;border-radius:10px;margin-bottom:10px;">

        <!-- STATUS -->
        <div style="display:flex;justify-content:space-between;align-items:center;">

            <div>
                <?php if($d['status'] == 'aktif'): ?>
                    <span style="background:lime;color:black;padding:5px 10px;border-radius:20px;">
                        🟢 Aktif
                    </span>
                <?php else: ?>
                    <span style="background:red;color:white;padding:5px 10px;border-radius:20px;">
                        🔴 Selesai
                    </span>
                <?php endif; ?>
            </div>

            <?php if($d['status'] == 'aktif'): ?>
                <div style="color:#ccc;">
                    <?= $d['sisa_hari']; ?> hari lagi
                </div>
            <?php endif; ?>

        </div>

        <hr style="border-color:#333;">

        <!-- DETAIL CERITA -->
        <div style="font-size:14px; line-height:1.6;">

            📌 Mulai sejak:
            <b><?= date('d M Y', strtotime($d['mulai_awal'])); ?></b><br>

            🔄 Perpanjang:
            <b><?= $d['jumlah_perpanjang']; ?>x</b><br>

            📅 Sampai:
            <b><?= date('d M Y', strtotime($d['selesai'])); ?></b><br>

            ⏳ Total durasi:
            <b><?= $d['durasi_hari']; ?> hari</b>

        </div>

        <!-- WARNING -->
        <?php if($d['status'] == 'aktif' && $d['sisa_hari'] <= 7): ?>
            <div style="margin-top:8px;color:orange;">
                ⚠️ Hampir habis
            </div>
        <?php endif; ?>

        <!-- BUTTON -->
        <?php if($d['status'] == 'aktif'): ?>
        <div style="margin-top:10px;">
            <a href="/kasir/perpanjang/<?= $d['id_detail']; ?>"
               style="background:orange;color:black;padding:6px 12px;border-radius:6px;text-decoration:none;">
               🔄 Perpanjang
            </a>
        </div>
        <?php endif; ?>

    </div>

    <?php endforeach; ?>

</div>

<?php endforeach; ?>

<?php endif; ?>

<!-- BACK -->
<a href="/kasir/siswa"
   style="background:#444;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;">
   ← Kembali
</a>

</div>

<?= $this->endSection(); ?>