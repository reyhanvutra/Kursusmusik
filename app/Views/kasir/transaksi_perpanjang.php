<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

<h2>🔄 Perpanjang Kursus</h2>

<form action="/kasir/perpanjang/simpan" method="post">

<input type="hidden" name="id_detail" value="<?= $id_detail ?>">

<div style="display:flex; gap:30px;">

<!-- ================= KIRI ================= -->
<div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px; color:white;">

<h3>Data Siswa</h3>

<div style="background:#1f1f1f; padding:15px; border-radius:10px;">
Nama:
<input type="text" value="<?= $siswa['nama']; ?>" readonly style="width:100%; padding:8px;"><br><br>

No HP:
<input type="text" value="<?= $siswa['no_hp']; ?>" readonly style="width:100%; padding:8px;"><br><br>

Alamat:
<textarea readonly style="width:100%; padding:8px;"><?= $siswa['alamat']; ?></textarea><br><br>

Status:
<span style="color:lime;">🟢 Aktif</span>
</div>

<hr>

<h3>Pembayaran</h3>

Bulan:
<input type="number" name="bulan" id="bulan" value="1" min="1" style="width:100%; padding:8px;"><br><br>

Tanggal Mulai:
<input type="date" id="tanggal_mulai" value="<?= $tanggal_mulai ?>" readonly style="width:100%; padding:8px;"><br><br>

Tanggal Selesai:
<input type="date" id="tanggal_selesai" readonly style="width:100%; padding:8px;"><br><br>

Bayar:
<input type="number" id="bayar" style="width:100%; padding:8px;"><br><br>

</div>

<!-- ================= KANAN ================= -->
<div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px; color:white;">

<h3>Ringkasan</h3>

<div id="list"></div>

<hr>

Subtotal: <b id="subtotal">0</b><br>

<hr>

<h3>TOTAL: <span id="total">0</span></h3>

Bayar: <b id="bayarText">0</b><br>
Kembalian: <b id="kembali">0</b><br><br>

<button type="submit">Bayar</button>

</div>

</div>

</form>
<div style="margin-top:20px;">
    <a href="/kasir/siswa/detail/<?= $siswa['id']; ?>"
       style="background:#555;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;">
       ← Kembali ke Detail Siswa
    </a>
</div>
</div>

<script>

let hargaPerBulan = <?= $item['harga']; ?>;
let namaItem = "<?= $item['nama']; ?>";

// HITUNG TANGGAL
document.getElementById('bulan').addEventListener('input', hitungTanggal);
document.getElementById('bayar').addEventListener('input', render);

function hitungTanggal(){
    let mulai = document.getElementById('tanggal_mulai').value;
    let bulan = parseInt(document.getElementById('bulan').value) || 1;

    let t = new Date(mulai);
    t.setMonth(t.getMonth() + bulan);

    document.getElementById('tanggal_selesai').value = t.toISOString().split('T')[0];

    render();
}

// RENDER UI
function render(){

    let bulan = parseInt(document.getElementById('bulan').value) || 1;
    let total = hargaPerBulan * bulan;

    document.getElementById('list').innerHTML = `
        <div>
            <b>${namaItem}</b>
            <div>Rp ${formatRupiah(hargaPerBulan)} x ${bulan}</div>
            <div><b>Rp ${formatRupiah(total)}</b></div>
        </div>
    `;

    document.getElementById('subtotal').innerText = formatRupiah(total);
    document.getElementById('total').innerText = formatRupiah(total);

    let bayar = parseInt(document.getElementById('bayar').value) || 0;

    document.getElementById('bayarText').innerText = formatRupiah(bayar);
    document.getElementById('kembali').innerText = formatRupiah(bayar - total);
}

function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}

// INIT
hitungTanggal();

</script>

<?= $this->endSection(); ?>