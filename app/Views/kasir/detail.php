<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/transaksi_detail.css'); ?>">

<div class="detail-container">
    <div class="invoice-box">
        
        <div class="invoice-id">TRX-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT); ?></div>
        
        <div class="invoice-header">
            <div class="customer-info">
                <h2><?= $t['nama_pembeli']; ?></h2>
                <p><i class="fa-solid fa-phone"></i> <?= $t['no_hp']; ?></p>
                <p><i class="fa-solid fa-calendar"></i> <?= date('d F Y', strtotime($t['tanggal'])); ?></p>
            </div>
            <div style="text-align: right;">
                <span class="item-label" style="background:rgba(0,255,128,0.1); color:var(--success); font-size:12px; padding:8px 15px;">
                    Lunas
                </span>
            </div>
        </div>

        <div class="section-title">
            <i class="fa-solid fa-receipt"></i> Detail Item
        </div>

        <?php foreach($detail as $d): ?>
        <div class="item-card">
            <div class="item-header">
                <div class="item-name"><?= $d['nama']; ?></div>
                <div class="item-label"><?= $d['tipe_label']; ?></div>
            </div>

            <div class="item-details">
                <?php if($d['tipe'] == 'kursus'): ?>
                    <div style="margin-bottom:5px;">
                        💰 Rp <?= number_format($d['harga_per_bulan'],0,',','.'); ?> x <?= $d['bulan']; ?> Bulan
                    </div>
                    <div style="font-size:12px; opacity:0.7;">
                        <i class="fa-solid fa-clock"></i> Periode: <?= $d['tanggal_mulai_f']; ?> — <?= $d['tanggal_selesai_f']; ?>
                    </div>

                    <?php if(!empty($d['is_perpanjang'])): ?>
                        <div class="perpanjang-notice">
                            <strong>🔄 Perpanjangan Aktif</strong><br>
                            Menambahkan masa aktif kursus selama <?= $d['bulan']; ?> bulan ke depan.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div><i class="fa-solid fa-box-open"></i> Paket Produk Full</div>
                <?php endif; ?>
            </div>

            <div style="text-align: right; margin-top: 10px; font-weight: 800; color: var(--success);">
                Rp <?= number_format($d['harga'],0,',','.'); ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="summary-area">
            <?php if($t['biaya_pendaftaran'] > 0): ?>
            <div class="summary-line">
                <span>Biaya Pendaftaran</span>
                <span>Rp <?= number_format($t['biaya_pendaftaran'],0,',','.'); ?></span>
            </div>
            <?php endif; ?>

            <div class="summary-line total">
                <span>Total Tagihan</span>
                <span style="color: var(--success);">Rp <?= number_format($t['total_harga'],0,',','.'); ?></span>
            </div>

            <div class="payment-status">
                <div>
                    <span style="display:block; font-size:10px; color:var(--text-dim); text-transform:uppercase;">Dibayar</span>
                    <strong>Rp <?= number_format($t['uang_bayar'],0,',','.'); ?></strong>
                </div>
                <div>
                    <span style="display:block; font-size:10px; color:var(--text-dim); text-transform:uppercase;">Kembali</span>
                    <strong>Rp <?= number_format($t['uang_kembali'],0,',','.'); ?></strong>
                </div>
            </div>
        </div>
        <?php 
$back = $_SERVER['HTTP_REFERER'] ?? '/kasir/dashboard';
?>

        <div class="action-area">
            <a href="/kasir/cetak/<?= $t['id']; ?>" target="_blank" class="btn-print">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </a>
            <a href="<?= $back; ?>" class="btn-back-detail">
                Kembali 
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>