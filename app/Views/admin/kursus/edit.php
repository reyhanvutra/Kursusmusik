<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/kursus" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Kursus
        </a>
        <h2 class="page-header">Edit Kursus: <?= esc($kursus['nama_kursus']); ?></h2>
    </div>

    <div class="form-container" style="max-width: 600px;">
        <form action="/admin/kursus/update/<?= $kursus['id']; ?>" method="post">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Kategori Kursus</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategori as $k): ?>
                        <option value="<?= $k['id']; ?>" <?= ($k['id'] == $kursus['id_kategori']) ? 'selected' : ''; ?>>
                            <?= $k['nama_kategori']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Kursus</label>
                <input type="text" name="nama_kursus" 
                       value="<?= esc($kursus['nama_kursus']); ?>" 
                       class="form-control" placeholder="Contoh: Piano Klasik" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5" 
                          placeholder="Jelaskan detail kursus..."><?= esc($kursus['deskripsi']); ?></textarea>
            </div>

            <button type="submit" class="btn-save">
    Update 
            </button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>