<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_user.css'); ?>">

<div class="user-header-flex">
    <h2 class="page-header" style="margin: 0; color: white;">Data Siswa</h2>
    
    <form method="get" action="" style="display: flex; gap: 10px;">
        <input type="text" name="search" class="form-control" 
               placeholder="Cari nama / no HP..." 
               value="<?= esc($search ?? '') ?>" 
               style="width: 250px; background: #1a1a1a;">
        <button type="submit" class="btn-tambah" style="padding: 10px 20px;">Cari</button>
    </form>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>No. HP / WhatsApp</th>
                <th style="text-align: center;">Status Keaktifan</th>
                <th width="150" style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($siswa)): ?>
                <tr><td colspan="4" style="text-align:center; color: #555;">Tidak ada data siswa</td></tr>
            <?php endif; ?>

            <?php foreach($siswa as $s): ?>
            <tr>
                <td><strong style="color: #fff;"><?= esc($s['nama']); ?></strong></td>
                <td style="color: #888;"><?= esc($s['no_hp']); ?></td>
                <td style="text-align: center;">
                    <?php if($s['status'] == 'belum'): ?>
                        <span class="badge-role" style="background: rgba(255, 165, 0, 0.15); color: orange;">Belum Daftar</span>
                    <?php elseif($s['status'] == 'aktif'): ?>
                        <span class="badge-role" style="background: rgba(0, 255, 0, 0.15); color: #00ff00;">Aktif</span>
                    <?php else: ?>
                        <span class="badge-role" style="background: rgba(255, 0, 0, 0.15); color: #ff4d4d;">Nonaktif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-group" style="justify-content: center;">
                        <a href="/admin/siswa/detail/<?= $s['id']; ?>" class="action-link edit" title="Lihat Detail">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="/admin/siswa/edit/<?= $s['id']; ?>" class="action-link edit" title="Edit Data">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection(); ?>