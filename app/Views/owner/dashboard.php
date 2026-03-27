<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>Dashboard Owner</h2>

    <!-- CARD -->
    <div style="display:flex; gap:15px; margin:20px 0;">

        <div class="card" style="flex:1; background:#2b2b2b; padding:20px;">
            <h4>Pendapatan Bulan Ini</h4>
            <h2>Rp <?= number_format($pendapatan,0,',','.'); ?></h2>

            <a href="/owner/laporan" style="color:yellow;">Lihat Detail</a>
        </div>

        <div class="card" style="flex:1; background:#444; padding:20px;">
            <h4>Total Transaksi</h4>
            <h2><?= $total_transaksi; ?></h2>
        </div>

        <div class="card" style="flex:1; background:#2b2b2b; padding:20px;">
            <h4>Siswa Aktif</h4>
            <h2><?= $siswa_aktif; ?></h2>
        </div>

        <div class="card" style="flex:1; background:#444; padding:20px;">
            <h4>Kursus Aktif</h4>
            <h2><?= $kursus_aktif; ?></h2>
        </div>

    </div>

    <!-- GRAFIK -->
    <div class="card" style="background:#2b2b2b; padding:20px;">
        <h4>Grafik Pendapatan</h4>

        <canvas id="chart"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let dataGrafik = <?= json_encode($grafik); ?>;

let bulan = [];
let total = [];

dataGrafik.forEach(d => {
    bulan.push("Bulan " + d.bulan);
    total.push(d.total);
});

new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: {
        labels: bulan,
        datasets: [{
            label: 'Pendapatan',
            data: total
        }]
    }
});
</script>

<?= $this->endSection(); ?>