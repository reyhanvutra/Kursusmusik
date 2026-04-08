<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/kategori" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali 
        </a>
        <h2 class="page-header">Tambah Kategori Baru</h2>
        <p style="color: #555; font-size: 14px; margin: 0;">Isi detail kategori kursus musik di bawah ini.</p>
    </div>

    <div class="form-container" style="max-width: 600px;"> <form action="/admin/kategori/store" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Piano Klasik" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan secara singkat tentang kategori ini..."></textarea>
            </div>

            <div class="form-group">
                <label>Gambar Kategori</label>
                <div class="upload-area">
                    <input type="file" name="gambar" id="imgInput" class="form-control-file" accept="image/*">
                    <div class="preview-box" id="previewBox">
                        <img id="imgPreview" src="#" alt="Preview" style="display:none;">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-save">
             Simpan 
            </button>
        </form>
    </div>
</div>

<script>
    // Script sederhana untuk preview gambar
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
        }
    }
</script>
<?= $this->endSection(); ?>