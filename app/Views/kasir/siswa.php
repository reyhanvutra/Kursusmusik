<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<div class="container">

    <h2 style="margin-bottom:20px;">📚 Data Siswa</h2>

    <div class="card card-dark" style="padding:20px;border-radius:12px;">

        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
            <thead style="background:#222;color:white;">
                <tr>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach($siswa as $s): ?>

                <?php 
                    $status = ($s['kursus_aktif'] > 0) ? 'Aktif' : 'Nonaktif';
                    $warna = ($status == 'Aktif') ? '#28a745' : '#dc3545';
                ?>

                <tr style="border-bottom:1px solid #ccc;">
                    <td><?= $s['nama']; ?></td>
                    <td><?= $s['no_hp']; ?></td>
                    <td><?= $s['total_transaksi']; ?></td>

                    <td>
                        <span style="
                            background:<?= $warna ?>;
                            color:white;
                            padding:5px 10px;
                            border-radius:20px;
                            font-size:12px;">
                            <?= $status; ?>
                        </span>
                    </td>

                    <td>
                        <a href="/kasir/siswa/detail/<?= $s['id']; ?>" 
                           style="background:#007bff;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;">
                           Detail
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>

        </table>

    </div>

</div>
<div style="margin-top:20px;">
    <a href="/kasir/dashboard"
       style="background:#555;color:white;padding:10px 15px;border-radius:8px;text-decoration:none;">
       ← Kembali ke Dashboard
    </a>
</div>
<?= $this->endSection(); ?>