<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit User</h2>

<form action="/admin/user/update/<?= $user['id']; ?>" method="post">
    Nama: <input type="text" name="nama" value="<?= $user['nama']; ?>"><br><br>
    Email: <input type="email" name="email" value="<?= $user['email']; ?>"><br><br>

    Role:
    <select name="role">
        <option value="admin" <?= $user['role']=='admin'?'selected':''; ?>>Admin</option>
        <option value="kasir" <?= $user['role']=='kasir'?'selected':''; ?>>Kasir</option>
        <option value="owner" <?= $user['role']=='owner'?'selected':''; ?>>Owner</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>

<?= $this->endSection(); ?>