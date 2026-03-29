<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<h1>Log Activity</h1>

<form method="get" action="/owner/log-activity">

    <label>Nama:</label>
    <input type="text" name="nama" value="<?= $_GET['nama'] ?? '' ?>">

    <label>Role:</label>
    <select name="role">
        <option value="">--Semua--</option>
        <option value="admin" <?= (($_GET['role'] ?? '')=='admin')?'selected':'' ?>>Admin</option>
        <option value="kasir" <?= (($_GET['role'] ?? '')=='kasir')?'selected':'' ?>>Kasir</option>
    </select>

    <button type="submit">Filter</button>

    <a href="/owner/log-activity">
        <button type="button">Clear</button>
    </a>

</form>

<br>

<?php 
$page = $_GET['page'] ?? 1;
$perPage = 10;
$no = 1 + ($perPage * ($page - 1));
?>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>No</th>
    <th>Nama User</th>
    <th>Role</th>
    <th>Aktivitas</th>
    <th>Waktu</th>
</tr>

<?php if(!empty($data)): ?>
    <?php foreach($data as $d): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $d['nama_user']; ?></td>
        <td><?= ucfirst($d['role']); ?></td>
        <td><?= $d['aktivitas']; ?></td>
        <td><?= date('d-m-Y H:i', strtotime($d['created_at'])); ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="5">Tidak ada data</td>
</tr>
<?php endif; ?>

</table>

<br>

<?= $pager->links(); ?>
<?= $this->endSection(); ?>