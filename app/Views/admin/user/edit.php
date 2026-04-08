<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    
    <div class="form-header-area">
        <a href="/admin/user" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h2 class="page-header">Edit User</h2>
    </div>

    <div class="form-container">
        <form action="/admin/user/update/<?= $user['id']; ?>" method="post">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" class="form-control" value="<?= $user['nama']; ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
            </div>

            <button type="submit" class="btn-save">Update Data</button>
        </form>
    </div>

</div>
<?= $this->endSection(); ?>