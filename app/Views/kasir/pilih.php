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
                <a href="/kasir/detail/kursus/<?= $c['id']; ?>" 
                   style="text-decoration:none; color:blue;">
                    👉 <?= $c['nama_kursus']; ?>
                </a>
            </div>
        <?php endforeach; ?>

    </div>
<?php endforeach; ?>

</div>

<?= $this->endSection(); ?>