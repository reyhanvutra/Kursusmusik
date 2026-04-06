<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/owner/datasiswa.css'); ?>">

<div class="container">
    <h2 class="judul">👨‍🎓 Data Siswa</h2>

    <!-- FILTER -->
    <form method="get" class="filter-box">
        <input type="text" name="nama" placeholder="Cari nama siswa..." value="<?= $nama ?? ''; ?>">

        <select name="aktif">
            <option value="">Semua Aktif</option>
            <option value="1" <?= ($aktif ?? '') === '1' ? 'selected' : '' ?>>🟢 Aktif</option>
            <option value="0" <?= ($aktif ?? '') === '0' ? 'selected' : '' ?>>🔴 Nonaktif</option>
        </select>
        <button type="submit">🔍 Filter</button>
        <a href="/owner/datasiswa" class="btn-reset">Reset</a>
        <a href="/owner/datasiswa/pdf?nama=<?= $nama ?>&status=<?= $status ?>&aktif=<?= $aktif ?>" class="btn-download">📄 Download PDF</a>
    </form>

    <!-- TABLE -->
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($siswa)): ?>
                    <tr><td colspan="6" class="kosong">Tidak ada data</td></tr>
                <?php endif; ?>
                <?php $no = 1; foreach($siswa as $s): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $s['nama']; ?></td>
                        <td><?= $s['no_hp']; ?></td>
                        <td><?= $s['alamat']; ?></td>
                        <td>
                           
            
                            <?php if($s['aktif_count'] > 0): ?>
                                <span class="badge aktif">Aktif</span>
                            <?php else: ?>
                                <span class="badge nonaktif">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $s['total_transaksi'] ?? 0; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination"><?= $pager ?></div>
</div>

<?= $this->endSection(); ?>