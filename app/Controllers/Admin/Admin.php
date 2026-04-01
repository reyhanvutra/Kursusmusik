<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\KursusModel;
use App\Models\PaketModel;
use App\Models\PaketDetailModel;
use App\Models\TransaksiModel;
use App\Models\LogActivityModel;
use App\Models\SettingModel;
use App\Controllers\BaseController;

class Admin extends BaseController
{
    protected $userModel;
    protected $kursusModel;
    protected $paketModel;
    protected $paketDetailModel;
    protected $transaksiModel;
    protected $logActivityModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kursusModel = new KursusModel();
        $this->paketModel = new PaketModel();
        $this->paketDetailModel = new PaketDetailModel();  
        $this->transaksiModel = new TransaksiModel(); 
        $this->logActivityModel = new LogActivityModel();
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

    return view('admin/dashboard', [
        'total_kursus' => $this->kursusModel->countAll(),
        'total_user' => $this->userModel->countAll(),

        // 🔥 hitung transaksi hari ini
        'transaksi_hari_ini' => $this->transaksiModel
            ->where('tanggal', $today)
            ->countAllResults()
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

        return redirect()->to('/admin/user');
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
        return redirect()->to('/admin/user');
    }

    public function hapus_user($id)
    {
        $this->userModel->delete($id);
        
            $this->log('Menghapus user: '.$this->request->getPost('nama'));
        return redirect()->to('/admin/user');

    }

    // ================= KURSUS =================
  public function kursus()
{
    // ================= KURSUS =================
    $data['kursus'] = $this->kursusModel->findAll();

    // ================= PAKET =================
    $paket = $this->paketModel->findAll();

    foreach($paket as &$p){

        $detail = $this->paketDetailModel
            ->select('kursus.nama_kursus, kursus.hari, kursus.instruktur, kursus.durasi')
            ->join('kursus','kursus.id = paket_detail.id_kursus')
            ->where('id_paket', $p['id'])
            ->findAll();

        // ================= LIST NAMA =================
        $namaKursus = array_column($detail, 'nama_kursus');
        $p['list_kursus'] = implode(', ', $namaKursus);

        // ================= DETAIL =================
        $p['detail_kursus'] = $detail;

        // ================= HARI =================
        $semuaHari = [];

        foreach($detail as $d){
            if($d['hari']){ // 🔥 biar ga error kalau NULL
                foreach(explode(',', $d['hari']) as $h){
                    $semuaHari[] = trim($h);
                }
            }
        }

        $semuaHari = array_unique($semuaHari);
        $p['hari'] = implode(', ', $semuaHari);
    }
    
    // 🔥 INI YANG KAMU LUPA
    $data['paket'] = $paket;
        // 🔥 LOG
        $this->log('Masuk halaman kursus & paket');

    return view('admin/kursus/index', $data);
}

    public function tambah_kursus()
    {
        return view('admin/kursus/tambah');
    }

    public function simpan_kursus()
    {
        if(!$this->request->getPost('nama_kursus')){
            return redirect()->back()->with('error','Nama kursus wajib diisi');
        }

        $file = $this->request->getFile('gambar');
        $namaGambar = null;

        if($file && $file->isValid()){
            $namaGambar = $file->getRandomName();
            $file->move('uploads/', $namaGambar);
        }

        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');

        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);

        if($end <= $start){
            return redirect()->back()->with('error','Jam tidak valid');
        }

        $durasi = ($end - $start) / 3600;
         $hari = $this->request->getPost('hari');

        $this->kursusModel->save([
            'nama_kursus' => $this->request->getPost('nama_kursus'),
            'harga' => $this->request->getPost('harga'),
            'instruktur' => $this->request->getPost('instruktur'),
            'hari' => $hari ? implode(',', $hari) : null,
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'durasi' => $durasi . ' jam',
            'slot' => $this->request->getPost('slot'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar' => $namaGambar
        ]);
        
        $this->log('Menambah kursus: ' . $this->request->getPost('nama_kursus'));

        return redirect()->to('/admin/kursus');
    }

    public function edit_kursus($id)
    {
        return view('admin/kursus/edit', [
            'kursus' => $this->kursusModel->find($id)
        ]);
    }

    public function update_kursus($id)
    {
        $kursus = $this->kursusModel->find($id);

        $file = $this->request->getFile('gambar');
        $namaGambar = $kursus['gambar'];

        if($file && $file->isValid()){
            $namaGambar = $file->getRandomName();
            $file->move('uploads/', $namaGambar);

            if($kursus['gambar']){
                @unlink('uploads/'.$kursus['gambar']);
            }
        }

        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');

        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);

        if($end <= $start){
            return redirect()->back()->with('error','Jam tidak valid');
        }

        $durasi = ($end - $start) / 3600;

        $this->kursusModel->update($id, [
            'nama_kursus' => $this->request->getPost('nama_kursus'),
            'harga' => $this->request->getPost('harga'),
            'instruktur' => $this->request->getPost('instruktur'),
            'hari' => implode(',', $this->request->getPost('hari')),
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
            'durasi' => $durasi . ' jam',
            'slot' => $this->request->getPost('slot'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar' => $namaGambar
        ]);
            $this->log('Mengedit kursus: ' . $this->request->getPost('nama_kursus'));

        return redirect()->to('/admin/kursus');
    }

    public function hapus_kursus($id)
    {
        $kursus = $this->kursusModel->find($id);

        if($kursus && $kursus['gambar']){
            @unlink('uploads/'.$kursus['gambar']);
        }

        $this->kursusModel->delete($id);
            $this->log('Menghapus kursus: ' . $kursus['nama_kursus']);
        return redirect()->to('/admin/kursus');
          
    }

    // ================= PAKET =================
    public function paket()
    {
       $this->log('Masuk halaman paket');
        return redirect()->to('/admin/kursus?tab=paket');
       
    }

    public function tambah_paket()
    {
        return view('admin/paket/tambah', [
            'kursus' => $this->kursusModel->findAll()
        ]);
    }

    public function simpan_paket()
    {
        $kursus = $this->request->getPost('kursus');

        if(!$kursus){
            return redirect()->back()->with('error','Pilih minimal 1 kursus');
        }

        $idPaket = $this->paketModel->insert([
            'nama_paket' => $this->request->getPost('nama_paket'),
            'harga' => $this->request->getPost('harga'),
            'durasi' => $this->request->getPost('durasi'), 
            'deskripsi' => $this->request->getPost('deskripsi'),
        ]);

        foreach($kursus as $k){
            $this->paketDetailModel->insert([
                'id_paket' => $idPaket,
                'id_kursus' => $k
            ]);
        }
        
        $this->log('Menambah paket: ' . $this->request->getPost('nama_paket'));

        return redirect()->to('/admin/kursus?tab=paket');
    }

    public function edit_paket($id)
    {
        return view('admin/paket/edit', [
            'paket' => $this->paketModel->find($id),
            'kursus' => $this->kursusModel->findAll(),
            'detail' => $this->paketDetailModel->where('id_paket', $id)->findAll()
        ]);
    }

    public function update_paket($id)
    {
        $kursus = $this->request->getPost('kursus');

        if(!$kursus){
            return redirect()->back()->with('error','Pilih minimal 1 kursus');
        }

        $this->paketModel->update($id, [
            'nama_paket' => $this->request->getPost('nama_paket'),
            'harga' => $this->request->getPost('harga'),
            'durasi' => $this->request->getPost('durasi'), 
            'deskripsi' => $this->request->getPost('deskripsi'),
        ]);

        $this->paketDetailModel->where('id_paket', $id)->delete();

        foreach($kursus as $k){
            $this->paketDetailModel->insert([
                'id_paket' => $id,
                'id_kursus' => $k
            ]);
        }
        $this->log('Mengupdate paket: ' . $this->request->getPost('nama_paket'));

        return redirect()->to('/admin/kursus?tab=paket');
    }

    public function hapus_paket($id)
    {
        $this->paketModel->delete($id);
        $this->paketDetailModel->where('id_paket', $id)->delete();
        $this->log('Menghapus paket: ' . $this->request->getPost('nama_paket'));
        return redirect()->to('/admin/kursus?tab=paket');
           
    }
    
    public function index()
    {
        $model = new SettingModel();
        $data['setting'] = $model->first();

        return view('admin/setting/index', $data);
    }

    public function update()
    {
        $model = new SettingModel();

        $model->update(1, [
            'biaya_pendaftaran' => $this->request->getPost('biaya_pendaftaran')
        ]);

        return redirect()->back()->with('success', 'Biaya berhasil diupdate');
    }

}