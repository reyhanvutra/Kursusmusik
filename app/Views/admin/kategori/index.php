<?= $this->extend('admin/layout'); ?>
<?php if(session()->getFlashdata('success')): ?>
    <div class="alert success">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert error">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>
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
                <th>Gambar</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th width="120" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($kategori as $k): ?>
            <tr>
                <td><?= $no++ ?></td>

                <!-- 🔥 GAMBAR -->
                <td>
                    <?php if($k['gambar']): ?>
                        <img src="<?= base_url('uploads/kategori/'.$k['gambar']) ?>" 
                             style="width:80px;height:50px;object-fit:cover;border-radius:6px;">
                    <?php else: ?>
                        <span style="color:#555;">Tidak ada</span>
                    <?php endif; ?>
                </td>

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
                        <a href="/admin/kategori/edit/<?= $k['id'] ?>" class="action-link edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="/admin/kategori/delete/<?= $k['id'] ?>" 
                        >
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