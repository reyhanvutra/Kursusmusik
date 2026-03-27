<h2><?= $p['nama_paket']; ?></h2>

<div style="background:#2b2b2b; padding:20px; border-radius:10px; color:white;">

    💰 Harga Paket: Rp <?= number_format($p['harga'],0,',','.'); ?><br><br>

    <b>Deskripsi:</b>
    <p><?= $p['deskripsi']; ?></p>

</div>

<br>

<h3>📚 Isi Paket:</h3>

<div style="display:flex; flex-wrap:wrap; gap:15px;">

<?php foreach($kursus as $k): ?>
    <div style="width:220px; background:#f5f5f5; padding:10px; border-radius:10px;">

        <?php if($k['gambar']): ?>
            <img src="/uploads/<?= $k['gambar']; ?>" width="100%" style="border-radius:8px;"><br><br>
        <?php endif; ?>

        <b><?= $k['nama_kursus']; ?></b><br>

        👨‍🏫 <?= $k['instruktur']; ?><br>
        ⏱️ <?= $k['durasi']; ?><br>
        💰 Rp <?= number_format($k['harga'],0,',','.'); ?>

    </div>
<?php endforeach; ?>

</div>

<br>

<a href="/kasir/pilih?tab=paket"
   style="background:#444;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">
   ← Kembali
</a>