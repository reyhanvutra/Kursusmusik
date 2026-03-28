<?= $this->extend('admin/layout'); ?> 
<?= $this->section('content'); ?>

<h2>Data Kursus & Paket</h2>

<!-- NAV TAB -->
<a href="?tab=kursus">Kursus</a> |
<a href="?tab=paket">Paket</a>

<hr>

<?php $tab = $_GET['tab'] ?? 'kursus'; ?>

<?php if($tab == 'kursus'): ?>

<!-- ================= KURSUS ================= -->
<h3>Data Kursus</h3>
<a href="/admin/kursus/tambah">+ Tambah Kursus</a>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>Nama</th>
    <th>Harga</th>
    <th>Instruktur</th>
    <th>Hari</th>
    <th>Durasi</th>
    <th>Jadwal</th>
    <th>Slot</th>
    <th>Gambar</th>
    <th>Deskripsi</th>
    <th>Aksi</th>
</tr>

<?php foreach($kursus as $k): ?>
<tr>
    <td><?= $k['nama_kursus']; ?></td>
    <td>Rp <?= number_format($k['harga'],0,',','.'); ?></td>
    <td><?= $k['instruktur']; ?></td>
  <td>
<?php if($k['hari']): ?>
    <?php foreach(explode(',', $k['hari']) as $h): ?>
        <span style="background:#444;color:white;padding:3px 8px;border-radius:5px;margin-right:5px;">
            <?= trim($h); ?>
        </span>
    <?php endforeach; ?>
<?php else: ?>
    -
<?php endif; ?>
</td>
    <td><?= $k['durasi']; ?></td>
    <td><?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?></td>
    <td><?= $k['slot']; ?></td>
    <td>
        <?php if($k['gambar']): ?>
            <img src="/uploads/<?= $k['gambar']; ?>" width="70">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td><?= $k['deskripsi']; ?></td>
    <td>
        <a href="/admin/kursus/edit/<?= $k['id']; ?>">Edit</a> |
        <a href="/admin/kursus/hapus/<?= $k['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?php else: ?>

<!-- ================= PAKET ================= -->
<h3>Data Paket</h3>
<a href="/admin/paket/tambah">+ Tambah Paket</a>

<table border="1" cellpadding="10" width="100%">
<tr>
    <th>Nama Paket</th>
    <th>Jumlah Kursus</th>
    <th>Hari</th>
    <th>Harga</th>
    <th>Aksi</th>
</tr>

<?php foreach($paket as $p): ?>
<tr>
    <td>
        <b><?= $p['nama_paket']; ?></b><br>
        <small><?= $p['deskripsi']; ?></small>
    </td>

    <td><?= count($p['detail_kursus']); ?></td>

    <td>
        <?php foreach(explode(',', $p['hari']) as $h): ?>
            <span style="background:#444;color:white;padding:3px 8px;border-radius:5px;margin:2px;">
                <?= trim($h); ?>
            </span>
        <?php endforeach; ?>
    </td>

    <td>Rp <?= number_format($p['harga'],0,',','.'); ?></td>

    <td>
        <button onclick="toggleDetail(<?= $p['id']; ?>)">Detail</button>
        <br><br>
        <a href="/admin/paket/edit/<?= $p['id']; ?>">Edit</a> |
        <a href="/admin/paket/hapus/<?= $p['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>

<!-- 🔥 DETAIL (HIDDEN) -->
<tr id="detail<?= $p['id']; ?>" style="display:none;">
    <td colspan="5" style="background:#f9f9f9;">

        <b>Isi Kursus:</b><br><br>

        <?php foreach($p['detail_kursus'] as $k): ?>
            <div style="margin-bottom:10px; padding:10px; background:white; border-radius:8px;">
                <b><?= $k['nama_kursus']; ?></b><br>
                👨‍🏫 <?= $k['instruktur']; ?><br>
                ⏱️ <?= $k['durasi']; ?><br>
                📅 <?= $k['hari']; ?>
            </div>
        <?php endforeach; ?>

    </td>
</tr>

<?php endforeach; ?>
</table>

<script>
function toggleDetail(id){
    let el = document.getElementById('detail'+id);
    el.style.display = el.style.display === 'none' ? 'table-row' : 'none';
}
</script>

<?php endif; ?>

<?= $this->endSection(); ?>