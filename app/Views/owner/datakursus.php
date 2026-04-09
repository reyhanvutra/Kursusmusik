<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/owner/datakursus.css'); ?>">

<div class="report-container">
    <div class="header-action">
        <div class="header-title-group">
            <h2 class="page-title">Data Kursus</h2>
            <p class="page-subtitle">Pantau performa setiap kursus dan total pendapatan yang dihasilkan.</p>
        </div>
    </div>

    <div class="card card-filter">
        <form method="get" action="/owner/data-kursus" class="filter-flex">
            <div class="input-group">
                <label>Cari Nama Kursus</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-music"></i>
                    <input type="text" name="nama" placeholder="Contoh: Gitar Akustik..." value="<?= $_GET['nama'] ?? '' ?>">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-filter">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                <a href="/owner/data-kursus" class="btn btn-clear">
                    <i class="fa-solid fa-rotate-left"></i> Clear
                </a>
                
                <?php $nama = $_GET['nama'] ?? ''; ?>
                <a href="/owner/data-kursus/pdf?nama=<?= $nama ?>" target="_blank" class="btn btn-pdf">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    <div class="card card-table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Nama Kursus</th>
                    <th>Kategori</th>
                    <th>Level</th>
                    <th>Total Terjual</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $page = $_GET['page'] ?? 1;
                $perPage = 10;
                $no = 1 + ($perPage * ($page - 1));
                ?>
                <?php if(!empty($data)): ?>
                    <?php foreach($data as $d): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><div class="course-name"><?= $d['nama_kursus']; ?></div></td>
                        <td><span class="badge-category"><?= $d['nama_kategori']; ?></span></td>
                        <td><?= $d['nama_level']; ?></td>
                        <td>
                            <span class="sold-count"><?= $d['total_terjual']; ?> Sesi</span>
                        </td>
                        <td class="text-white font-bold">
                            Rp <?= number_format($d['total_pendapatan'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">Data kursus tidak ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        <?= $pager->links(); ?>
    </div>
</div>

<?= $this->endSection(); ?>