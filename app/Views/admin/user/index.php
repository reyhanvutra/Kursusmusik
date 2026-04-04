<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="user-header-flex">
    <h2 class="page-header" style="margin-bottom: 0;">User Dashboard</h2>
    <a href="/admin/user/tambah" class="btn-tambah">
        <i class="fa-solid fa-plus"></i> Tambah User
    </a>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($users as $u): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $u['nama']; ?></td>
                <td><?= $u['username'] ?? $u['email']; ?></td> <td><?= ucfirst($u['role']); ?></td>
                <td>
                    <div class="action-group">
                        <a href="/admin/user/edit/<?= $u['id']; ?>" class="btn-edit">Edit</a>
                        <a href="/admin/user/hapus/<?= $u['id']; ?>" class="btn-delete" onclick="return confirm('Hapus user ini?')">
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