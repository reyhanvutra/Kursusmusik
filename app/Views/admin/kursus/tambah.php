<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/kursus" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h2 class="page-header">Tambah Kursus Baru</h2>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert error">
            <i class="fa-solid fa-circle-exclamation"></i> <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form action="/admin/kursus/simpan" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Kursus</label>
                        <input type="text" name="nama_kursus" class="form-control" placeholder="Contoh: Piano Klasik" required>
                    </div>
                </div>

                <div class="form-column">
                     <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan detail kursus..."></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-save">
                <i class="fa-solid fa-plus"></i> Simpan Kursus
            </button>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>