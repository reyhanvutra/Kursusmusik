<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/detail_kursus.css'); ?>">

<div class="detail-kursus-wrapper">

    <a href="/kasir/detail/kategori/<?= $k['id_kategori']; ?>" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke List Kursus
    </a>

    <header class="header-kursus">
        <h1 class="judul-kursus"><?= $k['nama_kursus']; ?></h1>
        <div class="deskripsi-kursus">
            <?= $k['deskripsi']; ?>
        </div>
    </header>

    <h3 class="judul-level"><span>Paket Level Tersedia</span></h3>

    <div class="grid-level">
        <?php foreach($level as $l): ?>
        <div class="card-level">
            
            <span class="slot-badge <?= $l['slot_sisa'] > 0 ? 'slot-available' : 'slot-full'; ?>">
                <?= $l['slot_sisa'] > 0 ? $l['slot_sisa'].' Slot Tersisa' : 'Kelas Penuh'; ?>
            </span>

            <h3><?= $l['badge']; ?> <?= $l['nama_level']; ?></h3>
            <p class="level-desc"><?= $l['deskripsi']; ?></p>

            <div class="info-list">
                <div class="info-item">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span><b><?= $l['pertemuan']; ?>x</b> Pertemuan tiap bulan</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-hourglass-half"></i>
                    <span>Durasi Sesi: <b><?= $l['durasi'] ?? '-' ?></b></span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-user-astronaut"></i>
                    <span>Mentor: <b><?= $l['nama_mentor'] ?? 'TBA' ?></b></span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Jadwal: <b><?= $l['hari']; ?></b> (<?= $l['jam_mulai']; ?> - <?= $l['jam_selesai']; ?>)</span>
                </div>
            </div>

            <div class="price-tag">
                <div class="price-value">
                    Rp <?= number_format($l['harga'],0,',','.'); ?> <span>/ bln</span>
                </div>
                
                <button 
                    class="btn-pilih"
                    onclick="pilihLevel(
                        <?= $l['id']; ?>,
                        '<?= $k['nama_kursus']; ?> - <?= $l['nama_level']; ?>',
                        <?= $l['harga']; ?>
                    )"
                    <?= $l['slot_sisa'] <= 0 ? 'disabled' : '' ?>
                >
                    <?= $l['slot_sisa'] <= 0 ? 'Quota Terlampaui' : 'Pilih Level' ?>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
function pilihLevel(id, nama, harga){
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');

    cart.push({
        id: id,
        nama: nama,
        harga: harga,
        tipe: 'kursus'
    });

    localStorage.setItem('cart', JSON.stringify(cart));
    localStorage.setItem('last_kursus', <?= $k['id']; ?>);
    localStorage.setItem('from_detail', '1');

    window.location.href = "/kasir/transaksi";
}
</script>

<?= $this->endSection(); ?>