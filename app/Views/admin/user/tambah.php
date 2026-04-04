<?= $this->extend('admin/layout'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    
    <div class="form-header-area">
        <a href="/admin/user" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h2 class="page-header">Tambah User</h2>
    </div>

    <div class="form-container">
        <form action="/admin/user/simpan" method="post">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="nama" class="form-control">
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label>Role:</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                    <option value="owner">Owner</option>
                </select>
            </div>

            <button type="submit" class="btn-save">Simpan</button>
        </form>
    </div>

</div>
<?= $this->endSection(); ?>