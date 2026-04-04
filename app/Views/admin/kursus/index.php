<?= $this->extend('admin/layout'); ?> 
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_kursus.css'); ?>">

<div class="header-flex">
    <h2 class="page-header">Data Kursus</h2>
    <a href="/admin/kursus/tambah" class="btn-tambah">
        <i class="fa-solid fa-plus"></i> Tambah Kursus
    </a>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Kursus</th>
                <th>Instruktur</th>
                <th>Hari</th>
                <th>Jadwal</th>
                <th>Slot</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($kursus)): ?>
            <tr>
                <td colspan="9" align="center">Belum ada data kursus</td>
            </tr>
            <?php endif; ?>

            <?php $no=1; foreach($kursus as $k): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <span class="badge-kategori"><?= $k['nama_kategori']; ?></span>
                </td>
                <td><strong><?= $k['nama_kursus']; ?></strong></td>
                <td><?= $k['instruktur']; ?></td>
                <td>
                    <?php if($k['hari']): ?>
                        <?php foreach(explode(',', $k['hari']) as $h): ?>
                            <span class="badge-hari"><?= trim($h); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?> - <?php endif; ?>
                </td>
                <td>
                    <span style="color:#00ff88; font-size: 13px;">
                        <i class="fa-regular fa-clock"></i> <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?>
                    </span>
                </td>
                <td>
                    <span style="background:rgba(255,255,255,0.1); padding:5px 10px; border-radius:8px;">
                        <?= $k['slot']; ?>
                    </span>
                </td>
                <td>
                    <?php if($k['gambar']): ?>
                        <img src="/uploads/<?= $k['gambar']; ?>" width="60" style="border-radius:10px; border: 1px solid rgba(255,255,255,0.1);">
                    <?php else: ?> - <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/level/<?= $k['id']; ?>" class="btn-level">Level</a>
                    <div style="margin-top: 5px;">
                        <a href="/admin/kursus/edit/<?= $k['id']; ?>" class="action-link edit">Edit</a>
                        <span style="color:#444;">|</span>
                        <a href="/admin/kursus/hapus/<?= $k['id']; ?>" class="action-link hapus" onclick="return confirm('Hapus kursus ini?')">Hapus</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>