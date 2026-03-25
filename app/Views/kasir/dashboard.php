<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>Welcome <b>Kasir Dashboard</b></h2>

    <div style="margin:20px 0;">
        <a href="/kasir/pilih" class="btn btn-red">
            + Mulai Transaksi →
        </a>
    </div>

    <!-- CARD -->
    <div style="display:flex; gap:15px; margin-bottom:20px;">

        <div class="card card-dark" style="flex:1;">
            <h4>Pendapatan Hari ini</h4>
            <h2>Rp <?= number_format($pendapatan_hari_ini,0,',','.'); ?></h2>
        </div>

        <div class="card card-light" style="flex:1;">
            <h4>Kursus Aktif</h4>
            <h2><?= $kursus_aktif; ?></h2>
        </div>

        <div class="card card-dark" style="flex:1;">
            <h4>Total Transaksi Hari Ini</h4>
            <h2><?= $total_transaksi_hari_ini; ?></h2>
        </div>

    </div>

    <!-- RIWAYAT -->
    <div class="card card-dark">

        <h4>Riwayat Transaksi</h4>

        <table>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Item</th>
                <th>Bayar</th>
            </tr>

            <?php foreach($transaksi as $t): ?>
            <tr>
                <td><?= $t['tanggal']; ?></td>
                <td><?= $t['nama_pembeli']; ?></td>

                <td>
                    <?php 
                    if(!empty($t['items'])){
                        foreach($t['items'] as $item){
                            echo '<span class="badge">'.$item.'</span>';
                        }
                    }
                    ?>
                </td>

                <td style="color:#ff4d4d;">
                    Rp <?= number_format($t['total_harga'],0,',','.'); ?>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</div>

<?= $this->endSection(); ?>