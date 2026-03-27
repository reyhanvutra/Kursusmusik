<h2><?= $k['nama_kursus']; ?></h2>

<?php if($k['gambar']): ?>
    <img src="/uploads/<?= $k['gambar']; ?>" width="250" style="border-radius:10px;"><br><br>
<?php endif; ?>

<div style="background:#2b2b2b; padding:20px; border-radius:10px; color:white;">

    💰 Harga: Rp <?= number_format($k['harga'],0,',','.'); ?><br><br>

    👨‍🏫 Instruktur: <?= $k['instruktur']; ?><br>
    ⏰ Jadwal: <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?><br>
    ⏱️ Durasi: <?= $k['durasi']; ?><br>
    📦 Slot: <?= $k['slot']; ?><br><br>

    <b>Deskripsi:</b><br>
    <p><?= $k['deskripsi']; ?></p>

</div>

<br>

<a href="/kasir/pilih?tab=kursus"
   style="background:#444;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">
   ← Kembali
</a>