<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2>👤 Detail Siswa</h2>
    <h3><?= $siswa['nama']; ?></h3>

    <!-- ================= KURSUS ================= -->
    <div class="card card-dark" style="margin-bottom:20px;">
        <h4>🎓 Kursus</h4>

        <?php if(empty($kursus)): ?>
            <p>Tidak ada kursus</p>
        <?php else: ?>
            <table border="1" cellpadding="10" width="100%">
                <tr>
                    <th>Nama</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Sisa</th>
                    <th>Aksi</th>
                </tr>

                <?php foreach($kursus as $k): ?>
                <tr>
                    <td><?= $k['nama']; ?></td>
                    <td><?= $k['mulai']; ?></td>
                    <td><?= $k['selesai']; ?></td>

                    <td>
                        <?php if($k['sisa_hari'] <= 3): ?>
                            <span style="color:red;">
                                <?= $k['sisa_hari']; ?> hari
                            </span>
                        <?php else: ?>
                            <span style="color:green;">
                                <?= $k['sisa_hari']; ?> hari
                            </span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <a href="/kasir/perpanjang/<?= $k['id_detail']; ?>" 
                           style="background:orange;color:white;padding:5px 10px;border-radius:6px;text-decoration:none;">
                           Perpanjang
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

            </table>
        <?php endif; ?>
    </div>

    <!-- ================= PAKET ================= -->
    <div class="card card-dark">
        <h4>📦 Paket</h4>

        <?php if(empty($paket)): ?>
            <p>Tidak ada paket</p>
        <?php else: ?>
            <table border="1" cellpadding="10" width="100%">
                <tr>
                    <th>Nama Paket</th>
                    <th>Tanggal Ambil</th>
                    <th>Aksi</th>
                </tr>

                <?php foreach($paket as $p): ?>
                <tr>
                    <td><?= $p['nama']; ?></td>
                    <td><?= $p['tanggal']; ?></td>
                    <td>
                        <a href="/kasir/pilih?paket=<?= $p['id_item']; ?>" 
                           style="background:green;color:white;padding:5px 10px;border-radius:6px;text-decoration:none;">
                           Beli Lagi
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

            </table>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection(); ?>