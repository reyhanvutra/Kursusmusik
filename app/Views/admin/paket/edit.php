<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<h2>Edit Paket Kursus</h2>
<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<form action="/admin/paket/update/<?= $paket['id']; ?>" method="post">

    Nama Paket:<br>
    <input type="text" name="nama_paket" value="<?= $paket['nama_paket']; ?>"><br><br>

    Harga:<br>
    <input type="number" name="harga" value="<?= $paket['harga']; ?>"><br><br>

    <b>Pilih Kursus:</b><br><br>

    <?php 
    $selected = array_column($detail, 'id_kursus');
    ?>

    <?php foreach($kursus as $k): ?>

        <?php $isChecked = in_array($k['id'], $selected); ?>

        <div style="margin-bottom:10px;">

            <input type="checkbox" 
                name="kursus[]" 
                value="<?= $k['id']; ?>"
                id="kursus<?= $k['id']; ?>"
                onclick="toggleDetail(<?= $k['id']; ?>)"
                <?= $isChecked ? 'checked' : '' ?>
                <?= $k['slot'] <= 0 ? 'disabled' : '' ?>
            >

            <label for="kursus<?= $k['id']; ?>">
                <?= $k['nama_kursus']; ?> 
                (Slot: <?= $k['slot']; ?>)
            </label>

            <?php if($k['slot'] <= 0): ?>
                <span style="color:red;">[Penuh]</span>
            <?php endif; ?>

            <!-- DETAIL -->
            <div id="detail<?= $k['id']; ?>" 
                style="display:<?= $isChecked ? 'block' : 'none'; ?>; 
                       margin-left:20px; 
                       background:#f5f5f5; 
                       padding:10px; 
                       border-radius:8px;">

                Instruktur: <?= $k['instruktur']; ?><br>
                Jadwal: <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?><br>
                Durasi: <?= $k['durasi']; ?><br>
                Harga: Rp <?= number_format($k['harga'],0,',','.'); ?>
            </div>

        </div>

    <?php endforeach; ?>

    <br>

    Deskripsi:<br>
    <textarea name="deskripsi"><?= $paket['deskripsi']; ?></textarea><br><br>

    <button type="submit">Update</button>
    <a href="/admin/kursus?tab=paket">Kembali</a>

</form>

<script>
function toggleDetail(id){
    let check = document.getElementById('kursus'+id);
    let detail = document.getElementById('detail'+id);

    detail.style.display = check.checked ? 'block' : 'none';
}
</script>

<?= $this->endSection(); ?>