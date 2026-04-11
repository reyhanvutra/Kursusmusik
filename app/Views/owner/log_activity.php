<?= $this->extend('owner/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/owner/logactivity.css'); ?>">

<div class="report-container">
    <div class="header-action">
        <div class="header-title-group">
            <h2 class="page-title">Log Aktivitas</h2>
            <p class="page-subtitle">Pantau seluruh jejak digital aktivitas Admin dan Kasir secara real-time.</p>
        </div>
    </div>

    <div class="card card-filter">
        <form method="get" action="/owner/log-activity" class="filter-grid">
            <div class="input-group">
                <label>Cari Pengguna</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-user-tag"></i>
                    <input type="text" name="nama" placeholder="Nama user..." value="<?= $_GET['nama'] ?? '' ?>">
                </div>
            </div>

            <div class="input-group">
                <label>Filter Role</label>
                <select name="role">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" <?= (($_GET['role'] ?? '')=='admin')?'selected':'' ?>>Admin</option>
                    <option value="kasir" <?= (($_GET['role'] ?? '')=='kasir')?'selected':'' ?>>Kasir</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="/owner/log-activity" class="btn btn-clear">Clear</a>
            </div>
        </form>
    </div>

    <div class="card card-table-wrapper">
        <table class="modern-table">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>User</th>
                    <th>Peran</th>
                    <th>Aktivitas</th>
                    <th class="text-right">Waktu Kejadian</th>
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
                        <td><div class="user-info-name"><?= $d['nama_user']; ?></div></td>
                        <td>
                            <span class="role-badge <?= strtolower($d['role']); ?>">
                                <?= ucfirst($d['role']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="activity-desc">
                                <i class="fa-solid fa-circle-dot activity-dot"></i>
                                <?= $d['aktivitas']; ?>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="timestamp">
                                <span class="date"><?= date('d M Y', strtotime($d['created_at'])); ?></span>
                                <span class="time"><?= date('H:i', strtotime($d['created_at'])); ?> WIB</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">Belum ada catatan aktivitas saat ini</td>
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