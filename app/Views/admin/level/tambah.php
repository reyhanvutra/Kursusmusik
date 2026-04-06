<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">
<h2>Tambah Level</h2>

<a href="/admin/level/index/<?= $id_kursus; ?>">← Kembali</a>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red"><?= session()->getFlashdata('error'); ?></div>
<?php endif; ?>

<form method="post" action="/admin/level/simpan">
<?= csrf_field(); ?>

<input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">

Nama Level <br>
<input type="text" name="nama_level" required><br><br>

Urutan <br>
<input type="number" name="urutan" required><br><br>

Pertemuan <br>
<input type="number" name="pertemuan" required><br><br>

Harga <br>
<input type="number" name="harga" required><br><br>

Mentor <br>
<select name="id_mentor" required>
    <option value="">-- Pilih Mentor --</option>
    <?php foreach($mentor as $m): ?>
        <option value="<?= $m['id']; ?>">
            <?= $m['nama']; ?> (<?= $m['keahlian']; ?>)
        </option>
    <?php endforeach; ?>
</select>
<br><br>

Hari <br>
<?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
<label>
<input type="checkbox" name="hari[]" value="<?= $h ?>"> <?= $h ?>
</label>
<?php endforeach; ?>
<br><br>

Jam Mulai <br>
<input type="time" name="jam_mulai" id="mulai" required><br>

Jam Selesai <br>
<input type="time" name="jam_selesai" id="selesai" required><br>

Durasi <br>
<input type="text" id="durasi" readonly><br><br>

Slot <br>
<input type="number" name="slot" required><br><br>

Deskripsi <br>
<textarea name="deskripsi"></textarea><br><br>

<button type="submit">Simpan</button>
</form>

<script>
function hitung(){
    let m = document.getElementById('mulai').value;
    let s = document.getElementById('selesai').value;

    if(m && s){
        let start = new Date('1970-01-01T'+m);
        let end = new Date('1970-01-01T'+s);
        let diff = (end-start)/3600000;

        document.getElementById('durasi').value =
            diff > 0 ? diff + ' jam' : 'Tidak valid';
    }
}

mulai.onchange = hitung;
selesai.onchange = hitung;
</script>

<?= $this->endSection(); ?>