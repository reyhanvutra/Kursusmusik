<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/owner/datasiswa.css'); ?>">

<div class="report-container">
    <div class="header-action">
        <div class="header-title-group">
            <h2 class="page-title">Data Siswa</h2>
            <p class="page-subtitle">Daftar seluruh siswa yang terdaftar dalam sistem kursus musik.</p>
        </div>
        <a href="/owner/datasiswa/pdf?nama=<?= $nama ?>&status=<?= $status ?>&aktif=<?= $aktif ?>" 
           class="btn btn-pdf">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <div class="card card-filter">
        <form method="get" class="filter-grid">
            <div class="input-group">
                <label>Nama Siswa</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="nama" placeholder="Cari nama siswa..." value="<?= $nama ?? ''; ?>">
                </div>
            </div>

            <div class="input-group">
                <label>Status Aktivitas</label>
                <select name="aktif">
                    <option value="">Semua Status</option>
                    <option value="1" <?= ($aktif ?? '') === '1' ? 'selected' : '' ?>>🟢 Aktif</option>
                    <option value="0" <?= ($aktif ?? '') === '0' ? 'selected' : '' ?>>🔴 Nonaktif</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-filter">Filter</button>
                <a href="/owner/datasiswa" class="btn btn-clear">Reset</a>
            </div>
        </form>
    </div>

    <div class="card card-table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Informasi Siswa</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($siswa)): ?>
                    <tr><td colspan="6" class="text-center py-5">Tidak ada data siswa ditemukan</td></tr>
                <?php endif; ?>
                <?php $no = 1; foreach($siswa as $s): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td>
                            <div class="student-info">
                                <span class="student-name"><?= $s['nama']; ?></span>
                                <small class="student-id">ID-<?= str_pad($s['id'] ?? $no, 4, '0', STR_PAD_LEFT); ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="contact-box">
                                <i class="fa-brands fa-whatsapp text-success"></i> <?= $s['no_hp']; ?>
                            </div>
                        </td>
                        <td><span class="address-text"><?= $s['alamat']; ?></span></td>
                        <td class="text-center">
                            <?php if($s['aktif_count'] > 0): ?>
                                <span class="badge-status aktif">Aktif</span>
                            <?php else: ?>
                                <span class="badge-status nonaktif">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <span class="transaction-count"><?= $s['total_transaksi'] ?? 0; ?></span>
                            <small class="text-muted">Kali</small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<div class="pagination-wrapper">
    <?= $pager; ?> 
</div>
</div>

<?= $this->endSection(); ?>