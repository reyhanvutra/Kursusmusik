x   <?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <!-- BACK -->
    <a href="/kasir/pilih"
       style="display:inline-block;margin-bottom:15px;color:#333;text-decoration:none;">
       ← Kembali ke Kategori
    </a>

    <!-- JUDUL -->
    <h2><?= $kategori['nama_kategori']; ?></h2>

    <!-- DESKRIPSI -->
    <div style="background:#f5f5f5;padding:15px;border-radius:10px;margin-bottom:20px;">
        <?= $kategori['deskripsi']; ?>
    </div>

    <!-- LIST KURSUS -->
    <h3>📚 Daftar Kursus</h3>

    <?php if(empty($kursus)): ?>
        <p style="color:red;">Belum ada kursus di kategori ini</p>
    <?php else: ?>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:15px;">

        <?php foreach($kursus as $k): ?>

        <div style="background:white;padding:15px;border-radius:10px;
                    box-shadow:0 2px 6px rgba(0,0,0,0.1);">

            <h4><?= $k['nama_kursus']; ?></h4>

            <p style="color:#666;font-size:14px;">
                <?= $k['deskripsi']; ?>
            </p>

            <a href="/kasir/detail/kursus/<?= $k['id']; ?>"
               style="display:inline-block;margin-top:10px;background:#4caf50;color:white;
                      padding:8px 12px;border-radius:6px;text-decoration:none;">
               🎯 Lihat Detail
            </a>

        </div>

        <?php endforeach; ?>

    </div>

    <?php endif; ?>

</div>

<?= $this->endSection(); ?>