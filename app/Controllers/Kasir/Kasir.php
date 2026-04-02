<?php

namespace App\Controllers\Kasir;

use App\Models\KursusModel;
use App\Models\PaketModel;
use App\Models\TransaksiModel;
use App\Models\TransaksiDetailModel;
use App\Models\PaketDetailModel;
use App\Models\LogActivityModel;
use App\Models\SiswaModel;
use App\Models\SettingModel;
use App\Models\KategoriKursusModel;
use App\Controllers\BaseController;

use Dompdf\Dompdf;

class Kasir extends BaseController
{
    protected $kursusModel;
    protected $paketModel;
    protected $transaksiModel;
    protected $detailModel;
    protected $paketDetailModel;
    protected $logActivityModel;
    protected $siswaModel;
    protected $settingModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->kursusModel = new KursusModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel = new TransaksiDetailModel();
        $this->paketDetailModel = new PaketDetailModel();
        $this->logActivityModel = new LogActivityModel();
        $this->siswaModel = new SiswaModel();
        $this->settingModel = new SettingModel();
        $this->kategoriModel = new KategoriKursusModel();
    }

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

    // ================= KURSUS AKTIF =================
    $kursus_aktif = $this->kursusModel
        ->where('slot >', 0)
        ->countAllResults();

    // ================= PENDAPATAN =================
    $pendapatan = $this->transaksiModel
        ->selectSum('total_harga')
        ->where('tanggal', $today)
        ->first();

    // ================= TOTAL TRANSAKSI =================
    $total_transaksi = $this->transaksiModel
        ->where('tanggal', $today)
        ->countAllResults();

    // ================= AMBIL TRANSAKSI =================
    $transaksi = $this->transaksiModel
        ->select('transaksi.*, siswa.nama as nama_pembeli')
        ->join('siswa', 'siswa.id = transaksi.id_siswa')
        ->orderBy('transaksi.id', 'DESC')
        ->findAll();

    // ================= LOAD MODEL LEVEL =================
    $levelModel = new \App\Models\LevelModel();

    // ================= AMBIL ITEM =================
    foreach($transaksi as &$t){

        $detail = $this->detailModel
            ->where('id_transaksi', $t['id'])
            ->findAll();

        $items = [];

        foreach($detail as $d){

            // 🔥 ambil level
            $level = $levelModel->find($d['id_item']);

            if($level){

                // 🔥 ambil kursus dari level
                $kursus = $this->kursusModel->find($level['id_kursus']);

                if($kursus){
                    $items[] = $kursus['nama_kursus'] . ' - ' . $level['nama_level'];
                }else{
                    $items[] = 'Kursus dihapus';
                }

            }else{
                $items[] = 'Level dihapus';
            }
        }

        $t['items'] = $items;
    }

    // ================= LOG =================
    $this->log('Masuk dashboard kasir');

    // ================= VIEW =================
    return view('kasir/dashboard', [
        'kursus_aktif' => $kursus_aktif,
        'pendapatan_hari_ini' => $pendapatan['total_harga'] ?? 0,
        'total_transaksi_hari_ini' => $total_transaksi,
        'transaksi' => $transaksi
    ]);
}
    public function siswa()
{
    $siswa = $this->siswaModel
        ->select('siswa.*, COUNT(transaksi.id) as total_transaksi')
        ->join('transaksi', 'transaksi.id_siswa = siswa.id', 'left')
        ->groupBy('siswa.id')
        ->findAll();

    return view('kasir/siswa', [
        'siswa' => $siswa
    ]);
}
public function detailSiswa($id)
{
    $siswa = $this->siswaModel->find($id);

    $data = $this->detailModel
        ->select('transaksi_detail.*, 
                  kursus.nama_kursus, 
                  paket.nama_paket')
        ->join('transaksi', 'transaksi.id = transaksi_detail.id_transaksi')
        ->join('kursus', 'kursus.id = transaksi_detail.id_item', 'left')
        ->join('paket', 'paket.id = transaksi_detail.id_item', 'left')
        ->where('transaksi.id_siswa', $id)
        ->orderBy('transaksi_detail.id', 'DESC')
        ->findAll();

    $kursus = [];
    $paket = [];

    foreach($data as $d){

        if($d['tipe'] == 'kursus'){

            if(!isset($kursus[$d['id_item']])){

                $today = date('Y-m-d');
                $sisa = (strtotime($d['tanggal_selesai']) - strtotime($today)) / 86400;

                $kursus[$d['id_item']] = [
                    'id_detail' => $d['id'],
                    'nama' => $d['nama_kursus'],
                    'mulai' => $d['tanggal_mulai'],
                    'selesai' => $d['tanggal_selesai'],
                    'sisa_hari' => max(0, floor($sisa))
                ];
            }

        } else {

            if(!isset($paket[$d['id_item']])){

                $paket[$d['id_item']] = [
                    'id_item' => $d['id_item'],
                    'nama' => $d['nama_paket'],
                    'tanggal' => $d['tanggal_mulai']
                ];
            }
        }
    }

    return view('kasir/detail_siswa', [
        'siswa' => $siswa,
        'kursus' => $kursus,
        'paket' => $paket
    ]);
}
public function detail($id)
{
    $transaksi = $this->transaksiModel
        ->select('transaksi.*, siswa.nama as nama_pembeli, siswa.no_hp')
        ->join('siswa', 'siswa.id = transaksi.id_siswa')
        ->where('transaksi.id', $id)
        ->first();

    if(!$transaksi){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $detail = $this->detailModel
        ->where('id_transaksi', $id)
        ->findAll();

    $levelModel = new \App\Models\LevelModel();

    foreach($detail as &$d){

        if($d['tipe'] == 'kursus'){

            // 🔥 ambil LEVEL dulu
            $level = $levelModel->find($d['id_item']);

            if($level){
                $kursus = $this->kursusModel->find($level['id_kursus']);

                $d['nama'] = ($kursus['nama_kursus'] ?? '') . ' - ' . ($level['nama_level'] ?? '');
            }else{
                $d['nama'] = 'Level dihapus';
            }

            $d['tipe_label'] = 'Kursus';

        }else{

            $p = $this->paketModel->find($d['id_item']);

            $d['nama'] = $p['nama_paket'] ?? 'Paket dihapus';
            $d['tipe_label'] = 'Paket';
        }

        $d['bulan'] = $d['bulan'] ?? 1;

        // 🔥 harga per bulan
        $d['harga_per_bulan'] = $d['bulan'] > 0 
            ? $d['harga'] / $d['bulan'] 
            : $d['harga'];

        // 🔥 format tanggal
        $d['tanggal_mulai_f'] = !empty($d['tanggal_mulai']) 
            ? date('d M Y', strtotime($d['tanggal_mulai'])) 
            : '-';

        $d['tanggal_selesai_f'] = !empty($d['tanggal_selesai']) 
            ? date('d M Y', strtotime($d['tanggal_selesai'])) 
            : '-';
    }

    $this->log('Melihat detail transaksi ' . $id);

    return view('kasir/detail', [
        't'      => $transaksi,
        'detail' => $detail
    ]);
}

    // ================= PILIH =================
 public function pilih()
{

    $kategori = $this->kategoriModel->findAll();

    // ambil kursus per kategori
    foreach($kategori as &$k){

        $k['kursus'] = $this->kursusModel
            ->where('id_kategori', $k['id'])
            ->findAll();
    }

    $this->log('Masuk halaman pilih kategori kursus');

    return view('kasir/pilih', [
        'kategori' => $kategori
    ]);
}
public function transaksi()
{
    return view('kasir/transaksi', [
        'siswa' => $this->siswaModel->findAll(),
        'setting' => $this->settingModel->first()
    ]);
}
public function simpan()
{
    $items = json_decode($this->request->getPost('items'), true);

    if(empty($items)){
        return redirect()->back()->with('error','Pilih kursus dulu');
    }

    $id_siswa = $this->request->getPost('id_siswa');

    if(!$id_siswa){
        return redirect()->back()->with('error','Pilih siswa dulu');
    }

    // ================= AMBIL DATA SISWA =================
    $siswa = $this->siswaModel->find($id_siswa);

    if(!$siswa){
        return redirect()->back()->with('error','Siswa tidak ditemukan');
    }

    // ================= SETTING =================
    $setting = $this->settingModel->first();
    $biaya_setting = $setting['biaya_pendaftaran'] ?? 0;

    // ================= HITUNG TOTAL =================
    $total = 0;

    foreach($items as $i){
        $bulan = (int)($i['bulan'] ?? 1);
        $harga = (int)$i['harga'];

        $total += $harga * $bulan; // 🔥 FIX DI SINI
    }

    // ================= BIAYA PENDAFTARAN =================
    $biaya_pendaftaran = 0;

    if(isset($siswa['sudah_daftar']) && $siswa['sudah_daftar'] == 0){
        $biaya_pendaftaran = $biaya_setting;
    }

    $total_final = $total + $biaya_pendaftaran;

    // ================= VALIDASI BAYAR =================
    $bayar = (int)$this->request->getPost('bayar');

    if($bayar < $total_final){
        return redirect()->back()->with('error','Uang kurang');
    }

    // ================= SIMPAN TRANSAKSI =================
    $id = $this->transaksiModel->insert([
        'id_siswa' => $id_siswa,
        'total_harga' => $total_final,
        'biaya_pendaftaran' => $biaya_pendaftaran,
        'uang_bayar' => $bayar,
        'uang_kembali' => $bayar - $total_final,
        'tanggal' => date('Y-m-d')
    ]);

    if(!$id){
        return redirect()->back()->with('error','Gagal simpan transaksi');
    }

    // ================= DETAIL =================
    $levelModel = new \App\Models\LevelModel();

    foreach($items as $i){

        $level = $levelModel->find($i['id']);
        $bulan = (int)($i['bulan'] ?? 1);
        $harga = (int)$i['harga'];

        $this->detailModel->insert([
            'id_transaksi' => $id,
            'tipe' => 'kursus',
            'id_item' => $i['id'], // ID LEVEL
            'harga' => $harga * $bulan, // 🔥 FIX
            'bulan' => $bulan,
            'tanggal_mulai' => $i['tanggal_mulai'] ?? date('Y-m-d'),
            'tanggal_selesai' => $i['tanggal_selesai'] ?? date('Y-m-d', strtotime('+1 month'))
        ]);

        // 🔥 KURANGI SLOT KURSUS
        if($level){
            $this->kursusModel
                ->set('slot','slot-1',false)
                ->where('id', $level['id_kursus'])
                ->update();
        }
    }

    // ================= UPDATE SISWA =================
    if(isset($siswa['sudah_daftar']) && $siswa['sudah_daftar'] == 0){
        $this->siswaModel->update($id_siswa, [
            'sudah_daftar' => 1
        ]);
    }

    $this->log('Transaksi siswa '.$siswa['nama'].' total Rp '.number_format($total_final,0,',','.'));

    return redirect()->to('/kasir/detail/'.$id);
}
    // ================= CETAK PDF =================
  public function cetak($id)
{
    // 🔥 AMBIL TRANSAKSI + SISWA
    $transaksi = $this->transaksiModel
        ->select('transaksi.*, siswa.nama as nama_pembeli, siswa.no_hp')
        ->join('siswa', 'siswa.id = transaksi.id_siswa', 'left')
        ->where('transaksi.id', $id)
        ->first();

    if(!$transaksi){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // 🔥 AMBIL DETAIL
    $detail = $this->detailModel
        ->where('id_transaksi', $id)
        ->findAll();

   foreach($detail as &$d){

    if($d['tipe'] == 'kursus'){

        $level = (new \App\Models\LevelModel())->find($d['id_item']);

        if($level){
            $kursus = $this->kursusModel->find($level['id_kursus']);
            $d['nama'] = ($kursus['nama_kursus'] ?? '') . ' - ' . $level['nama_level'];
        }else{
            $d['nama'] = 'Level dihapus';
        }

    }else{

        $p = $this->paketModel->find($d['id_item']);
        $d['nama'] = $p['nama_paket'] ?? 'Paket dihapus';
    }
}

    // 🔥 AMANKAN BIAR GA ERROR
    $transaksi['nama_pembeli'] = $transaksi['nama_pembeli'] ?? 'Umum';
    $transaksi['no_hp'] = $transaksi['no_hp'] ?? '-';

    // 🔥 LOG
    $this->log('Mencetak struk transaksi atas nama ' . $transaksi['nama_pembeli'] . ' dengan total ' . $transaksi['total_harga']);

    $html = view('kasir/struk_pdf', [
        't' => $transaksi,
        'detail' => $detail
    ]);

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper([0,0,226.77,600]);
    $dompdf->render();

    $dompdf->stream('struk.pdf', ['Attachment' => false]);
}
public function detailKursus($id)
{
    $levelModel = new \App\Models\LevelModel();

    // ================= AMBIL KURSUS =================
    $kursus = $this->kursusModel->find($id);

    // 🔥 VALIDASI
    if(!$kursus){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // ================= AMBIL LEVEL =================
    $level = $levelModel
        ->where('id_kursus', $id)
        ->orderBy('harga', 'ASC') // 🔥 biar urut dari murah (biasanya beginner)
        ->findAll();

    // ================= TAMBAHAN DATA LEVEL =================
    foreach($level as &$l){

        // default kalau belum ada field
        $l['pertemuan'] = $l['pertemuan'] ?? 4;
        $l['target'] = $l['target'] ?? 'Semua level';

        // 🔥 label otomatis (optional biar keren)
        if(stripos($l['nama_level'], 'beginner') !== false){
            $l['badge'] = '🟢 Beginner';
        }elseif(stripos($l['nama_level'], 'intermediate') !== false){
            $l['badge'] = '🟡 Intermediate';
        }elseif(stripos($l['nama_level'], 'advanced') !== false){
            $l['badge'] = '🔴 Advanced';
        }else{
            $l['badge'] = '🎵 Level';
        }
    }

    // 🔥 LOG (opsional tapi bagus)
    $this->log('Melihat detail kursus: ' . $kursus['nama_kursus']);

    return view('kasir/detailkursus', [
        'k' => $kursus,
        'level' => $level
    ]);
}
public function detailPaket($id)
{
    $data['p'] = $this->paketModel->find($id);

    $detail = $this->paketDetailModel
        ->select('kursus.*')
        ->join('kursus','kursus.id = paket_detail.id_kursus')
        ->where('id_paket',$id)
        ->findAll();

    $data['kursus'] = $detail;

    $this->log('Melihat detail paket ' . $data['p']['nama_paket']);

    return view('kasir/detailpaket', $data);
}
public function tambahSiswa()
{
    $this->log('Masuk halaman tambah siswa baru');
    return view('kasir/tambah_siswa');
}

public function simpanSiswa()
{
    $this->siswaModel->insert([
        'nama' => $this->request->getPost('nama'),
        'no_hp' => $this->request->getPost('no_hp'),
        'alamat' => $this->request->getPost('alamat'),
        'tanggal_daftar' => date('Y-m-d')
    ]);

    $this->log('Tambah siswa: '.$this->request->getPost('nama'));

    return redirect()->to('/kasir/transaksi')
        ->with('success','Siswa berhasil ditambahkan');
}
public function simpanSiswaAjax()
{
    $data = $this->request->getJSON(true);

    $nama   = trim($data['nama']);
    $no_hp  = trim($data['no_hp']);
    $alamat = trim($data['alamat']);

    // VALIDASI WAJIB
    if(!$nama || !$no_hp){
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Nama & No HP wajib diisi'
        ]);
    }

    // 🔥 CEK DUPLIKAT (PAKAI NO HP PALING AMAN)
    $existing = $this->siswaModel
        ->where('no_hp', $no_hp)
        ->first();

    if($existing){
        return $this->response->setJSON([
            'status' => 'duplicate',
            'data' => $existing
        ]);
    }

    // 🔥 INSERT BARU
    $id = $this->siswaModel->insert([
        'nama' => $nama,
        'no_hp' => $no_hp,
        'alamat' => $alamat,
        'sudah_daftar' => 0,
        'tanggal_daftar' => date('Y-m-d')
    ]);

    return $this->response->setJSON([
        'status' => 'success',
        'id' => $id,
        'nama' => $nama,
        'no_hp' => $no_hp,
        'alamat' => $alamat
    ]);
}

}