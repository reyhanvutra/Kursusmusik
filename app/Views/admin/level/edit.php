<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">
<h2>Edit Level</h2>

<a href="/admin/level/index/<?= $level['id_kursus']; ?>">← Kembali</a>

<form method="post" action="/admin/level/update/<?= $level['id']; ?>">
<?= csrf_field(); ?>

Nama Level <br>
<input type="text" name="nama_level" value="<?= $level['nama_level']; ?>"><br><br>

Urutan <br>
<input type="number" name="urutan" value="<?= $level['urutan']; ?>"><br><br>

Pertemuan <br>
<input type="number" name="pertemuan" value="<?= $level['pertemuan']; ?>"><br><br>

Harga <br>
<input type="number" name="harga" value="<?= $level['harga']; ?>"><br><br>

Mentor <br>
<select name="id_mentor" required>
    <?php foreach($mentor as $m): ?>
        <option value="<?= $m['id']; ?>"
            <?= $m['id'] == $level['id_mentor'] ? 'selected' : '' ?>>
            <?= $m['nama']; ?> (<?= $m['keahlian']; ?>)
        </option>
    <?php endforeach; ?>
</select>
<br><br>

Hari <br>
<?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
<label>
<input type="checkbox" name="hari[]" value="<?= $h ?>"
<?= in_array($h,$level['hari_array'])?'checked':'' ?>>
<?= $h ?>
</label>
<?php endforeach; ?>
<br><br>

Jam Mulai <br>
<input type="time" name="jam_mulai" value="<?= $level['jam_mulai']; ?>"><br>

Jam Selesai <br>
<input type="time" name="jam_selesai" value="<?= $level['jam_selesai']; ?>"><br><br>

Slot <br>
<input type="number" name="slot" value="<?= $level['slot']; ?>"><br><br>

<button type="submit">Update</button>
</form>

<?= $this->endSection(); ?>