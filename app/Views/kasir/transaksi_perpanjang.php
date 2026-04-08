<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/kasir/transaksi.css'); ?>">
<div class="container">

    <a href="/kasir/siswa/detail/<?= $siswa['id']; ?>" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Kembali 
    </a>

    <h2 class="judul">Perpanjang Kursus</h2>

    <form action="/kasir/perpanjang/simpan" method="post">
        <input type="hidden" name="id_detail" value="<?= $id_detail ?>">

        <div class="wrapper">

            <div class="box">
                <div class="section-title">
                    <i class="fa-solid fa-user-graduate"></i>
                    <h3>Informasi Siswa</h3>
                </div>

                <div class="info-siswa">
                    <div class="info-grid">
                        <div class="input-group">
                            <label>Nama Siswa</label>
                            <input type="text" class="input-plain" value="<?= $siswa['nama']; ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label>Nomor HP</label>
                            <input type="text" class="input-plain" value="<?= $siswa['no_hp']; ?>" readonly>
                        </div>
                    </div>
                    <div class="input-group" style="margin-top:10px;">
                        <label>Alamat</label>
                        <input type="text" class="input-plain" value="<?= $siswa['alamat']; ?>" readonly>
                    </div>
                    <span class="status-pill" style="background: rgba(0, 255, 100, 0.1); color: #00ff64; border: 1px solid rgba(0, 255, 100, 0.2);">
                        🟢 Status: Aktif
                    </span>
                </div>

                <div class="divider"></div>

                <div class="section-title">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <h3>Konfigurasi Perpanjangan</h3>
                </div>

                <div class="payment-grid">
                    <div class="input-group">
                        <label>Durasi (Bulan)</label>
                        <input type="number" name="bulan" id="bulan" value="1" min="1" class="input">
                    </div>
                    <div class="input-group">
                        <label>Mulai Sejak</label>
                        <input type="date" id="tanggal_mulai" value="<?= $tanggal_mulai ?>" readonly class="input" style="opacity: 0.6; cursor: not-allowed;">
                    </div>
                    <div class="input-group">
                        <label>Berakhir Pada</label>
                        <input type="date" id="tanggal_selesai" readonly class="input" style="color: #00d4ff; font-weight: 800;">
                    </div>
                </div>

                <div class="input-group" style="margin-top: 20px;">
                    <label>Nominal Bayar</label>
                    <div class="currency-input">
                        <span>Rp</span>
                        <input type="number" id="bayar" placeholder="0" required>
                    </div>
                </div>
            </div>

            <div class="box sticky-box">
                <div class="section-title">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <h3>Ringkasan Pembayaran</h3>
                </div>

                <div id="list" class="item-list-container">
                    </div>

                <div class="summary-line">
                    <span>Subtotal</span>
                    <span id="subtotal">0</span>
                </div>

                <div class="divider"></div>

                <div class="input-group">
                    <label>Total Yang Harus Dibayar</label>
                    <h1 id="total">0</h1>
                </div>

                <div class="summary-line">
                    <span>Uang Bayar</span>
                    <span id="bayarText" style="color: #fff; font-weight: 700;">0</span>
                </div>
                <div class="summary-line" style="margin-top: 10px;">
                    <span>Kembalian</span>
                    <span id="kembali" style="color: #00ff64; font-weight: 800; font-size: 18px;">0</span>
                </div>

                <button type="submit" class="btn-success" style="margin-top: 30px;">
                    <i class="fa-solid fa-check-circle"></i> Proses Perpanjangan
                </button>
            </div>

        </div>
    </form>
</div>

<script>
    let hargaPerBulan = <?= $item['harga']; ?>;
    let namaItem = "<?= $item['nama']; ?>";

    // Event Listeners
    document.getElementById('bulan').addEventListener('input', hitungTanggal);
    document.getElementById('bayar').addEventListener('input', render);

    function hitungTanggal() {
        let mulai = document.getElementById('tanggal_mulai').value;
        let bulan = parseInt(document.getElementById('bulan').value) || 1;

        let t = new Date(mulai);
        t.setMonth(t.getMonth() + bulan);

        document.getElementById('tanggal_selesai').value = t.toISOString().split('T')[0];
        render();
    }

    function render() {
        let bulan = parseInt(document.getElementById('bulan').value) || 1;
        let total = hargaPerBulan * bulan;
        let bayar = parseInt(document.getElementById('bayar').value) || 0;

        // Render Item List
        document.getElementById('list').innerHTML = `
            <div class="item">
                <div style="font-weight: 800; font-size: 15px; margin-bottom: 5px;">${namaItem}</div>
                <div style="color: var(--text-dim); font-size: 12px;">
                    Perpanjangan ${bulan} Bulan x Rp ${formatRupiah(hargaPerBulan)}
                </div>
                <div style="margin-top: 8px; font-weight: 800; color: #fff;">
                    Rp ${formatRupiah(total)}
                </div>
            </div>
        `;

        // Update Summary
        document.getElementById('subtotal').innerText = "Rp " + formatRupiah(total);
        document.getElementById('total').innerText = "Rp " + formatRupiah(total);
        document.getElementById('bayarText').innerText = "Rp " + formatRupiah(bayar);
        
        let selisih = bayar - total;
        document.getElementById('kembali').innerText = "Rp " + formatRupiah(selisih < 0 ? 0 : selisih);
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Initialize
    hitungTanggal();
</script>

<?= $this->endSection(); ?>