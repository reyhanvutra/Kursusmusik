<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

<a href="/kasir/dashboard" onclick="resetCart()">← Kembali</a>

<h2>TRANSAKSI</h2>

<form action="/kasir/simpan" method="post" onsubmit="return kirimData()">

<div style="display:flex; gap:30px;">

<!-- ================= KIRI ================= -->
<div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px;">

<h3>Data Siswa</h3>

<!-- 🔍 SEARCH -->
<div id="searchBox">
Cari Siswa:<br>
<input type="text" id="searchSiswa" placeholder="Ketik nama siswa..."
    style="width:100%; padding:8px;"><br><br>

<select id="listSiswa" size="5" style="width:100%; padding:8px; display:none;">
<?php foreach($siswa as $s): ?>
<option 
    value="<?= $s['id']; ?>"
    data-nama="<?= $s['nama']; ?>"
    data-nohp="<?= $s['no_hp']; ?>"
    data-alamat="<?= $s['alamat']; ?>"
    data-daftar="<?= $s['sudah_daftar']; ?>"
>
    <?= $s['nama']; ?> (<?= $s['no_hp']; ?>)
</option>
<?php endforeach; ?>
</select>
</div>

<input type="hidden" name="id_siswa" id="id_siswa">

<!-- 📄 INFO -->
<div id="infoSiswa" style="display:none; margin-top:15px;">

<div style="background:#1f1f1f; padding:15px; border-radius:10px;">

<div style="display:flex; justify-content:space-between;">
<b>Data Siswa</b>
<button type="button" onclick="resetSiswa()" style="background:red;color:white;border:none;padding:5px 10px;border-radius:5px;">
Ganti
</button>
</div>

<hr>

Nama:<br>
<input type="text" id="nama" readonly style="width:100%; padding:8px;"><br><br>

No HP:<br>
<input type="text" id="nohp" readonly style="width:100%; padding:8px;"><br><br>

Alamat:<br>
<textarea id="alamat" readonly style="width:100%; padding:8px;"></textarea><br><br>

Status:
<div id="statusSiswa" style="font-weight:bold;"></div>

</div>

</div>

<br>

<button type="button" onclick="toggleForm()">+ Tambah Siswa</button>

<div id="formSiswa" style="display:none; margin-top:15px;">
<hr>

Nama:<br>
<input type="text" id="nama_siswa" style="width:100%; padding:8px;"><br><br>

No HP:<br>
<input type="text" id="nohp_siswa" style="width:100%; padding:8px;"><br><br>

Alamat:<br>
<textarea id="alamat_siswa" style="width:100%; padding:8px;"></textarea><br><br>

<button type="button" onclick="simpanSiswa()">Simpan Siswa</button>

</div>

<hr>

<h3>Pembayaran</h3>

Bulan:<br>
<input type="number" id="bulan" value="1" min="1" style="width:100%; padding:8px;"><br><br>

Tanggal Mulai:<br>
<input type="date" id="tanggal_mulai" style="width:100%; padding:8px;"><br><br>

Tanggal Selesai:<br>
<input type="date" id="tanggal_selesai" readonly style="width:100%; padding:8px;"><br><br>

Bayar:<br>
<input type="number" name="bayar" id="bayar" required style="width:100%; padding:8px;"><br><br>

</div>

<!-- ================= KANAN ================= -->
<div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px;">

<h3>Ringkasan</h3>

<button type="button" onclick="tambahItem()">+ Tambah</button>

<br><br>

<div id="list"></div>

<hr>

Subtotal: <b id="subtotal">0</b><br>

<div id="biayaDaftarBox" style="display:none; color:orange;">
Biaya Pendaftaran: <b id="biayaDaftar"></b><br>
</div>

<hr>

<div style="font-size:18px;">
TOTAL: <b id="total">0</b><br>
</div>

<br>

Bayar: <b id="bayarText">0</b><br>
Kembalian: <b id="kembali">0</b><br><br>

<input type="hidden" name="items" id="items">

<button type="submit">Bayar</button>

</div>

</div>

</form>
</div>

<script>

// ================= GLOBAL FUNCTION (ANTI ERROR) =================
function toggleForm(){
    let f = document.getElementById('formSiswa');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

function tambahItem(){
    window.location = '/kasir/pilih';
}

function resetCart(){
    localStorage.removeItem('cart');
}

// ================= INIT =================
let BIAYA_DAFTAR = <?= $setting['biaya_pendaftaran'] ?? 0 ?>;
let biayaDaftarAktif = 0;
let items = JSON.parse(localStorage.getItem('cart') || '[]');

// ================= SEARCH =================
document.getElementById('searchSiswa').addEventListener('input', function(){
    let keyword = this.value.toLowerCase();
    let options = document.querySelectorAll('#listSiswa option');
    let ada = false;

    options.forEach(opt => {
        if(opt.text.toLowerCase().includes(keyword) && keyword !== ''){
            opt.style.display = '';
            ada = true;
        }else{
            opt.style.display = 'none';
        }
    });

    document.getElementById('listSiswa').style.display = ada ? 'block' : 'none';
});

// ================= PILIH SISWA =================
document.getElementById('listSiswa').addEventListener('change', function(){

    let s = this.options[this.selectedIndex];

    document.getElementById('id_siswa').value = s.value;
    document.getElementById('nama').value = s.dataset.nama;
    document.getElementById('nohp').value = s.dataset.nohp;
    document.getElementById('alamat').value = s.dataset.alamat;

    document.getElementById('infoSiswa').style.display = 'block';
    document.getElementById('searchBox').style.display = 'none';

    let status = parseInt(s.dataset.daftar || 0);

    if(status === 0){
        biayaDaftarAktif = BIAYA_DAFTAR;
        document.getElementById('statusSiswa').innerHTML = "🟠 Siswa Baru";
        document.getElementById('statusSiswa').style.color = "orange";

        document.getElementById('biayaDaftarBox').style.display = 'block';
        document.getElementById('biayaDaftar').innerText = formatRupiah(biayaDaftarAktif);
    }else{
        biayaDaftarAktif = 0;
        document.getElementById('statusSiswa').innerHTML = "🟢 Siswa Lama";
        document.getElementById('statusSiswa').style.color = "lightgreen";

        document.getElementById('biayaDaftarBox').style.display = 'none';
    }

    render();
});

// ================= RESET SISWA =================
function resetSiswa(){
    document.getElementById('id_siswa').value = '';
    document.getElementById('infoSiswa').style.display = 'none';
    document.getElementById('searchBox').style.display = 'block';

    biayaDaftarAktif = 0;
    document.getElementById('biayaDaftarBox').style.display = 'none';

    render();
}

// ================= TANGGAL =================
document.getElementById('tanggal_mulai').addEventListener('change', hitungTanggal);
document.getElementById('bulan').addEventListener('input', hitungTanggal);
document.getElementById('bayar').addEventListener('input', render);

function hitungTanggal(){
    let mulai = document.getElementById('tanggal_mulai').value;
    let bulan = parseInt(document.getElementById('bulan').value) || 1;

    if(!mulai) return;

    let t = new Date(mulai);
    t.setMonth(t.getMonth() + bulan);

    document.getElementById('tanggal_selesai').value = t.toISOString().split('T')[0];
}

// ================= RENDER =================
function render(){

    let bulan = parseInt(document.getElementById('bulan').value) || 1;

    let subtotal = 0;
    let html = '';

    items.forEach((i,index)=>{
        let harga = i.tipe == 'kursus' ? i.harga * bulan : i.harga;
        subtotal += harga;

        html += `
        <div style="margin-bottom:8px;">
            ${i.nama}
            <button onclick="hapus(${index})" style="float:right;">X</button>
            <div style="text-align:right;">Rp ${formatRupiah(harga)}</div>
        </div>`;
    });

    let total = subtotal + biayaDaftarAktif;

    document.getElementById('list').innerHTML = html;
    document.getElementById('subtotal').innerText = formatRupiah(subtotal);
    document.getElementById('total').innerText = formatRupiah(total);

    let bayar = parseInt(document.getElementById('bayar').value) || 0;

    document.getElementById('bayarText').innerText = formatRupiah(bayar);
    document.getElementById('kembali').innerText = formatRupiah(bayar - total);
}

function hapus(i){
    items.splice(i,1);
    localStorage.setItem('cart', JSON.stringify(items));
    render();
}

// ================= SUBMIT =================
function kirimData(){

    if(!document.getElementById('id_siswa').value){
        alert('Pilih siswa dulu!');
        return false;
    }

    let mulai = document.getElementById('tanggal_mulai').value;
    let selesai = document.getElementById('tanggal_selesai').value;
    let bulan = document.getElementById('bulan').value;

    items = items.map(i => ({
        ...i,
        bulan,
        tanggal_mulai: mulai,
        tanggal_selesai: selesai
    }));

    document.getElementById('items').value = JSON.stringify(items);

    localStorage.removeItem('cart');

    return true;
}

function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}

// INIT RENDER
render();

</script>

<?= $this->endSection(); ?>