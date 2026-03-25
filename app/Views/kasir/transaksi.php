<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

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

                Bayar:<br>
                <input type="number" name="bayar" id="bayar" required style="width:100%; padding:8px;"><br><br>

            </div>

            <!-- KANAN -->
            <div style="flex:1; background:#2b2b2b; padding:20px; border-radius:10px;">

                <h3>Ringkasan Transaksi</h3>

                <!-- tombol tambah -->
                <button type="button" onclick="window.location='/kasir/pilih'"
                    style="background:#444;color:white;padding:8px 15px;border:none;border-radius:8px;">
                    + Tambah
                </button>

                <br><br>

                <!-- LIST ITEM -->
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
let items = JSON.parse(localStorage.getItem('cart') || '[]');

let total = 0;
let html = '';

// render item
items.forEach((i,index)=>{
    total += parseInt(i.harga);

    html += `
        <div style="margin-bottom:10px; padding:10px; background:#3a3a3a; border-radius:8px;">
            <b>${i.nama}</b><br>
            Rp ${formatRupiah(i.harga)}

            <button type="button" onclick="hapus(${index})"
                style="float:right;background:#900;color:white;border:none;padding:5px 10px;border-radius:5px;">
                X
            </button>
        </div>
    `;
});

document.getElementById('list').innerHTML = html;
document.getElementById('total').innerText = formatRupiah(total);

// input bayar
document.getElementById('bayar').addEventListener('input', function(){
    let bayar = parseInt(this.value) || 0;

    document.getElementById('bayarText').innerText = formatRupiah(bayar);

    let kembali = bayar - total;
    document.getElementById('kembali').innerText = formatRupiah(kembali);
});

// hapus item
function hapus(index){
    items.splice(index,1);
    localStorage.setItem('cart', JSON.stringify(items));
    location.reload();
}

// kirim data
function kirimData(){

    if(items.length === 0){
        alert('Pilih kursus atau paket dulu!');
        return false;
    }

    let bayar = parseInt(document.getElementById('bayar').value) || 0;

    if(bayar < total){
        alert('Uang kurang!');
        return false;
    }

    document.getElementById('items').value = JSON.stringify(items);

    // bersihkan cart setelah submit
    localStorage.removeItem('cart');

    return true;
}

// format rupiah
function formatRupiah(angka){
    return new Intl.NumberFormat('id-ID').format(angka);
}
</script>

<?= $this->endSection(); ?>