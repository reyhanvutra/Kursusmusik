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

    // ================= PENDAPATAN HARI INI =================
    $pendapatan = $this->transaksiModel
        ->selectSum('total_harga')
        ->where('tanggal', $today)
        ->first();

    // ================= TOTAL TRANSAKSI HARI INI =================
    $total_transaksi = $this->transaksiModel
        ->where('tanggal', $today)
        ->countAllResults();

    // ================= AMBIL TRANSAKSI (LIMIT 5 TERBARU) =================
    $transaksi = $this->transaksiModel
        ->select('transaksi.*, siswa.nama as nama_pembeli')
        ->join('siswa', 'siswa.id = transaksi.id_siswa', 'left')
        ->orderBy('transaksi.id', 'DESC')
        ->limit(5) // 🔥 penting (preview saja)
        ->findAll();

    // ================= LOAD MODEL =================
    $levelModel = new \App\Models\LevelModel();

    // ================= AMBIL ITEM =================
    foreach ($transaksi as &$t) {

        $detail = $this->detailModel
            ->where('id_transaksi', $t['id'])
            ->findAll();

        $items = [];

        foreach ($detail as $d) {

            // 🔥 ambil level
            $level = $levelModel->find($d['id_item']);

            if ($level) {

                // 🔥 ambil kursus dari level
                $kursus = $this->kursusModel->find($level['id_kursus']);

                if ($kursus) {
                    $items[] = $kursus['nama_kursus'] . ' - ' . $level['nama_level'];
                } else {
                    $items[] = 'Kursus dihapus';
                }

            } else {
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
// ================= RIWAYAT TRANSAKSI =================
public function riwayat()
{
    // ================= AMBIL TRANSAKSI =================
    $transaksi = $this->transaksiModel
        ->select('transaksi.*, siswa.nama as nama_pembeli')
        ->join('siswa', 'siswa.id = transaksi.id_siswa', 'left')
        ->orderBy('transaksi.id', 'DESC')
        ->paginate(10);

    $pager = $this->transaksiModel->pager;

    // ================= LOAD MODEL =================
    $levelModel = new \App\Models\LevelModel();

    // ================= AMBIL ITEM =================
    foreach ($transaksi as &$t) {

        $detail = $this->detailModel
            ->where('id_transaksi', $t['id'])
            ->findAll();

        $items = [];

        foreach ($detail as $d) {

            $level = $levelModel->find($d['id_item']);

            if ($level) {

                $kursus = $this->kursusModel->find($level['id_kursus']);

                if ($kursus) {
                    $items[] = $kursus['nama_kursus'] . ' - ' . $level['nama_level'];
                } else {
                    $items[] = 'Kursus dihapus';
                }

            } else {
                $items[] = 'Level dihapus';
            }
        }

        $t['items'] = $items;
    }

    return view('kasir/riwayat', [
        'transaksi' => $transaksi,
        'pager' => $pager
    ]);
}
  public function siswa()
{
    $today = date('Y-m-d');

    $siswa = $this->siswaModel
        ->select("
            siswa.*,
            COUNT(DISTINCT transaksi.id) as total_transaksi,
            COUNT(CASE 
                WHEN transaksi_detail.tanggal_selesai >= '$today' 
                THEN 1 
            END) as kursus_aktif
        ")
        ->join('transaksi', 'transaksi.id_siswa = siswa.id', 'left')
        ->join('transaksi_detail', 'transaksi_detail.id_transaksi = transaksi.id', 'left')
        ->groupBy('siswa.id')
        ->findAll();

    return view('kasir/siswa', [
        'siswa' => $siswa
    ]);
}
public function detailSiswa($id)
{
    $siswa = $this->siswaModel->find($id);

    if(!$siswa){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $today = date('Y-m-d');

    // ambil semua riwayat detail kursus
    $data = $this->detailModel
        ->select('transaksi_detail.*, transaksi.tanggal')
        ->join('transaksi', 'transaksi.id = transaksi_detail.id_transaksi')
        ->where('transaksi.id_siswa', $id)
        ->orderBy('transaksi_detail.id', 'ASC') // 🔥 penting: dari lama ke baru
        ->findAll();

    $levelModel  = new \App\Models\LevelModel();
    $kursusModel = new \App\Models\KursusModel();

    $grouped = [];
    $totalAktif = 0;

    // ================= GROUP PER KURSUS =================
    foreach($data as $d){

        if($d['tipe'] != 'kursus') continue;

        $level = $levelModel->find($d['id_item']);
        if(!$level) continue;

        $kursusData = $kursusModel->find($level['id_kursus']);

        $nama = ($kursusData['nama_kursus'] ?? '-') . ' - ' . ($level['nama_level'] ?? '-');

        if(!isset($grouped[$nama])){
            $grouped[$nama] = [
                'nama' => $nama,
                'list' => []
            ];
        }

        $grouped[$nama]['list'][] = $d;
    }

    // ================= PROSES DATA =================
    foreach($grouped as &$g){

        $list = $g['list'];

        // data pertama & terakhir
        $pertama  = $list[0];
        $terakhir = end($list);

        $mulai_awal = $pertama['tanggal_mulai'];
        $selesai    = $terakhir['tanggal_selesai'];

        // jumlah perpanjang
        $jumlah_perpanjang = count($list) - 1;

        // hitung sisa hari
        $sisa = (strtotime($selesai) - strtotime($today)) / 86400;

        // hitung total durasi dari awal
        $durasi = (strtotime($selesai) - strtotime($mulai_awal)) / 86400;

        $status = ($selesai >= $today) ? 'aktif' : 'selesai';

        if($status == 'aktif'){
            $totalAktif++;
        }

        $g['data'] = [[
            'id_detail' => $terakhir['id'],
            'mulai_awal' => $mulai_awal,
            'selesai' => $selesai,
            'durasi_hari' => max(0, floor($durasi)),
            'jumlah_perpanjang' => $jumlah_perpanjang,
            'sisa_hari' => max(0, floor($sisa)),
            'status' => $status
        ]];
    }

    return view('kasir/detail_siswa', [
        'siswa' => $siswa,
        'kursus' => array_values($grouped),
        'total_aktif' => $totalAktif,
        'total_riwayat' => count($data)
    ]);
}
public function perpanjang($id_detail)
{
    $detail = $this->detailModel
        ->join('transaksi', 'transaksi.id = transaksi_detail.id_transaksi')
        ->where('transaksi_detail.id', $id_detail)
        ->first();

    if(!$detail){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    if($detail['tipe'] != 'kursus'){
        return redirect()->back()->with('error','Hanya kursus yang bisa diperpanjang');
    }

    $levelModel  = new \App\Models\LevelModel();
    $kursusModel = new \App\Models\KursusModel();
    $siswaModel  = new \App\Models\SiswaModel();

    $level  = $levelModel->find($detail['id_item']);
    $kursus = $kursusModel->find($level['id_kursus']);
    $siswa  = $siswaModel->find($detail['id_siswa']);

    $item = [
        'nama'  => $kursus['nama_kursus'] . ' - ' . $level['nama_level'],
        'harga' => $detail['harga'] / ($detail['bulan'] ?: 1),
    ];

    return view('kasir/transaksi_perpanjang', [
        'siswa'          => $siswa,
        'item'           => $item,
        'id_detail'      => $id_detail,
        'tanggal_mulai'  => $detail['tanggal_selesai']
    ]);
}
public function simpanPerpanjang()
{
    $id_detail = $this->request->getPost('id_detail');
    $bulan     = $this->request->getPost('bulan');
    $bayar     = $this->request->getPost('bayar');

    // 🔥 WAJIB JOIN KE TRANSAKSI
    $detail = $this->detailModel
        ->select('transaksi_detail.*, transaksi.id_siswa')
        ->join('transaksi', 'transaksi.id = transaksi_detail.id_transaksi')
        ->where('transaksi_detail.id', $id_detail)
        ->first();

    if(!$detail){
        return redirect()->back()->with('error','Data tidak ditemukan');
    }

    $today = date('Y-m-d');

    // ===============================
    // HITUNG TANGGAL PERPANJANGAN
    // ===============================
    if($detail['tanggal_selesai'] >= $today){
        $mulai = $detail['tanggal_selesai'];
    } else {
        $mulai = $today;
    }

    $selesai = date('Y-m-d', strtotime("+$bulan month", strtotime($mulai)));

    // ===============================
    // 1. UPDATE DURASI (UTAMA)
    // ===============================
    $this->detailModel->update($id_detail, [
        'tanggal_selesai' => $selesai
    ]);

    // ===============================
    // 2. HITUNG TOTAL
    // ===============================
    $harga_per_bulan = $detail['harga'] / ($detail['bulan'] ?: 1);
    $total = $harga_per_bulan * $bulan;

    // ===============================
    // 3. SIMPAN TRANSAKSI (RIWAYAT)
    // ===============================
    $transaksiId = $this->transaksiModel->insert([
        'id_siswa' => $detail['id_siswa'], // ✅ sekarang aman
        'tanggal' => date('Y-m-d'),
        'total_harga' => $total,
        'uang_bayar' => $bayar,
        'uang_kembali' => $bayar - $total,
        'biaya_pendaftaran' => 0,
        'tipe_transaksi' => 'perpanjang' // 🔥 biar kebaca di detail
    ]);

    // ===============================
    // 4. SIMPAN DETAIL TRANSAKSI
    // ===============================
    $this->detailModel->insert([
        'id_transaksi' => $transaksiId,
        'tipe' => 'kursus',
        'id_item' => $detail['id_item'],
        'harga' => $total,
        'bulan' => $bulan,
        'tanggal_mulai' => $mulai,
        'tanggal_selesai' => $selesai
    ]);

    return redirect()->to('/kasir/detail/'.$transaksiId)
        ->with('success','Berhasil diperpanjang');
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

        $d['harga_per_bulan'] = $d['bulan'] > 0 
            ? $d['harga'] / $d['bulan'] 
            : $d['harga'];

        $d['tanggal_mulai_f'] = !empty($d['tanggal_mulai']) 
            ? date('d M Y', strtotime($d['tanggal_mulai'])) 
            : '-';

        $d['tanggal_selesai_f'] = !empty($d['tanggal_selesai']) 
            ? date('d M Y', strtotime($d['tanggal_selesai'])) 
            : '-';

        // 🔥 FLAG PERPANJANG
        if(isset($transaksi['tipe_transaksi']) && $transaksi['tipe_transaksi'] == 'perpanjang'){
            $d['is_perpanjang'] = true;
        }else{
            $d['is_perpanjang'] = false;
        }
    }

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
public function detailKategori($id)
{
    // ambil kategori
    $kategori = $this->kategoriModel->find($id);

    if(!$kategori){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // ambil kursus sesuai kategori
    $kursus = $this->kursusModel
        ->where('id_kategori', $id)
        ->findAll();

    // 🔥 LOG
    $this->log('Melihat detail kategori: '.$kategori['nama_kategori']);

    return view('kasir/detail_kategori', [
        'kategori' => $kategori,
        'kursus'   => $kursus
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

    // 🔥 AMBIL 1 SISWA (FIX ERROR)
    $siswa = $this->siswaModel->find($id_siswa);

    if(!$siswa){
        return redirect()->back()->with('error','Siswa tidak ditemukan');
    }

    // 🔥 CEK APAKAH INI PERPANJANG
    $is_perpanjang = $this->request->getPost('is_perpanjang');

    // ================= SETTING =================
    $setting = $this->settingModel->first();
    $biaya_setting = $setting['biaya_pendaftaran'] ?? 0;

    // ================= HITUNG TOTAL =================
    $total = 0;

    foreach($items as $i){
        $bulan = (int)($i['bulan'] ?? 1);
        $harga = (int)$i['harga'];

        $total += $harga * $bulan;
    }

    // ================= BIAYA PENDAFTARAN =================
    $biaya_pendaftaran = 0;

    if(!$is_perpanjang && isset($siswa['sudah_daftar']) && $siswa['sudah_daftar'] == 0){
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

    // ================= SIMPAN DETAIL =================
    $levelModel = new \App\Models\LevelModel();

    foreach($items as $i){

        $level = $levelModel->find($i['id']);
        $bulan = (int)($i['bulan'] ?? 1);
        $harga = (int)$i['harga'];

        $this->detailModel->insert([
            'id_transaksi' => $id,
            'tipe' => 'kursus',
            'id_item' => $i['id'],
            'harga' => $harga * $bulan,
            'bulan' => $bulan,
            'tanggal_mulai' => $i['tanggal_mulai'] ?? date('Y-m-d'),
            'tanggal_selesai' => $i['tanggal_selesai'] ?? date('Y-m-d', strtotime('+1 month'))
        ]);

        // 🔥 KURANGI SLOT
        if($level){
            $this->kursusModel
                ->set('slot','slot-1',false)
                ->where('id', $level['id_kursus'])
                ->update();
        }
    }

    // ================= UPDATE SISWA =================
    if(!$is_perpanjang && isset($siswa['sudah_daftar']) && $siswa['sudah_daftar'] == 0){
        $this->siswaModel->update($id_siswa, [
            'sudah_daftar' => 1
        ]);
    }

    // ================= LOG =================
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
// public function detailPaket($id)
// {
//     $data['p'] = $this->paketModel->find($id);

//     $detail = $this->paketDetailModel
//         ->select('kursus.*')
//         ->join('kursus','kursus.id = paket_detail.id_kursus')
//         ->where('id_paket',$id)
//         ->findAll();

//     $data['kursus'] = $detail;

//     $this->log('Melihat detail paket ' . $data['p']['nama_paket']);

//     return view('kasir/detailpaket', $data);
// }
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