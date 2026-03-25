<h2>Kursus | Paket</h2>

<?php foreach($kursus as $k): ?>
<div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <h3><?= $k['nama_kursus']; ?></h3>
    <p>Harga: <?= $k['harga']; ?></p>
    <p>Slot: <?= $k['slot']; ?></p>

    <?php if($k['slot'] > 0): ?>
        <button onclick="pilihItem('kursus',<?= $k['id']; ?>,'<?= $k['nama_kursus']; ?>',<?= $k['harga']; ?>)">
            Tersedia
        </button>
    <?php else: ?>
        <button disabled>Penuh</button>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<script>
function pilihItem(tipe,id,nama,harga){
    let items = JSON.parse(localStorage.getItem('cart') || '[]');

    items.push({tipe,id,nama,harga});

    localStorage.setItem('cart', JSON.stringify(items));

    window.location = "/kasir/transaksi";
}
</script>