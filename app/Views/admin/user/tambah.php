<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah User</h2>

<form action="/admin/user/simpan" method="post">
    Nama: <input type="text" name="nama"><br><br>
    Email: <input type="email" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>

    Role:
    <select name="role">
        <option value="admin">Admin</option>
        <option value="kasir">Kasir</option>
        <option value="owner">Owner</option>
    </select><br><br>

    <button type="submit">Simpan</button>
</form>

<?= $this->endSection(); ?>