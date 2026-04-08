<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="user-header-flex">
    <h2 class="page-header" style="margin: 0; color: white;">Data Siswa</h2>
    
   <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;">

    <!-- SEARCH -->
    <input type="text" name="search"
        placeholder="Cari nama / no HP..."
        value="<?= esc($search ?? '') ?>"
        style="padding:10px;border-radius:10px;min-width:200px;">

    <!-- FILTER KURSUS -->
    <select name="kursus" style="padding:10px;border-radius:10px;">
        <option value="">Semua Kursus</option>
        <?php foreach($listKursus as $k): ?>
            <option value="<?= $k['id']; ?>"
                <?= ($filter['kursus'] == $k['id']) ? 'selected' : ''; ?>>
                <?= $k['nama_kursus']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- FILTER STATUS -->
    <select name="status" style="padding:10px;border-radius:10px;">
        <option value="">Semua Status</option>
        <option value="aktif" <?= ($filter['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
        <option value="nonaktif" <?= ($filter['status'] == 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
    </select>

    <button type="submit" class="btn-tambah">
        Cari
    </button>

    <a href="/admin/siswa" class="btn-tambah" style="background:#777;">
        Reset
    </a>
</form>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>No. HP</th>
                <th>Kursus</th>
                <th style="text-align: center;">Status</th>
                <th width="150" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($siswa)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; color: #555; padding: 40px;">
                        <i class="fa-solid fa-box-open" style="display: block; font-size: 24px; margin-bottom: 10px;"></i>
                        Tidak ada data siswa ditemukan
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($siswa as $s): ?>
                <tr>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <strong style="color: #fff; font-size: 15px;"><?= esc($s['nama']); ?></strong>
                            <span style="font-size: 11px; color: #555; text-transform: uppercase;">Siswa</span>
                        </div>
                    </td>
                    <td style="color: #ccc; font-family: monospace;"><?= esc($s['no_hp']); ?></td>
                    <td>
    <span style="color:#fff;font-weight:600;">
        <?= !empty($s['nama_kursus']) ? $s['nama_kursus'] : '—'; ?>
    </span>
</td>
                   <td style="text-align: center;">
    <?php if($s['status'] == 'aktif'): ?>
        <span class="badge-role" style="background: rgba(0, 255, 100, 0.1); color: #00ff64; border: 1px solid rgba(0, 255, 100, 0.2);">
            Aktif
        </span>
    <?php else: ?>
        <span class="badge-role" style="background: rgba(255, 50, 50, 0.1); color: #ff3232; border: 1px solid rgba(255, 50, 50, 0.2);">
            Nonaktif
        </span>
    <?php endif; ?>
</td>
                    <td>
                        <div class="action-group" style="justify-content: center;">
                            <a href="/admin/siswa/detail/<?= $s['id']; ?>" class="action-link" title="Lihat Detail" style="color: #00bfff;">
                                <i class="fa-solid fa-circle-info
                                "></i>
                            </a>
                            <a href="/admin/siswa/edit/<?= $s['id']; ?>" class="action-link edit" title="Edit Data">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>