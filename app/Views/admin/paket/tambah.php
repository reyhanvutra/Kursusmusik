<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Tambah Paket Kursus</h2>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<form action="/admin/paket/simpan" method="post">

Nama Paket:<br>
<input type="text" name="nama_paket" required><br><br>

Harga Paket (Final):<br>
<input type="number" name="harga" required><br>
<small style="color:gray;">* Harga sudah termasuk semua kursus</small><br><br>

<b>Pilih Kursus:</b><br>
<small style="color:red;">* pilih minimal 1</small><br><br>

<div id="listKursus">
<?php foreach($kursus as $k): ?>
<div style="margin-bottom:10px;">
    
    <input type="checkbox" 
        name="kursus[]" 
        value="<?= $k['id']; ?>"
        data-harga="<?= $k['harga']; ?>"
        id="kursus<?= $k['id']; ?>"
        onclick="toggleDetail(<?= $k['id']; ?>); hitungTotal();"
        <?= $k['slot'] <= 0 ? 'disabled' : '' ?>
    >

    <?= $k['nama_kursus']; ?> 
    (Rp <?= number_format($k['harga'],0,',','.'); ?>)

    <?php if($k['slot'] <= 0): ?>
        <span style="color:red;">[Penuh]</span>
    <?php endif; ?>

    <div id="detail<?= $k['id']; ?>" style="display:none; margin-left:20px;">
        Instruktur: <?= $k['instruktur']; ?><br>
        Jadwal: <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?><br>
        Durasi: <?= $k['durasi']; ?><br>
    </div>
</div>
<?php endforeach; ?>
</div>

<br>

<!-- PREVIEW -->
<div style="background:#f5f5f5;padding:10px;border-radius:8px;">
    Harga Normal: <b id="hargaNormal">0</b><br>
    <small style="color:gray;">* Perbandingan total kursus tanpa paket</small>
</div>

<br>

Deskripsi:<br>
<textarea name="deskripsi"></textarea><br><br>

<button type="submit">Simpan</button>
<a href="/admin/kursus?tab=paket">Kembali</a>

</form>

<script>
function toggleDetail(id){
    let check = document.getElementById('kursus'+id);
    let detail = document.getElementById('detail'+id);
    detail.style.display = check.checked ? 'block' : 'none';
}

function hitungTotal(){
    let total = 0;

    document.querySelectorAll('#listKursus input[type=checkbox]:checked').forEach(cb=>{
        total += parseInt(cb.dataset.harga);
    });

    document.getElementById('hargaNormal').innerText =
        new Intl.NumberFormat('id-ID').format(total);
}
</script>

<?= $this->endSection(); ?>