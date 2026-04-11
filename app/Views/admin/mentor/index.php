<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="table-container">
    <div class="user-header-flex">
        <h2 style="color: white; margin: 0;">Data Mentor</h2>
        <a href="/admin/mentor/tambah" class="btn-tambah">
            <span>+</span> Tambah Mentor
        </a>
    </div>


    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert error">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <table class="custom-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Keahlian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($mentor)): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada data mentor</td>
                </tr>
            <?php else: ?>
                <?php foreach($mentor as $m): ?>
                <tr>
                    <td><?= $m['nama']; ?></td>
                    <td><?= $m['keahlian']; ?></td>
                    <td>
                        <span class="badge-role" style="<?= $m['aktif'] ? '' : 'background:rgba(255,0,0,0.15); color:#ff4d4d;' ?>">
                            <?= $m['aktif'] ? 'Aktif' : 'Nonaktif'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="/admin/mentor/edit/<?= $m['id']; ?>" class="action-link edit" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                 </a>
                            <a href="/admin/mentor/hapus/<?= $m['id']; ?>">
                                 <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>