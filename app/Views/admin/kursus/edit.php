<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit Kursus</h2>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<form action="/admin/kursus/update/<?= $kursus['id']; ?>" method="post" enctype="multipart/form-data">

Kategori:
<select name="id_kategori" required>
    <?php foreach($kategori as $k): ?>
        <option value="<?= $k['id']; ?>"
            <?= $k['id'] == $kursus['id_kategori'] ? 'selected' : '' ?>>
            <?= $k['nama_kategori']; ?>
        </option>
    <?php endforeach; ?>
</select>
<br><br>

Nama:
<input type="text" name="nama_kursus" value="<?= $kursus['nama_kursus']; ?>" required><br><br>


Instruktur:
<input type="text" name="instruktur" value="<?= $kursus['instruktur']; ?>" required><br><br>

Hari:<br>

<?php 
$hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
$hariDipilih = $kursus['hari'] ? explode(',', $kursus['hari']) : [];
?>

<?php foreach($hariList as $h): ?>
<label>
    <input type="checkbox" name="hari[]" value="<?= $h; ?>"
        <?= in_array($h, $hariDipilih) ? 'checked' : '' ?>>
    <?= $h; ?>
</label><br>
<?php endforeach; ?>

<br>

Jam Mulai:
<input type="time" name="jam_mulai" value="<?= $kursus['jam_mulai']; ?>" required><br><br>

Jam Selesai:
<input type="time" name="jam_selesai" value="<?= $kursus['jam_selesai']; ?>" required><br><br>

Durasi:
<input type="text" value="<?= $kursus['durasi']; ?>" readonly><br><br>

Slot:
<input type="number" name="slot" value="<?= $kursus['slot']; ?>" required><br><br>

Deskripsi:
<textarea name="deskripsi"><?= $kursus['deskripsi']; ?></textarea><br><br>

<?php if($kursus['gambar']): ?>
    <img src="/uploads/<?= $kursus['gambar']; ?>" width="100"><br><br>
<?php endif; ?>

Ganti Gambar:
<input type="file" name="gambar" accept="image/*"><br><br>

<button type="submit">Update</button>
<a href="/admin/kursus">← Kembali</a>

</form>

<?= $this->endSection(); ?>