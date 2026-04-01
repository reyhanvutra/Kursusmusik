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

        $kursus_aktif = $this->kursusModel
            ->where('slot >', 0)
            ->countAllResults();

        $pendapatan = $this->transaksiModel
            ->selectSum('total_harga')
            ->where('tanggal', $today)
            ->first();

        $total_transaksi = $this->transaksiModel
            ->where('tanggal', $today)
            ->countAllResults();

       $transaksi = $this->transaksiModel
    ->select('transaksi.*, siswa.nama as nama_pembeli')
    ->join('siswa', 'siswa.id = transaksi.id_siswa')
    ->findAll();

        foreach($transaksi as &$t){

            $detail = $this->detailModel
                ->where('id_transaksi', $t['id'])
                ->findAll();

            $items = [];

            foreach($detail as $d){

                if($d['tipe'] == 'kursus'){
                    $k = $this->kursusModel->find($d['id_item']);
                    $items[] = $k ? $k['nama_kursus'] : 'Kursus dihapus';
                }else{
                    $p = $this->paketModel->find($d['id_item']);
                    $items[] = $p ? $p['nama_paket'] : 'Paket dihapus';
                }
            }

            $t['items'] = $items;
        }
            // 🔥 LOG
            $this->log('Masuk dashboard kasir');

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
    // 🔥 JOIN ke siswa biar dapat nama & no_hp
    $transaksi = $this->transaksiModel
        ->select('transaksi.*, siswa.nama as nama_pembeli, siswa.no_hp')
        ->join('siswa', 'siswa.id = transaksi.id_siswa')
        ->where('transaksi.id', $id)
        ->first();

    // 🔥 validasi
    if(!$transaksi){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // 🔥 ambil detail transaksi
    $detail = $this->detailModel
        ->where('id_transaksi', $id)
        ->findAll();

    // 🔥 isi nama + info tambahan
    foreach($detail as &$d){

        if($d['tipe'] == 'kursus'){

            $k = $this->kursusModel->find($d['id_item']);

            $d['nama']   = $k['nama_kursus'] ?? 'Kursus dihapus';
            $d['tipe_label'] = 'Kursus';

        }else{

            $p = $this->paketModel->find($d['id_item']);

            $d['nama']   = $p['nama_paket'] ?? 'Paket dihapus';
            $d['tipe_label'] = 'Paket';
        }

        // 🔥 pastikan bulan tidak null
        $d['bulan'] = $d['bulan'] ?? 1;

        // 🔥 harga per bulan
        if($d['tipe'] == 'kursus'){
            $d['harga_per_bulan'] = $d['harga'] / ($d['bulan'] ?: 1);
        }else{
            $d['harga_per_bulan'] = $d['harga'];
        }
    }

    // 🔥 LOG
    $this->log('Melihat detail transaksi ' . $id);

    return view('kasir/detail', [
        't'      => $transaksi,
        'detail' => $detail
    ]);
}

    // ================= PILIH =================
  public function pilih()
{
    $kursus = $this->kursusModel->findAll();
    $paket  = $this->paketModel->findAll();

    foreach($paket as &$p){

        $detail = $this->paketDetailModel
            ->select('kursus.nama_kursus, kursus.hari')
            ->join('kursus','kursus.id = paket_detail.id_kursus')
            ->where('id_paket', $p['id'])
            ->findAll();

        // list kursus
        $namaKursus = array_column($detail, 'nama_kursus');
        $p['list_kursus'] = implode(', ', $namaKursus);

        // hari paket
        $semuaHari = [];
        foreach($detail as $d){
            foreach(explode(',', $d['hari']) as $h){
                $semuaHari[] = trim($h);
            }
        }

        $p['hari'] = implode(', ', array_unique($semuaHari));
    }
    
     // 🔥 LOG
     $this->log('Masuk halaman pilih item untuk transaksi baru');

    return view('kasir/pilih', [
        'kursus' => $kursus,
        'paket'  => $paket
    ]);
}

    public function transaksi()
{
    return view('kasir/transaksi', [
        'siswa' => $this->siswaModel->findAll(),
        'setting' => (new \App\Models\SettingModel())->first()
    ]);
}

public function simpan()
{
    $items = json_decode($this->request->getPost('items'), true);

    if(empty($items)){
        return redirect()->back()->with('error','Pilih item dulu');
    }

    $id_siswa = $this->request->getPost('id_siswa');

    if(!$id_siswa){
        return redirect()->back()->with('error','Pilih siswa dulu');
    }

    // 🔥 AMBIL DATA SISWA
    $siswa = $this->siswaModel->find($id_siswa);

    if(!$siswa){
        return redirect()->back()->with('error','Siswa tidak ditemukan');
    }

    // 🔥 AMBIL SETTING
    $settingModel = new \App\Models\SettingModel();
    $setting = $settingModel->first();

    $biaya_setting = $setting['biaya_pendaftaran'] ?? 0;

    // 🔥 HITUNG TOTAL ITEM
    $total = 0;
    foreach($items as $i){
        $total += $i['harga'];
    }

    // 🔥 CEK BIAYA PENDAFTARAN
    $biaya_pendaftaran = 0;

    if(isset($siswa['sudah_daftar']) && $siswa['sudah_daftar'] == 0){
        $biaya_pendaftaran = $biaya_setting;
    }

    // 🔥 TOTAL FINAL
    $total_final = $total + $biaya_pendaftaran;

    $bayar = $this->request->getPost('bayar');

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

    // 🔥 VALIDASI INSERT
    if(!$id){
        return redirect()->back()->with('error','Gagal simpan transaksi');
    }

    // ================= DETAIL =================
    foreach($items as $i){

       $this->detailModel->insert([
    'id_transaksi' => $id,
    'tipe' => $i['tipe'],
    'id_item' => $i['id'],
    'harga' => $i['harga'],
    'bulan' => $i['bulan'] ?? 1,
    'tanggal_mulai' => $i['tanggal_mulai'] ?? null,
    'tanggal_selesai' => $i['tanggal_selesai'] ?? null,
]);

        // 🔥 KURANGI SLOT
        if($i['tipe'] == 'kursus'){
            $this->kursusModel->set('slot','slot-1',false)
                ->where('id',$i['id'])
                ->update();
        }
    }

    // ================= UPDATE SISWA =================
    if(isset($siswa['sudah_daftar']) && $siswa['sudah_daftar'] == 0){

        $this->siswaModel->update($id_siswa, [
            'sudah_daftar' => 1
        ]);
    }

    // 🔥 LOG
    $this->log('Transaksi oleh siswa '.$siswa['nama'].' total '.$total_final);

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
            $k = $this->kursusModel->find($d['id_item']);
            $d['nama'] = $k['nama_kursus'] ?? 'Kursus dihapus';
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
    $data['k'] = $this->kursusModel->find($id);
    return view('kasir/detailkursus', $data);
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

    $id = $this->siswaModel->insert([
        'nama' => $data['nama'],
        'no_hp' => $data['no_hp'],
        'alamat' => $data['alamat'],
        'tanggal_daftar' => date('Y-m-d')
    ]);

    return $this->response->setJSON([
        'status' => 'success', 
        'id' => $id,
        'nama' => $data['nama'],
        'no_hp' => $data['no_hp'],
        'alamat' => $data['alamat']
    ]);
}

}