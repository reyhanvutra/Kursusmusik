<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/transaksi.css'); ?>">

<div class="container">

<a href="#" onclick="kembaliKeKursus()" class="btn-back">← Kembali</a>

<h2 class="judul">Transaksi</h2>

<form action="/kasir/simpan" method="post" onsubmit="return kirimData()">

<div class="wrapper">

<!-- ================= KIRI ================= -->
<div class="box kiri">

<h3>Data Siswa</h3>

<input type="text" id="searchSiswa" placeholder="Cari siswa..." class="input"><br><br>

<select id="listSiswa" size="6" class="input">
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

<input type="hidden" name="id_siswa" id="id_siswa">

<div id="infoSiswa" class="info-siswa" style="display:none;">
    <div class="header">
        <b>Info Siswa</b>
        <button type="button" onclick="resetSiswa()" class="btn-danger">Ganti</button>
    </div>

    <hr>

    Nama:
    <input type="text" id="nama" readonly class="input">

    No HP:
    <input type="text" id="nohp" readonly class="input">

    Alamat:
    <textarea id="alamat" readonly class="input"></textarea>

    Status:
    <div id="statusSiswa"></div>
</div>

<br>

<button type="button" onclick="toggleForm()" class="btn-secondary">+ Tambah Siswa</button>

<div id="formSiswa" class="form-siswa" style="display:none;">
<hr>

Nama:
<input type="text" id="nama_siswa" class="input">

No HP:
<input type="text" id="nohp_siswa" class="input">

Alamat:
<textarea id="alamat_siswa" class="input"></textarea>

<button type="button" onclick="simpanSiswa()" class="btn-primary">Simpan</button>
</div>

<hr>

<h3>Pembayaran</h3>

Bulan:
<input type="number" id="bulan" value="1" min="1" class="input">

Tanggal Mulai:
<input type="date" id="tanggal_mulai" class="input">

Tanggal Selesai:
<input type="date" id="tanggal_selesai" readonly class="input">

Bayar:
<input type="number" name="bayar" id="bayar" required class="input">

</div>

<!-- ================= KANAN ================= -->
<div class="box kanan">

<h3>Ringkasan</h3>

<button type="button" onclick="tambahItem()" class="btn-primary">+ Tambah</button>

<br><br>

<div id="list"></div>

<hr>

Subtotal: <b id="subtotal">0</b><br>

<div id="biayaDaftarBox" style="display:none;" class="biaya-daftar">
Biaya Pendaftaran: <b id="biayaDaftar"></b>
</div>

<hr>

<h3>Total: <span id="total">0</span></h3>

Bayar: <b id="bayarText">0</b><br>
Kembalian: <b id="kembali">0</b><br><br>

<input type="hidden" name="items" id="items">

<button type="submit" class="btn-success">Bayar</button>

</div>

</div>

</form>
</div>

<script>

// ================= INIT =================
if(!localStorage.getItem('from_detail')){
    localStorage.removeItem('cart');
}
localStorage.removeItem('from_detail');

let BIAYA_DAFTAR = <?= $setting['biaya_pendaftaran'] ?? 0 ?>;
let biayaDaftarAktif = 0;
let items = JSON.parse(localStorage.getItem('cart') || '[]');

// ================= SEARCH =================
document.getElementById('searchSiswa').addEventListener('input', function(){
    let keyword = this.value.toLowerCase();
    document.querySelectorAll('#listSiswa option').forEach(opt=>{
        opt.style.display = opt.text.toLowerCase().includes(keyword) ? '' : 'none';
    });
});

// ================= PILIH SISWA =================
document.getElementById('listSiswa').addEventListener('change', function(){

    let s = this.options[this.selectedIndex];

    document.getElementById('id_siswa').value = s.value;
    document.getElementById('nama').value = s.dataset.nama;
    document.getElementById('nohp').value = s.dataset.nohp;
    document.getElementById('alamat').value = s.dataset.alamat;

    document.getElementById('infoSiswa').style.display = 'block';

    let status = parseInt(s.dataset.daftar || 0);

    if(status === 0){
        biayaDaftarAktif = BIAYA_DAFTAR;
        document.getElementById('statusSiswa').innerHTML = "🟠 Siswa Baru";
        document.getElementById('biayaDaftarBox').style.display = 'block';
        document.getElementById('biayaDaftar').innerText = formatRupiah(biayaDaftarAktif);
    }else{
        biayaDaftarAktif = 0;
        document.getElementById('statusSiswa').innerHTML = "🟢 Siswa Lama";
        document.getElementById('biayaDaftarBox').style.display = 'none';
    }

    render();
});

// ================= RESET =================
function resetSiswa(){
    document.getElementById('id_siswa').value = '';
    document.getElementById('infoSiswa').style.display = 'none';
    biayaDaftarAktif = 0;
    render();
}

// ================= TOGGLE FORM =================
function toggleForm(){
    let f = document.getElementById('formSiswa');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
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
        let harga = i.harga * bulan;
        subtotal += harga;

        html += `
        <div class="item">
            <b>${i.nama}</b>
            <button onclick="hapus(${index})" class="btn-danger-mini">X</button>
            <div>Rp ${formatRupiah(i.harga)} x ${bulan}</div>
            <div><b>Rp ${formatRupiah(harga)}</b></div>
        </div>`;
    });

    let total = subtotal + biayaDaftarAktif;

    document.getElementById('list').innerHTML = html || '<i>Belum ada item</i>';
    document.getElementById('subtotal').innerText = formatRupiah(subtotal);
    document.getElementById('total').innerText = formatRupiah(total);

    let bayar = parseInt(document.getElementById('bayar').value) || 0;

    document.getElementById('bayarText').innerText = formatRupiah(bayar);
    document.getElementById('kembali').innerText = formatRupiah(bayar - total);
}

// ================= HAPUS =================
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

    let bulan = document.getElementById('bulan').value;
    let mulai = document.getElementById('tanggal_mulai').value;

    items = items.map(i => ({
        ...i,
        bulan: bulan,
        tanggal_mulai: mulai
    }));

    document.getElementById('items').value = JSON.stringify(items);

    localStorage.removeItem('cart');
    localStorage.removeItem('last_kursus');

    return true;
}

// ================= NAVIGASI =================
function tambahItem(){
    window.location = '/kasir/pilih';
}

function kembaliKeKursus(){
    localStorage.removeItem('cart');
    let id = localStorage.getItem('last_kursus');
    if(id){
        window.location.href = "/kasir/detail/kursus/" + id;
    }else{
        window.location.href = "/kasir/pilih";
    }
}

function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}

render();

</script>

<?= $this->endSection(); ?>