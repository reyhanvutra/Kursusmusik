<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

<a href="/kasir/dashboard">← Kembali</a>

<h2>TRANSAKSI</h2>

<form action="/kasir/simpan" method="post" onsubmit="return kirimData()">

    <div style="display:flex; gap:30px;">

        <!-- KIRI -->
        <div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px;">

            <h3>Pembayaran & Data</h3>

            Nama:<br>
            <input type="text" name="nama" required style="width:100%; padding:8px;"><br><br>

            No HP:<br>
            <input type="text" name="nohp" required style="width:100%; padding:8px;"><br><br>

            Bulan (khusus kursus):<br>
            <input type="number" id="bulan" value="1" min="1" style="width:100%; padding:8px;"><br><br>

            Bayar:<br>
            <input type="number" name="bayar" id="bayar" required style="width:100%; padding:8px;"><br><br>

        </div>

        <!-- KANAN -->
        <div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px;">

            <h3>Ringkasan Transaksi</h3>

            <button type="button" onclick="tambahItem()"
                style="background:#444;color:white;padding:8px 15px;border:none;border-radius:8px;">
                + Tambah
            </button>

            <br><br>

            <div id="list"></div>

            <hr>

            Total: <b id="total">0</b><br>
            Bayar: <b id="bayarText">0</b><br>
            Kembalian: <b id="kembali">0</b><br><br>

            <input type="hidden" name="items" id="items">

            <button type="submit"
                style="background:red;color:white;padding:10px 20px;border:none;border-radius:8px;">
                Bayar
            </button>

        </div>

    </div>

</form>

</div>

<script>
// INIT
if(!localStorage.getItem('cart')){
    localStorage.setItem('cart', JSON.stringify([]));
}

let items = JSON.parse(localStorage.getItem('cart'));

// TAMBAH ITEM
function tambahItem(){
    localStorage.setItem('dari_pilih', '1');
    window.location = '/kasir/pilih';
}

// LOAD
window.onload = function(){

    if(!localStorage.getItem('dari_pilih')){
        localStorage.removeItem('cart');
        localStorage.removeItem('nama');
        localStorage.removeItem('nohp');
        localStorage.setItem('cart', JSON.stringify([]));
        items = [];
    }

    localStorage.removeItem('dari_pilih');

    if(localStorage.getItem('nama')){
        document.querySelector('input[name="nama"]').value = localStorage.getItem('nama');
    }

    if(localStorage.getItem('nohp')){
        document.querySelector('input[name="nohp"]').value = localStorage.getItem('nohp');
    }

    render();
};

// SIMPAN INPUT
document.addEventListener('DOMContentLoaded', function(){

    document.querySelector('input[name="nama"]').addEventListener('input', function(){
        localStorage.setItem('nama', this.value);
    });

    document.querySelector('input[name="nohp"]').addEventListener('input', function(){
        localStorage.setItem('nohp', this.value);
    });

});

// RENDER
function render(){

    let bulan = parseInt(document.getElementById('bulan').value) || 1;

    let total = 0;
    let html = '';

    let hanyaPaket = true;

    items.forEach((i,index)=>{

        let hargaItem;

        if(i.tipe == 'kursus'){
            hargaItem = i.harga * bulan;
            hanyaPaket = false;
        }else{
            hargaItem = i.harga;
        }

        total += hargaItem;

        html += `
            <div style="margin-bottom:10px; padding:10px; background:#3a3a3a; border-radius:8px;">
                <b>${i.nama}</b><br>

                ${
                    i.tipe == 'kursus'
                    ? `Rp ${formatRupiah(i.harga)} x ${bulan} bulan`
                    : `Rp ${formatRupiah(i.harga)}`
                }

                <br><b>Rp ${formatRupiah(hargaItem)}</b>

                <button type="button" onclick="hapus(${index})"
                    style="float:right;background:#900;color:white;border:none;padding:5px 10px;border-radius:5px;">
                    X
                </button>
            </div>
        `;
    });

    // disable bulan kalau hanya paket
    document.getElementById('bulan').disabled = hanyaPaket;

    document.getElementById('list').innerHTML = html;
    document.getElementById('total').innerText = formatRupiah(total);

    let bayar = parseInt(document.getElementById('bayar').value) || 0;
    document.getElementById('bayarText').innerText = formatRupiah(bayar);
    document.getElementById('kembali').innerText = formatRupiah(bayar - total);
}

// EVENT
document.getElementById('bulan').addEventListener('input', render);
document.getElementById('bayar').addEventListener('input', render);

// HAPUS
function hapus(index){
    items.splice(index,1);
    localStorage.setItem('cart', JSON.stringify(items));
    render();
}

// SUBMIT
function kirimData(){

    if(items.length === 0){
        alert('Pilih item dulu!');
        return false;
    }

    let bulan = parseInt(document.getElementById('bulan').value) || 1;

    let total = 0;

    let itemsFix = items.map(i => {

        let hargaBaru;

        if(i.tipe == 'kursus'){
            hargaBaru = i.harga * bulan;
        }else{
            hargaBaru = i.harga;
        }

        total += hargaBaru;

        return {
            ...i,
            harga: hargaBaru,
            bulan: i.tipe == 'kursus' ? bulan : 1
        };
    });

    let bayar = parseInt(document.getElementById('bayar').value) || 0;

    if(bayar < total){
        alert('Uang kurang!');
        return false;
    }

    document.getElementById('items').value = JSON.stringify(itemsFix);

    localStorage.removeItem('cart');
    localStorage.removeItem('nama');
    localStorage.removeItem('nohp');

    return true;
}

// FORMAT
function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}
</script>

<?= $this->endSection(); ?>