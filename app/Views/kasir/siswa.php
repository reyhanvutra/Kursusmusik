<?= $this->extend('kasir/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/kasir/data_siswa.css'); ?>">
 
<div class="container">
<a href="/kasir/dashboard" class="btn-back">
            <i class="fa-solid fa-arrow-left-long"></i> Kembali
        </a>
    <!-- HEADER -->
    <div class="header-section">
        <h2 class="judul-halaman">
        Data Siswa
        </h2>
        
    </div>

    <!-- 🔍 FILTER -->
    <div style="margin-bottom:20px;">
        <form method="get" class="filter-wrapper">
    
    <input type="text" name="nama" class="filter-input" 
           placeholder="Cari nama siswa..." 
           value="<?= $filter['nama'] ?? ''; ?>" style="flex: 1; min-width: 250px;">

    <select name="kursus" class="filter-input">
        <option value="">Semua Kursus</option>
        <?php foreach($listKursus as $k): ?>
            <option value="<?= $k['id']; ?>" <?= ($filter['kursus'] == $k['id']) ? 'selected' : ''; ?>>
                <?= $k['nama_kursus']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="status" class="filter-input">
        <option value="">Semua Status</option>
        <option value="aktif" <?= ($filter['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
        <option value="nonaktif" <?= ($filter['status'] == 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
    </select>

    <button type="submit" class="btn-filter">
        <i class="fa-solid fa-magnifying-glass"></i> Filter
    </button>

    <a href="/kasir/siswa" class="btn-reset">
        Reset
    </a>

</form>
    </div>

    <!-- TABLE -->
    <div class="card-table">
        <table class="siswa-table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>No. WhatsApp</th>
                    <th>Kursus</th> <!-- 🔥 TAMBAHAN -->
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>

            <tbody>
            <?php if(!empty($siswa)): ?>
                <?php foreach($siswa as $s): ?>
                    <?php 
                        $is_aktif = ($s['kursus_aktif'] > 0);
                        $status_text = $is_aktif ? 'Aktif' : 'Nonaktif';
                        $status_class = $is_aktif ? 'status-aktif' : 'status-nonaktif';
                    ?>
                    <tr>
                        <!-- NAMA -->
                        <td>
                            <div style="font-weight: 800; color: #fff;">
                                <?= $s['nama']; ?>
                            </div>
                            <small style="color: #555;">
                                ID: #SIS-<?= str_pad($s['id'], 4, '0', STR_PAD_LEFT); ?>
                            </small>
                        </td>

                        <!-- HP -->
                        <td>
                            <span style="color: #888;">
                                <i class="fa-brands fa-whatsapp"></i>
                                <?= $s['no_hp']; ?>
                            </span>
                        </td>

                        <!-- 🔥 KURSUS -->
                        <td>
    <span style="color:#fff;font-weight:600;">
        <?php if(!empty($s['nama_level'])): ?>
            <?= $s['nama_kursus']; ?> - <?= $s['nama_level']; ?>
        <?php else: ?>
            <?= $s['nama_kursus'] ?? '—'; ?>
        <?php endif; ?>
    </span>
</td>

                        <!-- TOTAL -->
                        <td>
                            <div style="color: #fff; font-weight: 700;">
                                <?= $s['total_transaksi']; ?>
                                <small style="color:#444">Invoice</small>
                            </div>
                        </td>

                        <!-- STATUS -->
                        <td>
                            <span class="status-badge <?= $status_class; ?>">
                                <?= $status_text; ?>
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td>
                            <a href="/kasir/siswa/detail/<?= $s['id']; ?>" class="btn-detail">
                                <i class="fa-solid fa-circle-info"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:20px;color:#888;">
                        Tidak ada data ditemukan 
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    

</div>

<?= $this->endSection(); ?>