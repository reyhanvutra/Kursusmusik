<?php

namespace App\Controllers\Kasir;

use App\Models\KursusModel;
use App\Models\PaketModel;
use App\Models\TransaksiModel;
use App\Models\TransaksiDetailModel;
use App\Controllers\BaseController;

class Kasir extends BaseController
{
    protected $kursusModel;
    protected $paketModel;
    protected $transaksiModel;
    protected $detailModel;

    public function __construct()
    {
        $this->kursusModel = new KursusModel();
        $this->paketModel = new PaketModel();
        $this->transaksiModel = new TransaksiModel();
        $this->detailModel = new TransaksiDetailModel();
    }
public function dashboard()
{
    $today = date('Y-m-d');

    // kursus aktif
    $kursus_aktif = $this->kursusModel
        ->where('slot >', 0)
        ->countAllResults();

    // pendapatan hari ini
    $pendapatan = $this->transaksiModel
        ->selectSum('total_harga')
        ->where('tanggal', $today)
        ->first();

    // total transaksi hari ini
    $total_transaksi = $this->transaksiModel
        ->where('tanggal', $today)
        ->countAllResults();

    // ambil transaksi terbaru
    $transaksi = $this->transaksiModel
        ->orderBy('id','DESC')
        ->findAll(5);

    // ambil detail item
    foreach($transaksi as &$t){

        $detail = $this->detailModel
            ->where('id_transaksi', $t['id'])
            ->findAll();

        $items = [];

        foreach($detail as $d){
            if($d['tipe'] == 'kursus'){
                $k = $this->kursusModel->find($d['id_item']);
                $items[] = $k['nama_kursus'];
            }else{
                $p = $this->paketModel->find($d['id_item']);
                $items[] = $p['nama_paket'];
            }
        }

        $t['items'] = $items;
    }

    $data = [
        'kursus_aktif' => $kursus_aktif,
        'pendapatan_hari_ini' => $pendapatan['total_harga'] ?? 0,
        'total_transaksi_hari_ini' => $total_transaksi,
        'transaksi' => $transaksi
    ];

    return view('kasir/dashboard', $data);
}
    // 🔹 halaman pilih kursus/paket
    public function pilih()
    {
        $data['kursus'] = $this->kursusModel->findAll();
        $data['paket'] = $this->paketModel->findAll();

        return view('kasir/pilih', $data);
    }

    // 🔹 halaman transaksi
    public function transaksi()
    {
        return view('kasir/transaksi');
    }

    // 🔹 simpan transaksi
    public function simpan()
    {
        $items = json_decode($this->request->getPost('items'), true);

        $total = 0;
        foreach($items as $i){
            $total += $i['harga'];
        }

        $bayar = $this->request->getPost('bayar');
        $kembali = $bayar - $total;

        $idTransaksi = $this->transaksiModel->insert([
            'nama_pembeli' => $this->request->getPost('nama'),
            'no_hp' => $this->request->getPost('nohp'),
            'total_harga' => $total,
            'uang_bayar' => $bayar,
            'uang_kembali' => $kembali,
            'tanggal' => date('Y-m-d')
        ]);

        // simpan detail
        foreach($items as $i){
            $this->detailModel->insert([
                'id_transaksi' => $idTransaksi,
                'tipe' => $i['tipe'],
                'id_item' => $i['id'],
                'harga' => $i['harga']
            ]);

            // kurangi slot
            if($i['tipe'] == 'kursus'){
                $this->kursusModel->set('slot', 'slot-1', false)
                    ->where('id', $i['id'])
                    ->update();
            }
        }

        return redirect()->to('/kasir/dashboard');
    }
}