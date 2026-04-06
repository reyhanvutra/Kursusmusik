<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table, th, td { border: 1px solid #555; }
        th, td { padding: 6px; text-align: left; }
        thead { background: #ddd; }
        .badge { display:inline-block; padding:2px 5px; border-radius:3px; font-size:11px; color:#fff;}
        .baru { background-color: orange; }
        .lama { background-color: green; }
        .aktif { background-color: green; }
        .nonaktif { background-color: red; }
    </style>
</head>
<body>
<h2>Daftar Siswa</h2>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Total Transaksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($siswa as $s): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $s['nama']; ?></td>
            <td><?= $s['no_hp']; ?></td>
            <td><?= $s['alamat']; ?></td>
            <td>
                <?php if($s['aktif_count']>0): ?>
                    <span class="badge aktif">Aktif</span>
                <?php else: ?>
                    <span class="badge nonaktif">Nonaktif</span>
                <?php endif; ?>
            </td>
            <td><?= $s['total_transaksi'] ?? 0; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>