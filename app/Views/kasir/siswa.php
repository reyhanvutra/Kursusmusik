<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>📚 Data Siswa</h2>

    <div class="card card-dark">

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Nama</th>
                <th>No HP</th>
                <th>Total Transaksi</th>
                <th>Aksi</th>
            </tr>

            <?php foreach($siswa as $s): ?>
            <tr>
                <td><?= $s['nama']; ?></td>
                <td><?= $s['no_hp']; ?></td>
                <td><?= $s['total_transaksi']; ?></td>
                <td>
                    <a href="/kasir/siswa/detail/<?= $s['id']; ?>" 
                       style="background:#444;color:white;padding:5px 10px;border-radius:6px;text-decoration:none;">
                       Detail
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</div>

<?= $this->endSection(); ?>