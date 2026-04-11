<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/transaksi.css'); ?>">

<div class="container">
    <a href="#" onclick="kembaliKeKursus()" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali 
    </a>

    <h2 class="judul">Transaksi</h2>

    <form action="/kasir/simpan" method="post" onsubmit="return kirimData()">
         <input type="hidden" name="id_siswa" id="id_siswa">
        <div class="wrapper">
           <div class="box kiri">
    <div class="section-title">
        <i class="fa-solid fa-user-graduate"></i>
        <h3>Data Siswa</h3>
    </div>

    <div class="search-container">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchSiswa" placeholder="Cari nama siswa..." class="input">
    </div>

    <select id="listSiswa" size="5" class="input custom-select" style="margin-top:10px;">
        <?php foreach($siswa as $s): ?>
        <option value="<?= $s['id']; ?>" data-nama="<?= $s['nama']; ?>" data-nohp="<?= $s['no_hp']; ?>" data-alamat="<?= $s['alamat']; ?>" data-daftar="<?= $s['sudah_daftar']; ?>">
            <?= $s['nama']; ?> — <?= $s['no_hp']; ?>
        </option>
        <?php endforeach; ?>
    </select>

    <div id="infoSiswa" class="info-siswa" style="display:none;">
        <div class="info-grid">
            <div class="info-item">
                <label>Nama Lengkap</label>
                <input type="text" id="nama" readonly class="input-plain">
            </div>
            <div class="info-item">
                <label>WhatsApp</label>
                <input type="text" id="nohp" readonly class="input-plain">
            </div>
        </div>
        <label>Alamat</label>
        <textarea id="alamat" readonly class="input-plain" style="resize:none; height:40px;"></textarea>
        <div id="statusSiswa"></div>
    </div>

    <button type="button" onclick="toggleForm()" class="btn-secondary" style="margin-top:15px;">
        <i class="fa-solid fa-user-plus"></i> Tambah Siswa Baru
    </button>

    <div id="formSiswa" class="form-siswa-extra" style="display:none; margin-top:15px;">
        <div class="form-grid">
            <input type="text" id="nama_siswa" placeholder="Nama Lengkap" class="input">
            <input type="text" id="nohp_siswa" placeholder="Nomor HP" class="input">
        </div>
        <textarea id="alamat_siswa" placeholder="Alamat Lengkap" class="input" style="margin-bottom:10px;"></textarea>
        <button type="button" onclick="simpanSiswa()" class="btn-success" style="padding:12px; font-size:12px;">Simpan & Pilih</button>
    </div>

    <div class="divider"></div>

    <div class="section-title">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <h3>Konfigurasi Pembayaran</h3>
    </div>

    <div class="payment-grid">
        <div class="input-group">
            <label>Durasi (Bulan)</label>
            <input type="number" id="bulan" value="1" min="1" class="input">
        </div>
        <div class="input-group">
            <label>Tanggal Mulai</label>
            <input type="date" id="tanggal_mulai" class="input">
        </div>
        <div class="input-group">
            <label>Estimasi Selesai</label>
            <input type="date" id="tanggal_selesai" readonly class="input readonly">
        </div>
    </div>

    <div class="input-group">
        <label>Jumlah Bayar</label>
        <div class="currency-input">
            <span>Rp</span>
            <input type="number" name="bayar" id="bayar" required placeholder="0">
        </div>
    </div>
</div>

            <div class="box kanan sticky-box">
                <div class="section-title">
                    <i class="fa-solid fa-list-check"></i>
                    <h3>Ringkasan Pesanan</h3>
                </div>

                <div id="list" class="item-list-container"></div>

                <div class="summary-details">
                    <div class="summary-line">
                        <span>Subtotal Kursus</span>
                        <b id="subtotal">0</b>
                    </div>
                    <div id="biayaDaftarBox" style="display:none;" class="summary-line registration">
                        <span>Biaya Pendaftaran</span>
                        <b id="biayaDaftar">0</b>
                    </div>
                    <div class="divider"></div>
                    <div class="total-section">
                        <p>Total Bayar</p>
                        <h2 id="total">0</h2>
                    </div>
                </div>

                <div class="change-section">
                    <div class="change-line">
                        <span>Dibayar</span>
                        <span id="bayarText">0</span>
                    </div>
                    <div class="change-line highlight">
                        <span>Kembalian</span>
                        <span id="kembali">0</span>
                    </div>
                </div>

                <input type="hidden" name="items" id="items">
                <button type="submit" class="btn-success">
                    Bayar 
                </button>
                
                <button type="button" onclick="tambahItem()" class="btn-add-more">
                    + Tambah Kursus Lain
                </button>
            </div>
        </div>
    </form>
</div>

<script>

// ================= AUTO RESET =================
if (localStorage.getItem('from_detail') !== '1') {
    localStorage.removeItem('cart');
}
localStorage.removeItem('from_detail');

let BIAYA_DAFTAR = <?= $setting['biaya_pendaftaran'] ?? 0 ?>;
let biayaDaftarAktif = 0;
let items = JSON.parse(localStorage.getItem('cart') || '[]');


// ================= LOAD SISWA =================
let savedSiswa = JSON.parse(localStorage.getItem('siswa') || 'null');

if(savedSiswa){
    document.getElementById('id_siswa').value = savedSiswa.id;
    document.getElementById('nama').value = savedSiswa.nama;
    document.getElementById('nohp').value = savedSiswa.nohp;
    document.getElementById('alamat').value = savedSiswa.alamat;

    document.getElementById('infoSiswa').style.display = 'block';

    if(parseInt(savedSiswa.daftar) === 0){
        biayaDaftarAktif = BIAYA_DAFTAR;
        document.getElementById('statusSiswa').innerHTML = "🟠 Siswa Baru";
        document.getElementById('biayaDaftarBox').style.display = 'block';
        document.getElementById('biayaDaftar').innerText = formatRupiah(biayaDaftarAktif);
    } else {
        biayaDaftarAktif = 0;
        document.getElementById('statusSiswa').innerHTML = "🟢 Siswa Lama";
        document.getElementById('biayaDaftarBox').style.display = 'none';
    }
}


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

    // 🔥 SIMPAN SISWA
    localStorage.setItem('siswa', JSON.stringify({
        id: s.value,
        nama: s.dataset.nama,
        nohp: s.dataset.nohp,
        alamat: s.dataset.alamat,
        daftar: s.dataset.daftar
    }));

    render();
});


// ================= RESET SISWA =================
function resetSiswa(){
    document.getElementById('id_siswa').value = '';
    document.getElementById('infoSiswa').style.display = 'none';
    biayaDaftarAktif = 0;
    localStorage.removeItem('siswa');
    render();
}


// ================= TOGGLE FORM =================
function toggleForm(){
    let f = document.getElementById('formSiswa');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}


// ================= SIMPAN SISWA AJAX =================
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

        // DUPLIKAT
        if(res.status === 'duplicate'){

            let s = res.data;

            document.getElementById('id_siswa').value = s.id;
            document.getElementById('nama').value = s.nama;
            document.getElementById('nohp').value = s.no_hp;
            document.getElementById('alamat').value = s.alamat;

            document.getElementById('infoSiswa').style.display = 'block';

            localStorage.setItem('siswa', JSON.stringify({
                id: s.id,
                nama: s.nama,
                nohp: s.no_hp,
                alamat: s.alamat,
                daftar: s.sudah_daftar
            }));

            render();
            return;
        }

        // SUCCESS
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

            select.value = res.id;
            select.dispatchEvent(new Event('change'));

            // 🔥 SIMPAN
            localStorage.setItem('siswa', JSON.stringify({
                id: res.id,
                nama: res.nama,
                nohp: res.no_hp,
                alamat: res.alamat,
                daftar: 0
            }));

            document.getElementById('nama_siswa').value = '';
            document.getElementById('nohp_siswa').value = '';
            document.getElementById('alamat_siswa').value = '';
        }

    });
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
            <button type="button" onclick="hapus(${index})">X</button>
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

    localStorage.setItem('cart', JSON.stringify(items));
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
    let selesai = document.getElementById('tanggal_selesai').value;

    items = items.map(i => ({
        ...i,
        bulan: bulan,
        tanggal_mulai: mulai,
        tanggal_selesai: selesai
    }));

    document.getElementById('items').value = JSON.stringify(items);

    // 🔥 CLEAR SEMUA
    localStorage.removeItem('cart');
    localStorage.removeItem('last_kursus');
    localStorage.removeItem('siswa');

    return true;
}


// ================= NAVIGASI =================
function tambahItem(){
    localStorage.setItem('from_detail', '1');
    window.location.href = '/kasir/pilih';
}

function kembaliKeKursus(){
    localStorage.removeItem('cart');
    localStorage.removeItem('siswa');

    let id = localStorage.getItem('last_kursus');
    if(id){
        window.location.href = "/kasir/detail/kursus/" + id;
    }else{
        window.location.href = "/kasir/pilih";
    }
}


// ================= FORMAT =================
function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}


// INIT
render();

</script>

<?= $this->endSection(); ?>