<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/detail_siswa.css'); ?>">

<div class="user-header-flex">
    <div>
        <h2 class="page-header" style="margin-top: 10px; color: white;">Detail Siswa</h2>
    </div>
</div>

<?php
$today = date('Y-m-d');
$aktif = false;

// 🔥 LOGIKA ASLI TETAP DIJAGA: CEK STATUS AKTIF
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

<div class="profile-card">
    <div class="profile-avatar">
        <i class="fa-solid fa-user-graduate"></i>
    </div>
    <div class="profile-info">
        <h3><?= esc($s['nama']); ?></h3>
        <div class="profile-meta">
            <span><i class="fa-solid fa-phone"></i> <?= esc($s['no_hp']); ?></span>
            <span><i class="fa-solid fa-location-dot"></i> <?= esc($s['alamat']); ?></span>
            <span>
                <b>Status:</b>
                <?php if($s['sudah_daftar'] == 0): ?>
                    <span style="color:orange;">🟠 Belum Daftar</span>
                <?php elseif($aktif): ?>
                    <span style="color:green;">🟢 Aktif</span>
                <?php else: ?>
                    <span style="color:red;">🔴 Nonaktif</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>

<hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.05); margin: 30px 0;">

<h3 style="color: #fff; margin-bottom: 20px;">📋 Riwayat Transaksi</h3>

<?php if (empty($transaksi)): ?>
    <div class="empty-history">
        <p><i>Belum ada transaksi</i></p>
    </div>
<?php else: ?>

    <?php foreach ($transaksi as $t): ?>
        <div class="transaction-item">
            <div class="transaction-header">
                <div class="trx-id">TRX-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT); ?></div>
                <div class="trx-date"><?= date('d-m-Y', strtotime($t['tanggal'])); ?></div>
                <div class="trx-amount">Rp <?= number_format($t['total_harga'] ?? 0, 0, ',', '.'); ?></div>
            </div>

            <?php if (!empty($t['detail'])): ?>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>Kursus</th>
                            <th>Kategori / Level</th>
                            <th>Bulan</th>
                            <th>Masa Aktif</th>
                            <th style="text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($t['detail'] as $d): ?>
                            <tr>
                                <td><span class="course-name"><?= esc($d['nama_kursus']); ?></span></td>
                                <td>
                                    <span style="color: #ccc;"><?= esc($d['kategori']); ?></span><br>
                                    <small style="color: #666;"><?= esc($d['level']); ?></small>
                                </td>
                                <td><?= esc($d['bulan']); ?></td>
                                <td>
                                    <small style="color: #888;">
                                        <?= date('d/m/y', strtotime($d['tanggal_mulai'])); ?> - <?= date('d/m/y', strtotime($d['tanggal_selesai'])); ?>
                                    </small>
                                </td>
                                <td style="text-align: right;">
                                    <?php if ($d['tanggal_selesai'] >= date('Y-m-d')): ?>
                                        <span class="status-active">Aktif</span>
                                    <?php else: ?>
                                        <span class="status-expired">Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="padding: 20px; color: #555;"><i>Tidak ada detail transaksi</i></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<br>
<a href="/admin/siswa" class="btn-back" style="display:inline-block; background:#333; color:white; padding:10px 20px; border-radius:12px; text-decoration:none; font-weight:bold;">
    ← Kembali ke Daftar Siswa
</a>

<?= $this->endSection(); ?>