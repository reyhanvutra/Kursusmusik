<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/owner/laporan.css'); ?>">

<div class="report-container">
    <div class="header-action">
        <h2 class="page-title">Laporan Transaksi</h2>
        <div class="total-badge">
            <span class="label">Total Pendapatan:</span>
            <span class="value">Rp <?= number_format($total, 0, ',', '.'); ?></span>
        </div>
    </div>

    <div class="card card-filter">
        <form method="get" action="/owner/laporan" class="filter-grid">
            <div class="input-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="<?= $_GET['tanggal'] ?? '' ?>">
            </div>

            <div class="input-group">
                <label>Bulan</label>
                <?php 
                $bulanList = [
                    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                ];
                ?>
                <select name="bulan">
                    <option value="">-- Semua Bulan --</option>
                    <?php foreach($bulanList as $key => $val): ?>
                        <option value="<?= $key ?>" <?= (($_GET['bulan'] ?? '')==$key)?'selected':'' ?>>
                            <?= $val ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <label>Tahun</label>
                <select name="tahun">
                    <option value="">-- Semua Tahun --</option>
                    <?php for($t=2023; $t<=date('Y'); $t++): ?>
                        <option value="<?= $t ?>" <?= (($_GET['tahun'] ?? '')==$t)?'selected':'' ?>>
                            <?= $t ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="input-group">
                <label>Nama Siswa</label>
                <input type="text" name="nama" placeholder="Cari nama..." value="<?= $_GET['nama'] ?? '' ?>">
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-filter"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
                <a href="/owner/laporan" class="btn btn-clear"><i class="fa-solid fa-rotate-left"></i> Clear</a>
                
                <?php
                $tanggal = $_GET['tanggal'] ?? '';
                $bulan   = $_GET['bulan'] ?? '';
                $tahun   = $_GET['tahun'] ?? '';
                $nama    = $_GET['nama'] ?? '';
                ?>
                <a href="/owner/laporan/pdf?tanggal=<?= $tanggal ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&nama=<?= $nama ?>" 
                   target="_blank" class="btn btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    <div class="card card-table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kursus</th>
                    <th>Detail</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembali</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $page = $_GET['page'] ?? 1;
                $perPage = 10;
                $no = 1 + ($perPage * ($page - 1));
                ?>
                <?php if(!empty($transaksi)): ?>
                    <?php foreach($transaksi as $t): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td>
                            <div class="student-name"><?= $t['nama_siswa']; ?></div>
                        </td>
                        <td><?= $t['nama_kursus']; ?></td>
                        <td>
                            <small class="text-muted"><?= $t['nama_kategori']; ?> • <?= $t['nama_level']; ?></small>
                        </td>
                        <td class="text-white">Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?></td>
                        <td>Rp <?= number_format($t['uang_bayar'], 0, ',', '.'); ?></td>
                        <td class="text-accent">Rp <?= number_format($t['uang_kembali'], 0, ',', '.'); ?></td>
                        <td><span class="badge-date"><?= date('d M Y', strtotime($t['tanggal'])); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">Data tidak ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        <?= $pager->links(); ?>
    </div>
</div>

<?= $this->endSection(); ?>