<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <a href="/kasir/pilih">← Kembali</a>

    <h2 style="margin-bottom:10px;"><?= $k['nama_kursus']; ?></h2>

    <!-- ================= DESKRIPSI ================= -->
    <div style="margin-bottom:20px; color:#555;">
        <?= $k['deskripsi']; ?>
    </div>

    <!-- ================= INFO KURSUS ================= -->
    <div style="background:#f8f9fa;padding:20px;border-radius:12px;margin-bottom:25px;box-shadow:0 2px 5px rgba(0,0,0,0.1);">
        
        <h3 style="margin-bottom:10px;">📚 Informasi Kursus</h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">

            <div>
                <b>👨‍🏫 Instruktur:</b><br>
                <?= $k['instruktur']; ?>
            </div>

            <div>
                <b>⏱️ Durasi:</b><br>
                <?= $k['durasi']; ?>
            </div>

            <div>
                <b>🕒 Jadwal:</b><br>
                <?= $k['jam_mulai']; ?> - <?= $k['jam_selesai']; ?>
            </div>

            <div>
                <b>👥 Slot:</b><br>
                <span style="color:<?= $k['slot'] > 0 ? 'green' : 'red'; ?>">
                    <?= $k['slot']; ?> siswa
                </span>
            </div>

        </div>

        <br>

        <div>
            <b>📅 Hari:</b><br>
            <?php if($k['hari']): ?>
                <?php foreach(explode(',', $k['hari']) as $h): ?>
                    <span style="background:#333;color:white;padding:5px 10px;border-radius:8px;margin-right:5px;">
                        <?= trim($h); ?>
                    </span>
                <?php endforeach; ?>
            <?php else: ?>
                -
            <?php endif; ?>
        </div>

    </div>

    <!-- ================= LEVEL ================= -->
    <h3 style="margin-bottom:15px;">🎯 Pilih Level</h3>

    <?php if(empty($level)): ?>
        <p style="color:red;">Belum ada level</p>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:15px;">

    <?php foreach($level as $l): ?>

        <div style="border-radius:12px;padding:20px;background:white;
                    box-shadow:0 4px 10px rgba(0,0,0,0.1);">

            <h3 style="margin:0;"><?= $l['nama_level']; ?></h3>

            <p style="color:#666;"><?= $l['deskripsi']; ?></p>

            <hr>

            <!-- 🔥 INFO TAMBAHAN -->
            <div style="font-size:14px; line-height:1.6;">

                <div>📆 <b><?= $l['pertemuan'] ?? 4; ?>x pertemuan / bulan</b></div>

                <div>⏳ Durasi per pertemuan: <?= $k['durasi']; ?></div>


            </div>

            <hr>

            <!-- HARGA -->
            <div style="font-size:18px; font-weight:bold; color:#e53935;">
                Rp <?= number_format($l['harga'],0,',','.'); ?>/bulan
            </div>

            <br>

            <button 
                style="width:100%; background:#e53935;color:white;border:none;
                       padding:10px;border-radius:8px;cursor:pointer;font-weight:bold;"
                onclick="pilihLevel(
                    <?= $l['id']; ?>,
                    '<?= $k['nama_kursus']; ?> - <?= $l['nama_level']; ?>',
                    <?= $l['harga']; ?>
                )"
                <?= $k['slot'] <= 0 ? 'disabled' : '' ?>
            >
                <?= $k['slot'] <= 0 ? '❌ Penuh' : '✔ Pilih Level' ?>
            </button>

        </div>

    <?php endforeach; ?>

    </div>

</div>

<script>
function pilihLevel(id, nama, harga){

    let cart = JSON.parse(localStorage.getItem('cart') || '[]');

    let item = {
        id: id,
        nama: nama,
        harga: harga,
        tipe: 'kursus'
    };

    // 🔥 tambah ke cart
    cart.push(item);

    localStorage.setItem('cart', JSON.stringify(cart));

    // 🔥 simpan kursus terakhir (untuk tombol kembali)
    localStorage.setItem('last_kursus', <?= $k['id']; ?>);

    // 🔥 tanda bahwa dari detail (biar tidak ke-reset)
    localStorage.setItem('from_detail', '1');

    // 🔥 redirect (HARUS TERAKHIR)
    window.location.href = "/kasir/transaksi";
}
</script>

<?= $this->endSection(); ?>