<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/dashboard_kasir.css'); ?>">

<div class="form-page-wrapper">

    <div class="form-header-area">
        <h2 class="page-header">Semua Riwayat Transaksi</h2>
        <p style="color: #555;">Data lengkap transaksi kasir</p>
    </div>

    <div style="margin-bottom:15px;">
        <a href="/kasir/dashboard" class="btn-nav secondary">← Kembali ke Dashboard</a>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 style="color: white; margin: 0; font-weight: 600;">Riwayat Transaksi</h3>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelanggan / Siswa</th>
                    <th>Kursus yang Diambil</th>
                    <th>Total Bayar</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($transaksi)): ?>
                    <?php foreach($transaksi as $t): ?>
                    <tr>
                        <td style="color: #777;">
                            <?= date('d/m/Y', strtotime($t['tanggal'])); ?>
                        </td>

                        <td>
                            <strong style="color: #eee;">
                                <?= $t['nama_pembeli']; ?>
                            </strong>
                        </td>

                        <td>
                            <?php if(!empty($t['items'])): ?>
                                <div style="display:flex; gap:5px; flex-wrap:wrap;">
                                    <?php foreach($t['items'] as $item): ?>
                                        <span class="badge-item"><?= $item; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td style="color:#ff4d4d; font-weight:700;">
                            Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?>
                        </td>

                        <td style="text-align:center;">
                            <a href="/kasir/detail/<?= $t['id']; ?>" class="btn-detail">
                                Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div style="margin-top:15px;">
            <?= $pager->links(); ?>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>