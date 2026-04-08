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
use App\Models\MentorModel;
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
        protected $mentorModel;

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
            $this->mentorModel = new MentorModel();
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
            // 'role' => $this->request->getPost('role'),
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

    // ================= SIMPAN =================
    $this->kursusModel->save([
        'id_kategori' => $this->request->getPost('id_kategori'),
        'nama_kursus' => $this->request->getPost('nama_kursus'),
        'deskripsi' => $this->request->getPost('deskripsi')
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

    // ================= UPDATE =================
    $this->kursusModel->update($id, [
        'id_kategori' => $this->request->getPost('id_kategori'),
        'nama_kursus' => $this->request->getPost('nama_kursus'),
        'deskripsi' => $this->request->getPost('deskripsi')
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

 // ================= LIST =================
    public function indexmentor()
    {
        $this->log('Masuk halaman mentor');
        return view('admin/mentor/index', [
            'mentor' => $this->mentorModel->findAll()
        ]);
    }

    // ================= TAMBAH =================
    public function tambahmentor()
    {
        $this->log('Masuk halaman tambah mentor');
        return view('admin/mentor/tambah');
    }

    public function simpanmentor()
    {
        $nama = $this->request->getPost('nama');
        $keahlian = $this->request->getPost('keahlian');

        // VALIDASI
        if(!$nama){
            return redirect()->back()->withInput()
                ->with('error','Nama mentor wajib diisi');
        }

        if(!$keahlian){
            return redirect()->back()->withInput()
                ->with('error','Keahlian wajib diisi');
        }

        $this->mentorModel->save([
            'nama' => $nama,
            'keahlian' => $keahlian,
            'aktif' => $this->request->getPost('aktif') ?? 1
        ]);
        $this->log('Menambah mentor: ' . $nama);

        return redirect()->to('/admin/mentor')
            ->with('success','Mentor berhasil ditambahkan');
    }

    // ================= EDIT =================
    public function editmentor($id)
    {
        $mentor = $this->mentorModel->find($id);

        if(!$mentor){
            return redirect()->back()->with('error','Mentor tidak ditemukan');
        }

        $this->log('Mengedit mentor: ' . $mentor['nama']);
        return view('admin/mentor/edit', [
            'mentor' => $mentor
        ]);
    }

    // ================= UPDATE =================
    public function updatementor($id)
    {
        $mentor = $this->mentorModel->find($id);

        if(!$mentor){
            return redirect()->back()->with('error','Mentor tidak ditemukan');
        }

        $nama = $this->request->getPost('nama');
        $keahlian = $this->request->getPost('keahlian');

        if(!$nama || !$keahlian){
            return redirect()->back()->withInput()
                ->with('error','Data tidak lengkap');
        }

        $this->mentorModel->update($id, [
            'nama' => $nama,
            'keahlian' => $keahlian,
            'aktif' => $this->request->getPost('aktif')
        ]);

            $this->log('Mengedit mentor: ' . $nama);
        return redirect()->to('/admin/mentor')
            ->with('success','Mentor berhasil diupdate');
    }

    // ================= HAPUS =================
    public function hapusmentor($id)
    {
        $mentor = $this->mentorModel->find($id);

        if(!$mentor){
            return redirect()->back()->with('error','Mentor tidak ditemukan');
        }

        $this->mentorModel->delete($id);

        $this->log('Menghapus mentor: ' . $mentor['nama']);
        return redirect()->to('/admin/mentor')
            ->with('success','Mentor berhasil dihapus');
    }
 
    
    public function settings()
    {
        $model = new SettingModel();
        $data['setting'] = $model->first();

        $this->log('Masuk halaman setting');
        return view('admin/setting/index', $data);
    }


    public function updateSettings()
    {
        $model = new SettingModel();

        $model->update(1, [
            'biaya_pendaftaran' => $this->request->getPost('biaya_pendaftaran')
        ]);

            $this->log('Mengupdate biaya pendaftaran: ' . $this->request->getPost('biaya_pendaftaran'));
        return redirect()->back()->with('success', 'Biaya berhasil diupdate');
    }

   // ================= INDEX =================
public function index()
{
    $this->log('Masuk halaman kategori');
    $data['kategori'] = $this->kategori->findAll();
    return view('admin/kategori/index', $data);
}

// ================= CREATE =================
public function create()
{
    $this->log('Masuk halaman tambah kategori');
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
        $this->log('Menambah kategori: ' . $this->request->getPost('nama_kategori'));

    return redirect()->to('/admin/kategori')->with('success', 'Data berhasil ditambahkan');
}

// ================= EDIT =================
public function edit($id)
{
    $this->log('Masuk halaman edit kategori');
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
        $this->log('Mengedit kategori: ' . $this->request->getPost('nama_kategori'));

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

        $this->log('Menghapus kategori: ' . $kategori['nama_kategori']);
    return redirect()->to('/admin/kategori')
    ->with('success', 'Data berhasil dihapus');
}

  public function indexlevel($id_kursus)
{
    $kursus = $this->kursusModel->find($id_kursus);

    if(!$kursus){
        return redirect()->to('/admin/kursus')
            ->with('error','Kursus tidak ditemukan');
    }

    $level = $this->levelModel
        ->select('level.*, mentor.nama as nama_mentor')
        ->join('mentor','mentor.id = level.id_mentor','left')
        ->where('level.id_kursus', $id_kursus)
        ->orderBy('level.urutan','ASC')
        ->findAll();

    // 🔥 HITUNG SLOT DINAMIS
    foreach($level as &$l){

        $aktif = $this->detailModel
            ->join('transaksi', 'transaksi.id = transaksi_detail.id_transaksi')
            ->where('transaksi_detail.id_item', $l['id'])
            ->where('transaksi_detail.tanggal_selesai >=', date('Y-m-d'))
            ->countAllResults();

        $l['slot_terpakai'] = $aktif;
        $l['slot_sisa'] = $l['slot'] - $aktif;
    }

    $this->log('Masuk halaman level untuk kursus: ' . $kursus['nama_kursus']);

    return view('admin/level/index', [
        'kursus' => $kursus,
        'level' => $level
    ]);
}


// ================= TAMBAH =================
public function tambah($id_kursus)
{
    $this->log('Masuk halaman tambah level untuk kursus ID: ' . $id_kursus);
    return view('admin/level/tambah', [
        'id_kursus' => $id_kursus,
        'mentor' => $this->mentorModel->where('aktif',1)->findAll()
    ]);
}


public function simpan()
{
    $id_kursus = $this->request->getPost('id_kursus');
    $id_mentor = $this->request->getPost('id_mentor');

    $harga      = (int)$this->request->getPost('harga');
    $pertemuan  = (int)$this->request->getPost('pertemuan');
    $urutan     = (int)$this->request->getPost('urutan');
    $slot       = (int)$this->request->getPost('slot');

    $jam_mulai   = $this->request->getPost('jam_mulai');
    $jam_selesai = $this->request->getPost('jam_selesai');
    $hari        = $this->request->getPost('hari');

    // ================= VALIDASI =================
    if(!$this->request->getPost('nama_level')){
        return redirect()->back()->withInput()
            ->with('error','Nama level wajib diisi');
    }

    if(!$id_mentor){
        return redirect()->back()->withInput()
            ->with('error','Mentor wajib dipilih');
    }

    if($harga <= 0 || $pertemuan <= 0 || $urutan <= 0 || $slot <= 0){
        return redirect()->back()->withInput()
            ->with('error','Data angka tidak valid');
    }

    if(!is_array($hari) || empty($hari)){
        return redirect()->back()->withInput()
            ->with('error','Pilih minimal 1 hari');
    }

    $start = strtotime($jam_mulai);
    $end   = strtotime($jam_selesai);

    if($end <= $start){
        return redirect()->back()->withInput()
            ->with('error','Jam tidak valid');
    }

    // ================= CEK BENTROK LEVEL =================
    $existing = $this->levelModel
        ->where('id_kursus', $id_kursus)
        ->findAll();

    foreach($existing as $e){

        $hari_lama = explode(',', $e['hari']);

        if(array_intersect($hari_lama, $hari)){

            $start2 = strtotime($e['jam_mulai']);
            $end2   = strtotime($e['jam_selesai']);

            if($start < $end2 && $end > $start2){
                return redirect()->back()->withInput()
                    ->with('error','Jadwal bentrok dengan level lain!');
            }
        }
    }

    // ================= CEK BENTROK MENTOR =================
    $mentorBentrok = $this->levelModel
        ->where('id_mentor', $id_mentor)
        ->findAll();

    foreach($mentorBentrok as $m){

        $hari_lama = explode(',', $m['hari']);

        if(array_intersect($hari_lama, $hari)){

            $start2 = strtotime($m['jam_mulai']);
            $end2   = strtotime($m['jam_selesai']);

            if($start < $end2 && $end > $start2){
                return redirect()->back()->withInput()
                    ->with('error','Mentor sudah mengajar di jadwal tersebut!');
            }
        }
    }

    // ================= DURASI =================
    $selisih = $end - $start;
    $jam   = floor($selisih / 3600);
    $menit = floor(($selisih % 3600) / 60);

    $durasi = ($jam ? $jam.' jam ' : '') . ($menit ? $menit.' menit' : '');

    // ================= SIMPAN =================
    $this->levelModel->save([
        'id_kursus'   => $id_kursus,
        'nama_level'  => $this->request->getPost('nama_level'),
        'deskripsi'   => $this->request->getPost('deskripsi'),
        'urutan'      => $urutan,
        'harga'       => $harga,
        'pertemuan'   => $pertemuan,
        'id_mentor'   => $id_mentor,
        'slot'        => $slot,
        'hari'        => implode(',', $hari),
        'jam_mulai'   => $jam_mulai,
        'jam_selesai' => $jam_selesai,
        'durasi'      => trim($durasi)
    ]);

    $this->log('Level berhasil ditambahkan untuk kursus ID: ' . $id_kursus);

    return redirect()->to('/admin/level/index/'.$id_kursus)
        ->with('success','Level berhasil ditambahkan');
}


// ================= EDIT =================
public function editlevel($id)
{
    $level = $this->levelModel->find($id);

    if(!$level){
        return redirect()->back()->with('error','Level tidak ditemukan');
    }

    $level['hari_array'] = explode(',', $level['hari'] ?? '');

    $this->log('Masuk halaman edit level ID: ' . $id);
    return view('admin/level/edit', [
        'level' => $level,
         'mentor' => $this->mentorModel->where('aktif',1)->findAll()
    ]);
}


public function updatelevel($id)
{
    $level = $this->levelModel->find($id);

    if(!$level){
        return redirect()->back()->with('error','Level tidak ditemukan');
    }

    $id_kursus = $level['id_kursus'];
    $id_mentor = $this->request->getPost('id_mentor');

    $harga      = (int)$this->request->getPost('harga');
    $pertemuan  = (int)$this->request->getPost('pertemuan');
    $urutan     = (int)$this->request->getPost('urutan');
    $slot       = (int)$this->request->getPost('slot');

    $jam_mulai   = $this->request->getPost('jam_mulai');
    $jam_selesai = $this->request->getPost('jam_selesai');
    $hari        = $this->request->getPost('hari');

    // ================= VALIDASI =================
    if(!$id_mentor){
        return redirect()->back()->withInput()
            ->with('error','Mentor wajib dipilih');
    }

    if($harga <= 0 || $pertemuan <= 0 || $urutan <= 0 || $slot <= 0){
        return redirect()->back()->withInput()
            ->with('error','Data tidak valid');
    }

    if(!is_array($hari) || empty($hari)){
        return redirect()->back()->withInput()
            ->with('error','Pilih minimal 1 hari');
    }

    $start = strtotime($jam_mulai);
    $end   = strtotime($jam_selesai);

    if($end <= $start){
        return redirect()->back()->withInput()
            ->with('error','Jam tidak valid');
    }

    // ================= CEK BENTROK LEVEL =================
    $existing = $this->levelModel
        ->where('id_kursus', $id_kursus)
        ->where('id !=', $id)
        ->findAll();

    foreach($existing as $e){

        $hari_lama = explode(',', $e['hari']);

        if(array_intersect($hari_lama, $hari)){

            $start2 = strtotime($e['jam_mulai']);
            $end2   = strtotime($e['jam_selesai']);

            if($start < $end2 && $end > $start2){
                return redirect()->back()->withInput()
                    ->with('error','Jadwal bentrok dengan level lain!');
            }
        }
    }

    // ================= CEK BENTROK MENTOR =================
    $mentorBentrok = $this->levelModel
        ->where('id_mentor', $id_mentor)
        ->where('id !=', $id)
        ->findAll();

    foreach($mentorBentrok as $m){

        $hari_lama = explode(',', $m['hari']);

        if(array_intersect($hari_lama, $hari)){

            $start2 = strtotime($m['jam_mulai']);
            $end2   = strtotime($m['jam_selesai']);

            if($start < $end2 && $end > $start2){
                return redirect()->back()->withInput()
                    ->with('error','Mentor sudah dipakai di jadwal lain!');
            }
        }
    }

    // ================= DURASI =================
    $selisih = $end - $start;
    $jam   = floor($selisih / 3600);
    $menit = floor(($selisih % 3600) / 60);

    $durasi = ($jam ? $jam.' jam ' : '') . ($menit ? $menit.' menit' : '');

    // ================= UPDATE =================
    $this->levelModel->update($id, [
        'nama_level'  => $this->request->getPost('nama_level'),
        'deskripsi'   => $this->request->getPost('deskripsi'),
        'urutan'      => $urutan,
        'harga'       => $harga,
        'pertemuan'   => $pertemuan,
        'id_mentor'   => $id_mentor,
        'slot'        => $slot,
        'hari'        => implode(',', $hari),
        'jam_mulai'   => $jam_mulai,
        'jam_selesai' => $jam_selesai,
        'durasi'      => trim($durasi)
    ]);

    $this->log('Level berhasil diupdate untuk kursus ID: ' . $id_kursus);

    return redirect()->to('/admin/level/index/'.$id_kursus)
        ->with('success','Level berhasil diupdate');
}

// ================= HAPUS =================
public function hapus($id)
{
    $level = $this->levelModel->find($id);

    if(!$level){
        return redirect()->back()->with('error','Level tidak ditemukan');
    }

    $id_kursus = $level['id_kursus'];

    $this->levelModel->delete($id);
    $this->log('Level berhasil dihapus untuk kursus ID: ' . $id_kursus);

    return redirect()->to('/admin/level/index/'.$id_kursus)
        ->with('success','Level berhasil dihapus');
}

public function indexsiswa()
{
    $today = date('Y-m-d');

    // 🔍 ambil filter
    $search = $this->request->getGet('search');
    $kursus = $this->request->getGet('kursus');
    $status = $this->request->getGet('status');

    $builder = $this->siswaModel
        ->select("
            siswa.*,

            COUNT(DISTINCT transaksi.id) as total_transaksi,

            COUNT(CASE 
                WHEN transaksi_detail.tanggal_selesai >= '$today' 
                THEN 1 
            END) as kursus_aktif,

            GROUP_CONCAT(DISTINCT k.nama_kursus SEPARATOR ', ') as nama_kursus
        ")
        ->join('transaksi', 'transaksi.id_siswa = siswa.id', 'left')
        ->join('transaksi_detail', 'transaksi_detail.id_transaksi = transaksi.id', 'left')

        // 🔥 ambil level dulu
        ->join('level l', 'l.id = transaksi_detail.id_item', 'left')

        // 🔥 baru ambil kursus dari level
        ->join('kursus k', 'k.id = l.id_kursus', 'left');

    // ================= SEARCH =================
    if (!empty($search)) {
        $builder->groupStart()
            ->like('siswa.nama', $search)
            ->orLike('siswa.no_hp', $search)
        ->groupEnd();
    }

    // ================= FILTER KURSUS =================
    if (!empty($kursus)) {
        $builder->where('k.id', $kursus);
    }

    // ================= GROUP =================
    $builder->groupBy('siswa.id');

    // ================= FILTER STATUS =================
    if ($status == 'aktif') {
        $builder->having('kursus_aktif >', 0);
    } elseif ($status == 'nonaktif') {
        $builder->having('kursus_aktif =', 0);
    }

    $siswa = $builder->get()->getResultArray();

    // ================= AMBIL LIST KURSUS =================
    $listKursus = $this->kursusModel->findAll();

    // ================= FORMAT STATUS =================
    foreach ($siswa as &$s) {
        $s['status'] = $s['kursus_aktif'] > 0 ? 'aktif' : 'nonaktif';
    }

        $this->log('Masuk halaman siswa dengan filter - Search: ' . ($search ?? '-') . ', Kursus: ' . ($kursus ?? '-') . ', Status: ' . ($status ?? '-'));
    return view('admin/siswa/index', [
        'siswa' => $siswa,
        'search' => $search,
        'listKursus' => $listKursus,
        'filter' => [
            'kursus' => $kursus,
            'status' => $status
        ]
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

    $this->log('Melihat detail siswa: ' . $siswa['nama']);

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

    $this->log('Masuk halaman edit siswa: ' . $siswa['nama']);

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
    $this->log('Mengupdate siswa: ' . $siswa['nama']);

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