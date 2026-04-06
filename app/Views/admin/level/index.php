<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <a href="/admin/kursus">← Kembali</a>
        <h2>Level: <?= $kursus['nama_kursus']; ?></h2>
    </div>
    <a href="/admin/level/tambah/<?= $kursus['id']; ?>">+ Tambah Level</a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div style="color:lightgreen"><?= session()->getFlashdata('success'); ?></div>
<?php endif; ?>

<?php if(empty($level)): ?>
    <div>Belum ada level</div>
<?php endif; ?>

<?php foreach($level as $l): ?>
<div style="border:1px solid #444;padding:15px;margin-bottom:15px;border-radius:10px;">

    <b><?= $l['nama_level']; ?></b> (Urutan <?= $l['urutan']; ?>)
    <br>
    Harga: Rp <?= number_format($l['harga'],0,',','.'); ?>

    <hr>

    Hari: <?= $l['hari']; ?> <br>
    Jam: <?= $l['jam_mulai']; ?> - <?= $l['jam_selesai']; ?> <br>
    Durasi: <?= $l['durasi']; ?> <br>
    Mentor: <?= $l['nama_mentor'] ?? '-' ?> <br>
   Slot: 
<span style="color:<?= $l['slot_sisa'] > 0 ? 'lightgreen' : 'red'; ?>">
    <?= $l['slot_sisa']; ?> / <?= $l['slot']; ?>
</span>
<br>
<small style="color:#aaa;">
    Terpakai: <?= $l['slot_terpakai']; ?>
</small>
    Pertemuan: <?= $l['pertemuan']; ?>x

    <br><br>

    <a href="/admin/level/edit/<?= $l['id']; ?>">Edit</a> |
    <a href="/admin/level/hapus/<?= $l['id']; ?>">Hapus</a>

</div>
<?php endforeach; ?>

<?= $this->endSection(); ?>