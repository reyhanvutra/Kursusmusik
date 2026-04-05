<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="user-header-flex">
    <div class="header-titles">
        <a href="/admin/kursus" class="action-link" style="font-size: 14px;">← Kembali ke Kursus</a>
        <h2 class="page-header" style="margin-top: 5px; color: white;">Level: <?= $kursus['nama_kursus']; ?></h2>
    </div>
    <a href="/admin/level/tambah/<?= $kursus['id']; ?>" class="btn-tambah">
        <i class="fa-solid fa-plus"></i> Tambah Level
    </a>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th width="80">Urutan</th>
                <th>Nama Level</th>
                <th>Pertemuan</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th width="120" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($level as $l): ?>
            <tr>
                <td style="text-align: center;"><span class="badge-hari"><?= $l['urutan']; ?></span></td>
                <td><strong style="color: #fff;"><?= $l['nama_level']; ?></strong></td>
                <td><?= $l['pertemuan']; ?>x Pertemuan</td>
                <td style="color: #00ff00; font-weight: 700;">Rp <?= number_format($l['harga'], 0, ',', '.'); ?></td>
                <td style="color: #888; font-size: 13px;"><?= $l['deskripsi']; ?></td>
                <td>
                    <div class="action-group" style="justify-content: center;">
                        <a href="/admin/level/edit/<?= $l['id']; ?>" class="action-link edit" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="/admin/level/hapus/<?= $l['id']; ?>" >
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>