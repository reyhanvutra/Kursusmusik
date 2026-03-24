<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Data User</h2>

<a href="/admin/user/tambah">+ Tambah User</a>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>

    <?php $no=1; foreach($users as $u): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $u['nama']; ?></td>
        <td><?= $u['email']; ?></td>
        <td><?= $u['role']; ?></td>
        <td>
            <a href="/admin/user/edit/<?= $u['id']; ?>">Edit</a>
            <a href="/admin/user/hapus/<?= $u['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<?= $this->endSection(); ?>