<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper" style="overflow-y: auto !important; height: 100vh !important; display: block !important;">
    
    <div class="form-header-area">
        <a href="/admin/kursus" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Kursus
        </a>
        <h2 class="page-header">Tambah Kursus Baru</h2>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="form-container" style="max-width: 800px;"> <form action="/admin/kursus/simpan" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label>Kategori:</label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Kursus:</label>
                        <input type="text" name="nama_kursus" class="form-control" placeholder="Contoh: Piano Klasik" required>
                    </div>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan detail kursus..."></textarea>
            </div>

            <button type="submit" class="btn-save">Simpan Kursus</button>
        </form>
    </div>
</div>



<?= $this->endSection(); ?>