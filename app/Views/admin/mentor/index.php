<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h2>Data Mentor</h2>
    <a href="/admin/mentor/tambah" style="background:#28a745;color:white;padding:8px 12px;border-radius:6px;">
        + Tambah Mentor
    </a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="color:lightgreen;margin-bottom:10px;">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;margin-bottom:10px;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<table border="1" cellpadding="10" width="100%" style="border-collapse:collapse;">
    <tr style="background:#222;color:white;">
        <th>Nama</th>
        <th>Keahlian</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php if(empty($mentor)): ?>
        <tr>
            <td colspan="4" style="text-align:center;">Belum ada data mentor</td>
        </tr>
    <?php endif; ?>

    <?php foreach($mentor as $m): ?>
    <tr>
        <td><?= $m['nama']; ?></td>
        <td><?= $m['keahlian']; ?></td>
        <td>
            <?= $m['aktif'] ? '<span style="color:lightgreen">Aktif</span>' : '<span style="color:red">Nonaktif</span>'; ?>
        </td>
        <td>
            <a href="/admin/mentor/edit/<?= $m['id']; ?>">Edit</a> |
            <a href="/admin/mentor/hapus/<?= $m['id']; ?>"
               onclick="return confirm('Yakin hapus mentor ini?')">
               Hapus
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection(); ?>