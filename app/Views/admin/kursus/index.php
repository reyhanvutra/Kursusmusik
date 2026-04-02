<?= $this->extend('admin/layout'); ?> 
<?= $this->section('content'); ?>

<h2>Data Kursus</h2>

<a href="/admin/kursus/tambah" 
   style="background:green;color:white;padding:6px 10px;border-radius:5px;text-decoration:none;">
   + Tambah Kursus
</a>

<br><br>

<table border="1" cellpadding="10" width="100%" style="border-collapse:collapse;">
<tr style="background:#f5f5f5;">
    <th>No</th>
    <th>Kategori</th>
    <th>Nama</th>
    <th>Instruktur</th>
    <th>Hari</th>
    <th>Durasi</th>
    <th>Jadwal</th>
    <th>Slot</th>
    <th>Gambar</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php if(empty($kursus)): ?>
<tr>
    <td colspan="11" align="center">Belum ada data kursus</td>
</tr>
<?php endif; ?>

<?php $no=1; foreach($kursus as $k): ?>
<tr>
    <td><?= $no++; ?></td>

    <!-- KATEGORI -->
    <td>
        <span style="background:#007bff;color:white;padding:4px 8px;border-radius:6px;">
            <?= $k['nama_kategori']; ?>
        </span>
    </td>

    <!-- NAMA -->
    <td><b><?= $k['nama_kursus']; ?></b></td>

    <td><?= $k['instruktur']; ?></td>

    <!-- HARI -->
    <td>
    <?php if($k['hari']): ?>
        <?php foreach(explode(',', $k['hari']) as $h): ?>
            <span style="background:#444;color:white;padding:3px 8px;border-radius:5px;margin:2px;">
                <?= trim($h); ?>
            </span>
        <?php endforeach; ?>
    <?php else: ?>
        -
    <?php endif; ?>
    </td>

    <!-- DURASI -->
    <td><?= $k['durasi']; ?></td>

    <!-- JAM -->
    <td>
        <span style="color:green;">
            <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?>
        </span>
    </td>

    <!-- SLOT -->
    <td>
        <span style="background:#eee;padding:3px 6px;border-radius:5px;">
            <?= $k['slot']; ?>
        </span>
    </td>

    <!-- GAMBAR -->
    <td>
        <?php if($k['gambar']): ?>
            <img src="/uploads/<?= $k['gambar']; ?>" width="70" style="border-radius:8px;">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>

    <!-- DESKRIPSI -->
    <td style="max-width:200px;">
        <?= $k['deskripsi']; ?>
    </td>

    <!-- AKSI -->
    <td>

        <!-- 🔥 BUTTON LEVEL -->
        <a href="/admin/level/<?= $k['id']; ?>" 
           style="background:orange;color:white;padding:4px 8px;border-radius:5px;text-decoration:none;">
           Level
        </a>

        <br><br>

        <a href="/admin/kursus/edit/<?= $k['id']; ?>">Edit</a> |
        <a href="/admin/kursus/hapus/<?= $k['id']; ?>" 
           onclick="return confirm('Hapus kursus ini?')">Hapus</a>

    </td>
</tr>
<?php endforeach; ?>

</table>

<?= $this->endSection(); ?>