<?php

namespace App\Controllers\Kasir;

use App\Models\KursusModel;
use App\Models\PaketModel;
use App\Models\TransaksiModel;
use App\Models\TransaksiDetailModel;
use App\Models\PaketDetailModel;
use App\Models\LogActivityModel;
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

    public function __construct()
    {
        $this->kursusModel = new KursusModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel = new TransaksiDetailModel();
        $this->paketDetailModel = new PaketDetailModel();
        $this->logActivityModel = new LogActivityModel();
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
            ->orderBy('id','DESC')
            ->findAll(5);

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
   public function detail($id)
{
    $transaksi = $this->transaksiModel->find($id);

    // 🔥 validasi
    if(!$transaksi){
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // ambil detail transaksi
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

        // 🔥 harga per bulan (khusus kursus)
        if($d['tipe'] == 'kursus'){
            $d['harga_per_bulan'] = $d['harga'] / $d['bulan'];
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
        return view('kasir/transaksi');
    }

    // ================= SIMPAN =================
    public function simpan()
    {
        $items = json_decode($this->request->getPost('items'), true);

        if(empty($items)){
            return redirect()->back()->with('error','Pilih item dulu');
        }

        $total = 0;
        foreach($items as $i){
            $total += $i['harga'];
        }

        $bayar = $this->request->getPost('bayar');

        if($bayar < $total){
            return redirect()->back()->with('error','Uang kurang');
        }

        $id = $this->transaksiModel->insert([
            'nama_pembeli' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('nohp'),
            'total_harga' => $total,
            'uang_bayar' => $bayar,
            'uang_kembali' => $bayar - $total,
            'tanggal' => date('Y-m-d')
        ]);

      foreach($items as $i){

    $this->detailModel->insert([
        'id_transaksi' => $id,
        'tipe' => $i['tipe'],
        'id_item' => $i['id'],
        'harga' => $i['harga'],
        'bulan' => $i['bulan'] ?? 1 // 🔥 default 1 kalau kosong
    ]);

    if($i['tipe'] == 'kursus'){
        $this->kursusModel->set('slot','slot-1',false)
            ->where('id',$i['id'])
            ->update();
    }
}
$this->log('Menyimpan transaksi dengan pembeli ' . $this->request->getPost('nama') . ' dan total ' . $total);

        // 🔥 langsung cetak PDF
       return redirect()->to('/kasir/detail/'.$id);
    }

    // ================= CETAK PDF =================
    public function cetak($id)
    {
        $transaksi = $this->transaksiModel->find($id);

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

        $this->log('Mencetak struk transaksi atas nama ' . $transaksi['nama_pembeli'] . ' dengan total ' . $transaksi['total_harga']);

        $html = view('kasir/struk_pdf', [
            't' => $transaksi,
            'detail' => $detail
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0,0,226.77,600]); // ukuran struk
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
}