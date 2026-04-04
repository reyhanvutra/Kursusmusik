<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="user-header-flex">
    <h2 class="page-header" style="margin: 0; color: white;">User Dashboard</h2>
    <a href="/admin/user/tambah" class="btn-tambah">
        <i class="fa-solid fa-plus"></i> Tambah User
    </a>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Nama Lengkap</th>
                <th>Username / Email</th>
                <th>Role</th>
                <th width="100" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($users as $u): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><strong><?= $u['nama']; ?></strong></td>
                <td style="color: #888;"><?= $u['username'] ?? $u['email']; ?></td>
                <td>
                    <span class="badge-role"><?= ucfirst($u['role']); ?></span>
                </td>
                <td>
                    <div class="action-group" style="justify-content: center;">
                        <a href="/admin/user/edit/<?= $u['id']; ?>" class="action-link edit" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="/admin/user/hapus/<?= $u['id']; ?>" class="action-link hapus" onclick="return confirm('Hapus user ini?')" title="Hapus">
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