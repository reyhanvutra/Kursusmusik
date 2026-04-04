<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>👤 Detail Siswa</h2>

<?php
$today = date('Y-m-d');
$aktif = false;

// 🔥 CEK SEMUA TRANSAKSI
foreach($transaksi as $t){
    if(!empty($t['detail'])){
        foreach($t['detail'] as $d){
            if($d['tanggal_selesai'] >= $today){
                $aktif = true;
            }
        }
    }
}
?>

<div style="background:#f8f9fa;padding:20px;border-radius:10px;margin-bottom:20px;">

    <p><b>Nama:</b> <?= esc($s['nama']); ?></p>
    <p><b>No HP:</b> <?= esc($s['no_hp']); ?></p>
    <p><b>Alamat:</b> <?= esc($s['alamat']); ?></p>

    <p>
        <b>Status:</b>

        <?php if($s['sudah_daftar'] == 0): ?>
            <span style="color:orange;">🟠 Belum Daftar</span>

        <?php elseif($aktif): ?>
            <span style="color:green;">🟢 Aktif</span>

        <?php else: ?>
            <span style="color:red;">🔴 Nonaktif</span>
        <?php endif; ?>

    </p>

</div>

<hr>

<h3>📋 Riwayat Transaksi</h3>

<?php if (empty($transaksi)): ?>
    <p><i>Belum ada transaksi</i></p>
<?php else: ?>

    <?php foreach ($transaksi as $t): ?>

        <div style="background:white;border-radius:10px;padding:15px;margin-bottom:20px;box-shadow:0 2px 5px rgba(0,0,0,0.1);">

            <p><b>No:</b> TRX-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT); ?></p>
            <p><b>Tanggal:</b> <?= date('d-m-Y', strtotime($t['tanggal'])); ?></p>
            <p><b>Total:</b> Rp <?= number_format($t['total_harga'] ?? 0, 0, ',', '.'); ?></p>

            <br>

            <?php if (!empty($t['detail'])): ?>

                <table width="100%" border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
                    <tr style="background:#eee;">
                        <th>Kursus</th>
                        <th>Kategori</th>
                        <th>Level</th>
                        <th>Harga</th>
                        <th>Bulan</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Status</th>
                    </tr>

                    <?php foreach ($t['detail'] as $d): ?>

                        <tr>
                            <td><?= esc($d['nama_kursus']); ?></td>
                            <td><?= esc($d['kategori']); ?></td>
                            <td><?= esc($d['level']); ?></td>

                            <td>
                                Rp <?= number_format($d['harga'], 0, ',', '.'); ?>
                            </td>

                            <td><?= esc($d['bulan']); ?></td>

                            <td><?= esc($d['tanggal_mulai']); ?></td>
                            <td><?= esc($d['tanggal_selesai']); ?></td>

                            <td>
                                <?php if ($d['tanggal_selesai'] >= date('Y-m-d')): ?>
                                    <span style="color:green;">Aktif</span>
                                <?php else: ?>
                                    <span style="color:red;">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </table>

            <?php else: ?>
                <p><i>Tidak ada detail transaksi</i></p>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<br>

<a href="/admin/siswa" 
   style="background:#555;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;">
   ← Kembali
</a>

<?= $this->endSection(); ?>