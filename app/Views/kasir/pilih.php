<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>Kursus & Paket</h2>

    <!-- TAB -->
    <a href="?tab=kursus">Kursus</a> |
    <a href="?tab=paket">Paket</a>

    <hr>

    <?php $tab = $_GET['tab'] ?? 'kursus'; ?>

    <!-- ================= KURSUS ================= -->
    <?php if($tab == 'kursus'): ?>

        <h3>Daftar Kursus</h3>

        <?php foreach($kursus as $k): ?>
        <div style="border:1px solid #ccc;padding:15px;margin:10px;border-radius:10px;">

            <!-- GAMBAR -->
            <?php if($k['gambar']): ?>
                <img src="/uploads/<?= $k['gambar']; ?>" width="120" style="border-radius:8px;"><br><br>
            <?php endif; ?>

            <!-- INFO -->
            <b><?= $k['nama_kursus']; ?></b><br><br>

            💰 Harga: Rp <?= number_format($k['harga'],0,',','.'); ?><br>
            👨‍🏫 Instruktur: <?= $k['instruktur']; ?><br>
            📅 Hari: <?= $k['hari']; ?><br>
            ⏰ Jadwal: <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?><br>
            📦 Slot: <?= $k['slot']; ?><br><br>

            <!-- BUTTON -->
            <?php if($k['slot'] > 0): ?>

                <!-- PILIH -->
                <button onclick="pilihItem('kursus',<?= $k['id']; ?>,'<?= $k['nama_kursus']; ?>',<?= $k['harga']; ?>)"
                    style="background:green;color:white;padding:8px 15px;border:none;border-radius:6px;">
                    Pilih
                </button>

                <!-- DETAIL -->
                <a href="/kasir/detail/kursus/<?= $k['id']; ?>"
                    style="background:#555;color:white;padding:8px 15px;text-decoration:none;border-radius:6px;">
                    Lihat Detail
                </a>

            <?php else: ?>

                <button disabled style="background:#999;color:white;padding:8px 15px;border:none;">
                    Penuh
                </button>

            <?php endif; ?>

        </div>
        <?php endforeach; ?>

    <!-- ================= PAKET ================= -->
    <?php else: ?>

      <h3>Daftar Paket</h3>

<?php foreach($paket as $p): ?>
<div style="border:1px solid #ccc;padding:15px;margin:10px;border-radius:10px;">

    <b><?= $p['nama_paket']; ?></b><br><br>

    💰 Rp <?= number_format($p['harga'],0,',','.'); ?><br><br>

    📅 Hari:<br>
    <?php foreach(explode(',', $p['hari']) as $h): ?>
        <span style="background:#444;color:white;padding:3px 8px;border-radius:5px;">
            <?= trim($h); ?>
        </span>
    <?php endforeach; ?>
    <br><br>

    📚 Isi Paket:<br>
    <ul>
        <?php foreach(explode(',', $p['list_kursus']) as $k): ?>
            <li><?= $k; ?></li>
        <?php endforeach; ?>
    </ul>

    <p><?= $p['deskripsi']; ?></p>

    <button onclick="pilihItem('paket',<?= $p['id']; ?>,'<?= $p['nama_paket']; ?>',<?= $p['harga']; ?>)"
        style="background:blue;color:white;padding:8px 15px;border:none;">
        Pilih Paket
    </button>

</div>
<?php endforeach; ?>

    <?php endif; ?>

    <br>
    <a href="/kasir/dashboard" 
       style="background:#444;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">
       ← Kembali
    </a>

</div>

<script>
function pilihItem(tipe,id,nama,harga){

    let items = JSON.parse(localStorage.getItem('cart') || '[]');

    items.push({
        tipe: tipe,
        id: id,
        nama: nama,
        harga: harga
    });

    localStorage.setItem('cart', JSON.stringify(items));

    // 🔥 penting supaya transaksi tidak reset
    localStorage.setItem('dari_pilih', '1');

    window.location.href = "/kasir/transaksi";
}
</script>

<?= $this->endSection(); ?>