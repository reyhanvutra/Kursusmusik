<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\KursusModel;
use App\Models\PaketModel;
use App\Models\PaketDetailModel;
use App\Models\TransaksiModel;
use App\Models\LogActivityModel;
use App\Models\SettingModel;
use App\Models\KategoriKursusModel;
use App\Models\LevelModel;
use App\Models\SiswaModel;
use App\Models\TransaksiDetailModel;
use App\Controllers\BaseController;

class Admin extends BaseController
{
    protected $userModel;
    protected $kursusModel;
    protected $transaksiModel;
    protected $logActivityModel;
    protected $kategori;
    protected $levelModel;
        protected $siswaModel;
        protected $detailModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kursusModel = new KursusModel();
        $this->transaksiModel = new TransaksiModel(); 
        $this->logActivityModel = new LogActivityModel();
        $this->kategori = new KategoriKursusModel();
        $this->levelModel = new LevelModel();
            $this->siswaModel = new SiswaModel();
            $this->detailModel = new TransaksiDetailModel();
    }
        // 🔥 HELPER LOG (BIAR RAPI)
    private function log($aktivitas)
    {
        $this->logActivityModel->insert([
            'user_id' => session()->get('id'),
            'nama_user' => session()->get('nama'),
            'role' => session()->get('role'),
            'aktivitas' => $aktivitas
        ]);
    }

  // ================= DASHBOARD =================
public function dashboard()
{
    $today = date('Y-m-d');

    $this->log('Masuk dashboard admin');

    // ================= TOTAL KURSUS =================
    $total_kursus = $this->kursusModel->countAll();

    // ================= TOTAL USER =================
    $total_user = $this->userModel->countAll();
    $users = $this->userModel->findAll();

    // ================= AMBIL ROLE DINAMIS =================
    $roles = $this->userModel
        ->select('role')
        ->distinct()
        ->findAll();

    $role_list = array_column($roles, 'role');

    // ================= TRANSAKSI HARI INI =================
    $transaksi_hari_ini = $this->transaksiModel
        ->where('tanggal', $today)
        ->countAllResults();

    // ================= TOTAL PENDAPATAN =================
    $pendapatan = $this->transaksiModel
        ->selectSum('total_harga')
        ->where('tanggal', $today)
        ->first();

    $pendapatan_hari_ini = $pendapatan['total_harga'] ?? 0;

    // ================= KATEGORI =================
    $kategori_kursus = $this->kategori->findAll(); // 🔥 FIX DISINI
    $kategori_list = implode(' | ', array_column($kategori_kursus, 'nama_kategori'));

    return view('admin/dashboard', [
        'total_kursus' => $total_kursus,
        'total_user' => $total_user,
        'transaksi_hari_ini' => $transaksi_hari_ini,
        'pendapatan_hari_ini' => $pendapatan_hari_ini,
        'kategori_list' => $kategori_list,
        'role_list' => $role_list,
        'users' => $users
    ]);
}

    // ================= USER =================
    public function user()
    {
        $this->log('Masuk halaman user');
        return view('admin/user/index', [
            'users' => $this->userModel->findAll()
        ]);
    }

    public function tambah_user()
    {
        return view('admin/user/tambah');
    }

    public function simpan_user()
    {
        if(!$this->request->getPost('nama') || !$this->request->getPost('email')){
            return redirect()->back()->with('error','Semua field wajib diisi');
        }

        $this->userModel->save([
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => md5($this->request->getPost('password')),
            'role' => $this->request->getPost('role'),
        ]);
        $this->log('Menambah user baru');

        return redirect()->to('/admin/user')
            ->with('success', 'Data berhasil disimpan');
    }

    public function edit_user($id)
    {
        return view('admin/user/edit', [
            'user' => $this->userModel->find($id)
        ]);
    }

    public function update_user($id)
    {
        $this->userModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ]);
         $this->log('Mengedit user: '.$this->request->getPost('nama'));
        return redirect()->to('/admin/user')
            ->with('success', 'Data berhasil diupdate');
    }

    public function hapus_user($id)
    {
        $this->userModel->delete($id);
        
            $this->log('Menghapus user: '.$this->request->getPost('nama'));
        return redirect()->to('/admin/user')
            ->with('success', 'Data berhasil dihapus');

    }

    // ================= KURSUS =================
    public function kursus()
    {
        $data['kursus'] = $this->kursusModel
            ->select('kursus.*, kategori_kursus.nama_kategori')
            ->join('kategori_kursus', 'kategori_kursus.id = kursus.id_kategori')
            ->findAll();

        $this->log('Masuk halaman kursus');

        return view('admin/kursus/index', $data);
    }

    // ================= TAMBAH =================
    public function tambah_kursus()
    {
        return view('admin/kursus/tambah', [
            'kategori' => $this->kategori->findAll()
        ]);
    }

   public function simpan_kursus()
{
    // ================= VALIDASI =================
    if(!$this->request->getPost('nama_kursus')){
        return redirect()->back()->with('error','Nama kursus wajib diisi');
    }

    if(!$this->request->getPost('id_kategori')){
        return redirect()->back()->with('error','Kategori wajib dipilih');
    }

    // ================= VALIDASI HARGA =================
    $harga = (int) $this->request->getPost('harga');

    if($harga <= 0){
        return redirect()->back()->with('error','Harga harus lebih dari 0');
    }

    // ================= UPLOAD GAMBAR =================
    $file = $this->request->getFile('gambar');
    $namaGambar = null;

    if($file && $file->isValid()){
        $namaGambar = $file->getRandomName();
        $file->move('uploads/', $namaGambar);
    }

    // ================= VALIDASI JAM =================
    $jam_mulai = $this->request->getPost('jam_mulai');
    $jam_selesai = $this->request->getPost('jam_selesai');

    $start = strtotime($jam_mulai);
    $end = strtotime($jam_selesai);

    if($end <= $start){
        return redirect()->back()->with('error','Jam tidak valid');
    }

    // ================= FORMAT DURASI =================
    $selisihDetik = $end - $start;

    $jam = floor($selisihDetik / 3600);
    $menit = floor(($selisihDetik % 3600) / 60);

    $durasi = '';

    if($jam > 0){
        $durasi .= $jam . ' jam ';
    }

    if($menit > 0){
        $durasi .= $menit . ' menit';
    }

    $durasi = trim($durasi); // 🔥 penting

    // ================= HARI =================
    $hari = $this->request->getPost('hari');

    if(!$hari){
        return redirect()->back()->with('error','Pilih minimal 1 hari');
    }

    // ================= SLOT =================
    $slot = (int) $this->request->getPost('slot');

    if($slot <= 0){
        return redirect()->back()->with('error','Slot harus lebih dari 0');
    }

    // ================= SIMPAN =================
    $this->kursusModel->save([
        'id_kategori' => $this->request->getPost('id_kategori'),
        'nama_kursus' => $this->request->getPost('nama_kursus'),
        'instruktur' => $this->request->getPost('instruktur'),
        'hari' => implode(',', $hari),
        'jam_mulai' => $jam_mulai,
        'jam_selesai' => $jam_selesai,
        'durasi' => $durasi, // 🔥 FIX (tidak pakai ' jam' lagi)
        'slot' => $slot,
        'deskripsi' => $this->request->getPost('deskripsi'),
        'gambar' => $namaGambar
    ]);

    $this->log('Menambah kursus: ' . $this->request->getPost('nama_kursus'));

    return redirect()->to('/admin/kursus')
        ->with('success', 'Data berhasil disimpan');
}

    // ================= EDIT =================
    public function edit_kursus($id)
    {
        return view('admin/kursus/edit', [
            'kursus' => $this->kursusModel->find($id),
            'kategori' => $this->kategori->findAll()
        ]);
    }

    public function update_kursus($id)
    {
        $kursus = $this->kursusModel->find($id);

        // ================= UPLOAD GAMBAR =================
        $file = $this->request->getFile('gambar');
        $namaGambar = $kursus['gambar'];

        if($file && $file->isValid()){
            $namaGambar = $file->getRandomName();
            $file->move('uploads/', $namaGambar);

            if($kursus['gambar']){
                @unlink('uploads/'.$kursus['gambar']);
            }
        }

        // ================= VALIDASI JAM =================
        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');

        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);

        if($end <= $start){
            return redirect()->back()->with('error','Jam tidak valid');
        }

        $durasi = ($end - $start) / 3600;

        // ================= HARI =================
        $hari = $this->request->getPost('hari');

        // ================= UPDATE =================
        $this->kursusModel->update($id, [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_kursus' => $this->request->getPost('nama_kursus'),
            'instruktur' => $this->request->getPost('instruktur'),
            'hari' => $hari ? implode(',', $hari) : null,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'durasi' => $durasi . ' jam',
            'slot' => $this->request->getPost('slot'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar' => $namaGambar
        ]);

        $this->log('Mengedit kursus: ' . $this->request->getPost('nama_kursus'));

        return redirect()->to('/admin/kursus')
            ->with('success', 'Data berhasil diupdate');
    }

    // ================= HAPUS =================
    public function hapus_kursus($id)
    {
        $kursus = $this->kursusModel->find($id);

        if($kursus && $kursus['gambar']){
            @unlink('uploads/'.$kursus['gambar']);
        }

        $this->kursusModel->delete($id);

        $this->log('Menghapus kursus: ' . $kursus['nama_kursus']);

        return redirect()->to('/admin/kursus')
            ->with('success', 'Data berhasil dihapus');
    }


    // // ================= PAKET =================
    // public function paket()
    // {
    //    $this->log('Masuk halaman paket');
    //     return redirect()->to('/admin/kursus?tab=paket');
       
    // }

    // public function tambah_paket()
    // {
    //     return view('admin/paket/tambah', [
    //         'kursus' => $this->kursusModel->findAll()
    //     ]);
    // }

    // public function simpan_paket()
    // {
    //     $kursus = $this->request->getPost('kursus');

    //     if(!$kursus){
    //         return redirect()->back()->with('error','Pilih minimal 1 kursus');
    //     }

    //     $idPaket = $this->paketModel->insert([
    //         'nama_paket' => $this->request->getPost('nama_paket'),
    //         'harga' => $this->request->getPost('harga'),
    //         'durasi' => $this->request->getPost('durasi'), 
    //         'deskripsi' => $this->request->getPost('deskripsi'),
    //     ]);

    //     foreach($kursus as $k){
    //         $this->paketDetailModel->insert([
    //             'id_paket' => $idPaket,
    //             'id_kursus' => $k
    //         ]);
    //     }
        
    //     $this->log('Menambah paket: ' . $this->request->getPost('nama_paket'));

    //     return redirect()->to('/admin/kursus?tab=paket');
    // }

    // public function edit_paket($id)
    // {
    //     return view('admin/paket/edit', [
    //         'paket' => $this->paketModel->find($id),
    //         'kursus' => $this->kursusModel->findAll(),
    //         'detail' => $this->paketDetailModel->where('id_paket', $id)->findAll()
    //     ]);
    // }

    // public function update_paket($id)
    // {
    //     $kursus = $this->request->getPost('kursus');

    //     if(!$kursus){
    //         return redirect()->back()->with('error','Pilih minimal 1 kursus');
    //     }

    //     $this->paketModel->update($id, [
    //         'nama_paket' => $this->request->getPost('nama_paket'),
    //         'harga' => $this->request->getPost('harga'),
    //         'durasi' => $this->request->getPost('durasi'), 
    //         'deskripsi' => $this->request->getPost('deskripsi'),
    //     ]);

    //     $this->paketDetailModel->where('id_paket', $id)->delete();

    //     foreach($kursus as $k){
    //         $this->paketDetailModel->insert([
    //             'id_paket' => $id,
    //             'id_kursus' => $k
    //         ]);
    //     }
    //     $this->log('Mengupdate paket: ' . $this->request->getPost('nama_paket'));

    //     return redirect()->to('/admin/kursus?tab=paket');
    // }

    // public function hapus_paket($id)
    // {
    //     $this->paketModel->delete($id);
    //     $this->paketDetailModel->where('id_paket', $id)->delete();
    //     $this->log('Menghapus paket: ' . $this->request->getPost('nama_paket'));
    //     return redirect()->to('/admin/kursus?tab=paket');
           
    // }
    
    public function settings()
    {
        $model = new SettingModel();
        $data['setting'] = $model->first();

        return view('admin/setting/index', $data);
    }


    public function updateSettings()
    {
        $model = new SettingModel();

        $model->update(1, [
            'biaya_pendaftaran' => $this->request->getPost('biaya_pendaftaran')
        ]);

        return redirect()->back()->with('success', 'Biaya berhasil diupdate');
    }

   // ================= INDEX =================
public function index()
{
    $data['kategori'] = $this->kategori->findAll();
    return view('admin/kategori/index', $data);
}

// ================= CREATE =================
public function create()
{
    return view('admin/kategori/create');
}

// ================= STORE =================
public function store()
{
    $file = $this->request->getFile('gambar');

    $namaFile = null;

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $namaFile = $file->getRandomName();
        $file->move('uploads/kategori', $namaFile);
    }

    $this->kategori->save([
        'nama_kategori' => $this->request->getPost('nama_kategori'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'gambar' => $namaFile
    ]);

    return redirect()->to('/admin/kategori')->with('success', 'Data berhasil ditambahkan');
}

// ================= EDIT =================
public function edit($id)
{
    $data['kategori'] = $this->kategori->find($id);
    return view('admin/kategori/edit', $data);
}

// ================= UPDATE =================
public function update($id)
{
    $kategori = $this->kategori->find($id);

    $file = $this->request->getFile('gambar');
    $namaFile = $kategori['gambar'];

    if ($file && $file->isValid() && !$file->hasMoved()) {

        // hapus gambar lama
        if ($kategori['gambar'] && file_exists('uploads/kategori/' . $kategori['gambar'])) {
            unlink('uploads/kategori/' . $kategori['gambar']);
        }

        $namaFile = $file->getRandomName();
        $file->move('uploads/kategori', $namaFile);
    }

    $this->kategori->update($id, [
        'nama_kategori' => $this->request->getPost('nama_kategori'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'gambar' => $namaFile
    ]);

    return redirect()->to('/admin/kategori')->with('success', 'Data berhasil diupdate');
}

// ================= DELETE =================
public function delete($id)
{
    $kategori = $this->kategori->find($id);

    if ($kategori['gambar'] && file_exists('uploads/kategori/' . $kategori['gambar'])) {
        unlink('uploads/kategori/' . $kategori['gambar']);
    }

    $this->kategori->delete($id);

    return redirect()->to('/admin/kategori')
    ->with('success', 'Data berhasil dihapus');
}

       // ================= LIST LEVEL =================
    public function indexlevel($id_kursus)
    {
        $kursus = $this->kursusModel->find($id_kursus);

        if(!$kursus){
            return redirect()->to('/admin/kursus')->with('error','Kursus tidak ditemukan');
        }

        $data = [
            'kursus' => $kursus,
            'level' => $this->levelModel
                ->where('id_kursus', $id_kursus)
                ->orderBy('urutan', 'ASC')
                ->findAll()
        ];

        return view('admin/level/index', $data);
    }

    // ================= TAMBAH =================
    public function tambah($id_kursus)
    {
        return view('admin/level/tambah', [
            'id_kursus' => $id_kursus
        ]);
    }

    // ================= SIMPAN =================
    public function simpan()
    {
        $harga = (int)$this->request->getPost('harga');
        $pertemuan = (int)$this->request->getPost('pertemuan');
        $urutan = (int)$this->request->getPost('urutan');

        if(!$this->request->getPost('nama_level')){
            return redirect()->back()->with('error','Nama level wajib diisi');
        }

        if($harga <= 0){
            return redirect()->back()->with('error','Harga tidak valid');
        }

        if($pertemuan <= 0){
            return redirect()->back()->with('error','Pertemuan tidak valid');
        }

        if($urutan <= 0){
            return redirect()->back()->with('error','Urutan tidak valid');
        }

        $this->levelModel->save([
            'id_kursus' => $this->request->getPost('id_kursus'),
            'nama_level' => $this->request->getPost('nama_level'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'urutan' => $urutan,
            'harga' => $harga,
            'pertemuan' => $pertemuan
        ]);

        return redirect()->to('/admin/level/'.$this->request->getPost('id_kursus'));
    }

    // ================= EDIT =================
    public function editlevel($id)
    {
        $level = $this->levelModel->find($id);

        return view('admin/level/edit', [
            'level' => $level
        ]);
    }

    // ================= UPDATE =================
    public function updatelevel($id)
    {
        $harga = (int)$this->request->getPost('harga');
        $pertemuan = (int)$this->request->getPost('pertemuan');
        $urutan = (int)$this->request->getPost('urutan');

        $this->levelModel->update($id, [
            'nama_level' => $this->request->getPost('nama_level'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'urutan' => $urutan,
            'harga' => $harga,
            'pertemuan' => $pertemuan
        ]);

        return redirect()->back();
    }

    // ================= HAPUS =================
    public function hapus($id)
    {
        $level = $this->levelModel->find($id);

        if($level){
            $this->levelModel->delete($id);
            return redirect()->to('/admin/level/'.$level['id_kursus']);
        }

        return redirect()->back();
    }

// ================= LIST SISWA =================
public function indexsiswa()
{
    $keyword = $this->request->getGet('search');

    // 🔍 SEARCH
    if ($keyword) {
        $siswa = $this->siswaModel
            ->like('nama', $keyword)
            ->orLike('no_hp', $keyword)
            ->findAll();
    } else {
        $siswa = $this->siswaModel->findAll();
    }

    $today = date('Y-m-d');

    foreach ($siswa as &$s) {

        // 🔥 cek apakah masih punya kursus aktif
        $aktifTransaksi = $this->detailModel
            ->join('transaksi', 'transaksi.id = transaksi_detail.id_transaksi')
            ->where('transaksi.id_siswa', $s['id'])
            ->where('transaksi_detail.tanggal_selesai >=', $today)
            ->countAllResults();

        // 🔥 LOGIKA STATUS FINAL
        if ($s['sudah_daftar'] == 0) {
            $s['status'] = 'belum';
        } else {
            $s['status'] = $aktifTransaksi > 0 ? 'aktif' : 'nonaktif';
        }
    }

    return view('admin/siswa/index', [
        'siswa' => $siswa,
        'search' => $keyword
    ]);
}

public function detail($id)
{
    $siswa = $this->siswaModel->find($id);
    if (!$siswa) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $transaksi = $this->transaksiModel
        ->where('id_siswa', $id)
        ->orderBy('id', 'DESC')
        ->findAll();

    $detailModel = new \App\Models\TransaksiDetailModel();
    $kursusModel = new \App\Models\KursusModel();
    $levelModel = new \App\Models\LevelModel();
    $kategoriModel = new \App\Models\KategoriKursusModel();

    foreach ($transaksi as &$t) {

        $details = $detailModel
            ->where('id_transaksi', $t['id'])
            ->findAll();

        foreach ($details as &$d) {

            if ($d['tipe'] === 'kursus') {

                // 🔥 id_item = id_level
                $level = $levelModel->find($d['id_item']);

                if ($level) {

                    $kursus = $kursusModel->find($level['id_kursus']);

                    $d['nama_kursus'] = $kursus['nama_kursus'] ?? '-';

                    if ($kursus) {
                        $kategori = $kategoriModel->find($kursus['id_kategori']);
                        $d['kategori'] = $kategori['nama_kategori'] ?? '-';
                    } else {
                        $d['kategori'] = '-';
                    }

                    $d['level'] = $level['nama_level'] ?? '-';

                } else {

                    $d['nama_kursus'] = 'Level dihapus';
                    $d['kategori'] = '-';
                    $d['level'] = '-';
                }

            } else {

                $d['nama_kursus'] = '-';
                $d['kategori'] = '-';
                $d['level'] = '-';
            }

            // 🔥 default aman
            $d['harga'] = $d['harga'] ?? 0;
            $d['bulan'] = $d['bulan'] ?? 1;
            $d['tanggal_mulai'] = $d['tanggal_mulai'] ?? '-';
            $d['tanggal_selesai'] = $d['tanggal_selesai'] ?? '-';
        }

        $t['detail'] = $details;
    }

    return view('admin/siswa/detail', [
        's' => $siswa,
        'transaksi' => $transaksi
    ]);
}
// ================= EDIT =================
public function editsiswa($id)
{
    $siswa = $this->siswaModel->find($id);
    if (!$siswa) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

    return view('admin/siswa/edit', ['s' => $siswa]);
}

public function updatesiswa($id)
{
    $no_hp = $this->request->getPost('no_hp');
    $siswa = $this->siswaModel->find($id);

    if ($this->siswaModel->where('no_hp', $no_hp)->where('id !=', $id)->first()) {
        return redirect()->back()->withInput()->with('error','No HP sudah terdaftar');
    }

    $this->siswaModel->update($id, [
        'nama' => $this->request->getPost('nama'),
        'no_hp' => $no_hp,
        'alamat' => $this->request->getPost('alamat'),
    ]);

    return redirect()->to('/admin/siswa')->with('success','Data berhasil diupdate');
}

// // ================= TOGGLE AKTIF/NONAKTIF =================
// public function toggle($id)
// {
//     $siswa = $this->siswaModel->find($id);
//     if (!$siswa) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

//     $this->siswaModel->update($id, [
//         'aktif' => $siswa['aktif'] ? 0 : 1
//     ]);

//     return redirect()->to('/admin/siswa')->with('success','Status berhasil diubah');
// }
}