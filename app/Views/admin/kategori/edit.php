<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/kategori" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h2 class="page-header">Edit Kategori</h2>
        <p style="color: #555; font-size: 14px; margin: 0;">Perbarui informasi atau gambar untuk kategori <strong><?= esc($kategori['nama_kategori']) ?></strong>.</p>
    </div>

    <div class="form-container" style="max-width: 650px;">
        <form action="/admin/kategori/update/<?= $kategori['id'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" 
                       value="<?= esc($kategori['nama_kategori']) ?>" 
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4"><?= esc($kategori['deskripsi']) ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px;">
                <div class="form-group">
                    <label>Gambar Saat Ini</label>
                    <div class="current-img-box">
                        <?php if($kategori['gambar']): ?>
                            <img src="<?= base_url('uploads/kategori/'.$kategori['gambar']) ?>" alt="Current">
                        <?php else: ?>
                            <div class="no-image-placeholder">
                                <i class="fa-solid fa-image-slash"></i>
                                <p>No Image</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ganti Gambar Baru</label>
                    <div class="upload-area">
                        <input type="file" name="gambar" id="imgInput" class="form-control-file" accept="image/*">
                        <div class="preview-box" id="previewBox">
                            <img id="imgPreview" src="#" alt="Preview" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-save" style="margin-top: 30px;">
                <i class="fa-solid fa-arrows-rotate"></i> Update
            </button>
        </form>
    </div>
</div>

<script>
    const imgInput = document.getElementById('imgInput');
    const imgPreview = document.getElementById('imgPreview');
    const previewBox = document.getElementById('previewBox');

    imgInput.onchange = evt => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.style.display = 'block';
            previewBox.querySelector('i').style.display = 'none';
            previewBox.querySelector('p').style.display = 'none';
            previewBox.style.borderColor = '#00ff64'; // Beri warna hijau sebagai tanda file siap
        }
    }
</script>
<?= $this->endSection(); ?>