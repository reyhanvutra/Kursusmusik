<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

<a href="#" onclick="kembaliKeKursus()">← Kembali</a>

<h2>TRANSAKSI</h2>

<form action="/kasir/simpan" method="post" onsubmit="return kirimData()">

<div style="display:flex; gap:30px;">

<!-- ================= KIRI ================= -->
<div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px; color:white;">

<h3>Data Siswa</h3>

<!-- SEARCH -->
<input type="text" id="searchSiswa" placeholder="Cari siswa..." style="width:100%; padding:8px;"><br><br>

<select id="listSiswa" size="5" style="width:100%; padding:8px;">
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

<!-- INFO SISWA -->
<div id="infoSiswa" style="display:none; margin-top:15px; background:#1f1f1f; padding:15px; border-radius:10px;">

<div style="display:flex; justify-content:space-between;">
<b>Info Siswa</b>
<button type="button" onclick="resetSiswa()" style="background:red;color:white;border:none;padding:5px 10px;border-radius:5px;">
Ganti
</button>
</div>

<hr>

Nama:
<input type="text" id="nama" readonly style="width:100%; padding:8px;"><br><br>

No HP:
<input type="text" id="nohp" readonly style="width:100%; padding:8px;"><br><br>

Alamat:
<textarea id="alamat" readonly style="width:100%; padding:8px;"></textarea><br><br>

Status:
<div id="statusSiswa"></div>

</div>

<br>

<!-- TAMBAH SISWA -->
<button type="button" onclick="toggleForm()">+ Tambah Siswa</button>

<div id="formSiswa" style="display:none; margin-top:15px; background:#1f1f1f; padding:15px; border-radius:10px;">

<hr>

Nama:
<input type="text" id="nama_siswa" style="width:100%; padding:8px;"><br><br>

No HP:
<input type="text" id="nohp_siswa" style="width:100%; padding:8px;"><br><br>

Alamat:
<textarea id="alamat_siswa" style="width:100%; padding:8px;"></textarea><br><br>

<button type="button" onclick="simpanSiswa()">Simpan</button>

</div>

<hr>

<h3>Pembayaran</h3>

Bulan:
<input type="number" id="bulan" value="1" min="1" style="width:100%; padding:8px;"><br><br>

Tanggal Mulai:
<input type="date" id="tanggal_mulai" style="width:100%; padding:8px;"><br><br>

Tanggal Selesai:
<input type="date" id="tanggal_selesai" readonly style="width:100%; padding:8px;"><br><br>

Bayar:
<input type="number" name="bayar" id="bayar" required style="width:100%; padding:8px;"><br><br>

</div>

<!-- ================= KANAN ================= -->
<div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px; color:white;">

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

<h3>TOTAL: <span id="total">0</span></h3>

Bayar: <b id="bayarText">0</b><br>
Kembalian: <b id="kembali">0</b><br><br>

<input type="hidden" name="items" id="items">

<button type="submit">Bayar</button>

</div>

</div>

</form>
</div>

<script>

// AUTO RESET
if(!localStorage.getItem('from_detail')){
    localStorage.removeItem('cart');
}
localStorage.removeItem('from_detail');

let BIAYA_DAFTAR = <?= $setting['biaya_pendaftaran'] ?? 0 ?>;
let biayaDaftarAktif = 0;
let items = JSON.parse(localStorage.getItem('cart') || '[]');

// SEARCH
document.getElementById('searchSiswa').addEventListener('input', function(){
    let keyword = this.value.toLowerCase();
    document.querySelectorAll('#listSiswa option').forEach(opt=>{
        opt.style.display = opt.text.toLowerCase().includes(keyword) ? '' : 'none';
    });
});

// PILIH SISWA
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

// 🔥 RESET SISWA
function resetSiswa(){
    document.getElementById('id_siswa').value = '';
    document.getElementById('infoSiswa').style.display = 'none';
    biayaDaftarAktif = 0;
    render();
}

// 🔥 TOGGLE FORM
function toggleForm(){
    let f = document.getElementById('formSiswa');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

// 🔥 SIMPAN SISWA AJAX
function simpanSiswa(){

    let nama   = document.getElementById('nama_siswa').value.trim();
    let nohp   = document.getElementById('nohp_siswa').value.trim();
    let alamat = document.getElementById('alamat_siswa').value.trim();

    if(!nama || !nohp){
        alert('Nama & No HP wajib diisi!');
        return;
    }

    fetch('/kasir/simpanSiswaAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            nama: nama,
            no_hp: nohp,
            alamat: alamat
        })
    })
    .then(res => res.json())
    .then(res => {

        // ================= DUPLIKAT =================
        if(res.status === 'duplicate'){

            alert('Siswa sudah ada, otomatis dipilih!');

            let s = res.data;

            document.getElementById('id_siswa').value = s.id;
            document.getElementById('nama').value = s.nama;
            document.getElementById('nohp').value = s.no_hp;
            document.getElementById('alamat').value = s.alamat;

            document.getElementById('infoSiswa').style.display = 'block';

            let status = parseInt(s.sudah_daftar || 0);

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
            return;
        }

        // ================= SUCCESS =================
        if(res.status === 'success'){

            let select = document.getElementById('listSiswa');

            let opt = document.createElement('option');
            opt.value = res.id;
            opt.text = res.nama + ' (' + res.no_hp + ')';
            opt.dataset.nama = res.nama;
            opt.dataset.nohp = res.no_hp;
            opt.dataset.alamat = res.alamat;
            opt.dataset.daftar = 0;

            select.appendChild(opt);

            alert('Siswa berhasil ditambahkan!');

            // 🔥 AUTO PILIH SISWA BARU
            select.value = res.id;
            select.dispatchEvent(new Event('change'));

            // 🔥 RESET FORM
            document.getElementById('nama_siswa').value = '';
            document.getElementById('nohp_siswa').value = '';
            document.getElementById('alamat_siswa').value = '';
        }

        // ================= ERROR =================
        if(res.status === 'error'){
            alert(res.message);
        }

    });
}
// TANGGAL
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

// RENDER
function render(){

    let bulan = parseInt(document.getElementById('bulan').value) || 1;

    let subtotal = 0;
    let html = '';

    items.forEach((i,index)=>{
        let harga = i.harga * bulan;
        subtotal += harga;

        html += `
        <div>
            <b>${i.nama}</b>
            <button onclick="hapus(${index})">X</button>
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

// HAPUS
function hapus(i){
    items.splice(i,1);
    localStorage.setItem('cart', JSON.stringify(items));
    render();
}

// SUBMIT
function kirimData(){

    if(!document.getElementById('id_siswa').value){
        alert('Pilih siswa dulu!');
        return false;
    }

    let bulan = document.getElementById('bulan').value;
    let mulai = document.getElementById('tanggal_mulai').value;
    let selesai = document.getElementById('tanggal_selesai').value;

    items = items.map(i => ({
        ...i,
        bulan: bulan,
        tanggal_mulai: mulai,
        tanggal_selesai: selesai
    }));

    document.getElementById('items').value = JSON.stringify(items);

    localStorage.removeItem('cart');
    localStorage.removeItem('last_kursus');

    return true;
}

// NAVIGASI
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