<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/admin_tambah.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/level/index/<?= $level['id_kursus']; ?>" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Level
        </a>
        <h2 class="page-header">Edit Level: <?= esc($level['nama_level']); ?></h2>
    </div>

    <div class="form-container">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert error" style="margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/admin/level/update/<?= $level['id']; ?>">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_kursus" value="<?= $level['id_kursus']; ?>">

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Nama Level</label>
                    <input type="text" name="nama_level" class="form-control" value="<?= esc($level['nama_level']); ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Urutan Ke-</label>
                    <input type="number" name="urutan" class="form-control" value="<?= $level['urutan']; ?>" required>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Pilih Mentor</label>
                    <select name="id_mentor" class="form-control" required>
                        <?php foreach($mentor as $m): ?>
                            <option value="<?= $m['id']; ?>" <?= $m['id'] == $level['id_mentor'] ? 'selected' : '' ?>>
                                <?= $m['nama']; ?> (<?= $m['keahlian']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="<?= $level['harga']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Pilih Hari Pertemuan</label>
                <div style="display: flex; flex-wrap: wrap; gap: 10px; background: #252525; padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                    <?php foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
                    <label style="color: #ccc; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="hari[]" value="<?= $h ?>" 
                        <?= in_array($h, $level['hari_array'] ?? []) ? 'checked' : '' ?>> <?= $h ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="mulai" class="form-control" value="<?= $level['jam_mulai']; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="selesai" class="form-control" value="<?= $level['jam_selesai']; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Durasi</label>
                    <input type="text" id="durasi" class="form-control" style="background: #111; color: #00ff64; font-weight: 700;" readonly>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jumlah Pertemuan</label>
                    <input type="number" name="pertemuan" class="form-control" value="<?= $level['pertemuan']; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Kapasitas Slot</label>
                    <input type="number" name="slot" class="form-control" value="<?= $level['slot']; ?>" required>
                </div>
            </div>

            <button type="submit" class="btn-save" style="background: #ffffff; color: #000;">
                <i class="fa-solid fa-rotate"></i> Update Data Level
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
                dInput.value = 'Tidak Valid';
                dInput.style.color = '#ff3232';
            } else {
                let diffMs = (end - start);
                let diffHrs = Math.floor(diffMs / 3600000);
                let diffMins = Math.round(((diffMs % 3600000) / 60000));
                
                let hasil = "";
                if(diffHrs > 0) hasil += diffHrs + " Jam ";
                if(diffMins > 0) hasil += diffMins + " Menit";
                
                dInput.value = hasil || "0 Menit";
                dInput.style.color = '#00ff64';
            }
        }
    }

    // Jalankan hitung saat halaman dimuat pertama kali
    window.onload = hitung;
    mInput.onchange = hitung;
    sInput.onchange = hitung;
</script>

<?= $this->endSection(); ?>