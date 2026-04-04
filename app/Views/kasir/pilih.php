<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<h2>Pilih Kategori Kursus</h2>

<div style="display:flex; gap:20px; flex-wrap:wrap;">

<?php foreach($kategori as $k): ?>
    <div style="border:1px solid #ccc; padding:20px; width:300px; border-radius:10px;">
        
        <h3><?= $k['nama_kategori']; ?></h3>
        <p><?= $k['deskripsi']; ?></p>

        <hr>

        <?php foreach($k['kursus'] as $c): ?>
            <div style="margin-bottom:10px;">
               <a href="/kasir/detail/kategori/<?= $k['id']; ?>" 
   style="display:inline-block;margin-top:10px;background:#2196f3;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;">
   🔍 Lihat Kategori
</a>
                </a>
            </div>
        <?php endforeach; ?>

    </div>
<?php endforeach; ?>

</div>

<!-- 🔥 TOMBOL KEMBALI -->
<div style="margin-top:20px;">
    <a href="/kasir/dashboard"
       style="background:#444;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;">
       ← Kembali
    </a>
</div>

<?= $this->endSection(); ?>