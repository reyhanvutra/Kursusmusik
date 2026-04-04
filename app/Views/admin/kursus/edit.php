<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper" style="overflow-y: auto !important; height: 100vh !important; display: block !important;">   
    
    <div class="form-header-area">
        <a href="/admin/kursus" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Kursus
        </a>
        <h2 class="page-header">Edit Kursus: <?= $kursus['nama_kursus']; ?></h2>
    </div>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i> <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form action="/admin/kursus/update/<?= $kursus['id']; ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label>Kategori:</label>
                        <select name="id_kategori" class="form-control" required>
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k['id']; ?>" <?= $k['id'] == $kursus['id_kategori'] ? 'selected' : '' ?>>
                                    <?= $k['nama_kategori']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Kursus:</label>
                        <input type="text" name="nama_kursus" class="form-control" value="<?= $kursus['nama_kursus']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Instruktur:</label>
                        <input type="text" name="instruktur" class="form-control" value="<?= $kursus['instruktur']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Pilih Hari:</label>
                        <div class="checkbox-group">
                            <?php 
                            $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                            $hariDipilih = $kursus['hari'] ? explode(',', $kursus['hari']) : [];
                            foreach($hariList as $h): 
                            ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="hari[]" value="<?= $h; ?>" <?= in_array($h, $hariDipilih) ? 'checked' : '' ?>>
                                <span><?= $h; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-row-flex">
                        <div class="form-group flex-1">
                            <label>Jam Mulai:</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="<?= $kursus['jam_mulai']; ?>" required>
                        </div>
                        <div class="form-group flex-1">
                            <label>Jam Selesai:</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="<?= $kursus['jam_selesai']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Durasi Estimasi:</label>
                        <input type="text" id="durasi" class="form-control" value="<?= $kursus['durasi']; ?>" readonly style="background: rgba(255,255,255,0.02);">
                    </div>

                    <div class="form-group">
                        <label>Slot Kuota:</label>
                        <input type="number" name="slot" class="form-control" value="<?= $kursus['slot']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Gambar Kursus (Kosongkan jika tidak ganti):</label>
                        <?php if($kursus['gambar']): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="/uploads/<?= $kursus['gambar']; ?>" style="width: 80px; border-radius: 8px; border: 1px solid #333;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= $kursus['deskripsi']; ?></textarea>
            </div>

            <button type="submit" class="btn-save">Update Kursus</button>
        </form>
    </div>
</div>

<script>
    const mulai = document.getElementById('jam_mulai');
    const selesai = document.getElementById('jam_selesai');
    const durasi = document.getElementById('durasi');

    function hitungDurasi(){
        if(mulai.value && selesai.value){
            let start = new Date('1970-01-01T' + mulai.value);
            let end = new Date('1970-01-01T' + selesai.value);
            let selisih = (end - start) / 3600000;
            durasi.value = selisih > 0 ? selisih.toFixed(1) + ' jam' : 'Jam tidak valid';
        }
    }
    mulai.addEventListener('change', hitungDurasi);
    selesai.addEventListener('change', hitungDurasi);
</script>

<?= $this->endSection(); ?>