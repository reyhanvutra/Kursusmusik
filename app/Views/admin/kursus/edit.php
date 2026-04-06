<?= $this->extend('admin/layout'); ?>
<?= $this->section('content'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin/tambah_kursus.css'); ?>">

<div class="form-page-wrapper">
    <div class="form-header-area">
        <a href="/admin/level/index/<?= $id_kursus; ?>" class="btn-back">
            ← Kembali
        </a>
        <h2 class="page-header">Tambah Level</h2>
    </div>

    <div class="form-container" style="max-width: 700px;">
        <form action="/admin/level/simpan" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">

            <!-- NAMA -->
            <div class="form-group">
                <label>Nama Level</label>
                <input type="text" name="nama_level" class="form-control" required>
            </div>

            <!-- GRID -->
            <div class="form-grid">

                <div class="form-column">
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="urutan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Pertemuan</label>
                        <input type="number" name="pertemuan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Instruktur</label>
                        <input type="text" name="instruktur" class="form-control">
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label>Hari</label>
                        <input type="text" name="hari" class="form-control" placeholder="Contoh: Senin, Rabu">
                    </div>

                    <div class="form-row-flex">
                        <div class="form-group flex-1">
                            <label>Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control">
                        </div>
                        <div class="form-group flex-1">
                            <label>Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Slot</label>
                        <input type="number" name="slot" class="form-control">
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn-save">Update</button>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>