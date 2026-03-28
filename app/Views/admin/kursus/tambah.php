<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah Kursus</h2>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<form action="/admin/kursus/simpan" method="post" enctype="multipart/form-data">

Nama:
<input type="text" name="nama_kursus" required><br><br>

Harga:
<input type="number" name="harga" required><br><br>

Instruktur:
<input type="text" name="instruktur" required><br><br>

Hari:<br>

<?php 
$hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
foreach($hariList as $h): 
?>

<label>
    <input type="checkbox" name="hari[]" value="<?= $h; ?>">
    <?= $h; ?>
</label><br>

<?php endforeach; ?>

<br>

Jam Mulai:
<input type="time" name="jam_mulai" required><br><br>

Jam Selesai:
<input type="time" name="jam_selesai" required><br><br>

Durasi:
<input type="text" id="durasi" readonly><br><br>

Slot:
<input type="number" name="slot" required><br><br>

Deskripsi:
<textarea name="deskripsi"></textarea><br><br>

Gambar:
<input type="file" name="gambar" accept="image/*"><br><br>

<button type="submit">Simpan</button>
<a href="/admin/kursus" style="display:inline-block;margin-bottom:15px;">← Kembali</a>

</form>

<script>
let mulai = document.querySelector('[name=jam_mulai]');
let selesai = document.querySelector('[name=jam_selesai]');
let durasi = document.getElementById('durasi');

function hitungDurasi(){
    if(mulai.value && selesai.value){
        let start = new Date('1970-01-01T' + mulai.value);
        let end = new Date('1970-01-01T' + selesai.value);

        let selisih = (end - start) / 3600000;

        if(selisih <= 0){
            durasi.value = 'Jam tidak valid';
        }else{
            durasi.value = selisih + ' jam';
        }
    }
}

mulai.addEventListener('change', hitungDurasi);
selesai.addEventListener('change', hitungDurasi);
</script>

<?= $this->endSection(); ?>