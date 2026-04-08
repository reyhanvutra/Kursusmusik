<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/level/index/<?= $id_kursus; ?>" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Level
        </a>
        <h2 class="page-header">Tambah Level Baru</h2>
    </div>

    <div class="form-container">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/admin/level/simpan">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Nama Level</label>
                    <input type="text" name="nama_level" class="form-control" placeholder="Contoh: Beginner" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Urutan Ke-</label>
                    <input type="number" name="urutan" class="form-control" placeholder="1" required>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Pilih Mentor</label>
                    <select name="id_mentor" class="form-control" required>
                        <option value="">-- Pilih Mentor --</option>
                        <?php foreach($mentor as $m): ?>
                            <option value="<?= $m['id']; ?>">
                                <?= $m['nama']; ?> (<?= $m['keahlian']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" placeholder="300000" required>
                </div>
            </div>

            <div class="form-group">
                <label>Pilih Hari Pertemuan</label>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; background: #252525; padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                    <?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
                    <label style="color: #ccc; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="hari[]" value="<?= $h ?>"> <?= $h ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="mulai" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="selesai" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Durasi</label>
                    <input type="text" id="durasi" class="form-control" style="background: #111; color: #00ff64; font-weight: 700;" readonly placeholder="-">
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jumlah Pertemuan</label>
                    <input type="number" name="pertemuan" class="form-control" placeholder="8" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Kapasitas Slot</label>
                    <input type="number" name="slot" class="form-control" placeholder="10" required>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Level</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Apa saja yang dipelajari di level ini?"></textarea>
            </div>

            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Level Baru
            </button>
        </form>
    </div>
</div>

<script>
    const mInput = document.getElementById('mulai');
    const sInput = document.getElementById('selesai');
    const dInput = document.getElementById('durasi');

    function hitung() {
        if (mInput.value && sInput.value) {
            let start = new Date('1970-01-01T' + mInput.value);
            let end = new Date('1970-01-01T' + sInput.value);
            
            if (end < start) {
                dInput.value = 'Jam Tidak Valid';
                dInput.style.color = '#ff3232';
            } else {
                let diffMs = (end - start);
                let diffHrs = Math.floor(diffMs / 3600000);
                let diffMins = Math.round(((diffMs % 3600000) / 60000));
                
                let hasil = "";
                if(diffHrs > 0) hasil += diffHrs + " Jam ";
                if(diffMins > 0) hasil += diffMins + " Menit";
                
                dInput.value = hasil;
                dInput.style.color = '#00ff64';
            }
        }
    }

    mInput.onchange = hitung;
    sInput.onchange = hitung;
</script>

<?= $this->endSection(); ?>