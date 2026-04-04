<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="user-header-flex">
    <h2 class="page-header" style="margin: 0; color: white;">Kategori Kursus</h2>
    <a href="/admin/kategori/create" class="btn-tambah">
        <i class="fa-solid fa-plus"></i> Tambah Kategori
    </a>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th width="120" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($kategori as $k): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <strong style="color: #fff; font-size: 15px;">
                        <?= $k['nama_kategori'] ?>
                    </strong>
                </td>
                <td style="color: #aaa; font-size: 13px; max-width: 300px;">
                    <?= $k['deskripsi'] ?: '<em style="color: #555;">Tidak ada deskripsi</em>' ?>
                </td>
                <td>
                    <div class="action-group" style="justify-content: center;">
                        <a href="/admin/kategori/edit/<?= $k['id'] ?>" class="action-link edit" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="/admin/kategori/delete/<?= $k['id'] ?>" class="action-link hapus" onclick="return confirm('Hapus kategori ini?')" title="Hapus">
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