<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\KursusModel;

class Owner extends BaseController  
{
    protected $transaksiModel;
    protected $kursusModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->kursusModel = new KursusModel();
    }

   public function dashboard()
{
    $bulan = date('m');
    $tahun = date('Y');

    // 💰 pendapatan bulan ini
    $pendapatan = $this->transaksiModel
        ->selectSum('total_harga')
        ->where('MONTH(tanggal)', $bulan)
        ->where('YEAR(tanggal)', $tahun)
        ->first();

    // 🧾 total transaksi bulan ini
    $total_transaksi = $this->transaksiModel
        ->where('MONTH(tanggal)', $bulan)
        ->where('YEAR(tanggal)', $tahun)
        ->countAllResults();

    // 🎵 kursus aktif
    $kursus_aktif = $this->kursusModel
        ->where('slot >', 0)
        ->countAllResults();

    // 👨‍🎓 siswa aktif (dari transaksi)
    $siswa_aktif = $this->transaksiModel
        ->select('COUNT(DISTINCT nama_pembeli) as total')
        ->first();

    // 📊 grafik per bulan
    $grafik = $this->transaksiModel
        ->select('MONTH(tanggal) as bulan, SUM(total_harga) as total')
        ->groupBy('MONTH(tanggal)')
        ->orderBy('bulan','ASC')
        ->findAll();

    return view('owner/dashboard', [
        'pendapatan' => $pendapatan['total_harga'] ?? 0,
        'total_transaksi' => $total_transaksi,
        'kursus_aktif' => $kursus_aktif,
        'siswa_aktif' => $siswa_aktif['total'] ?? 0,
        'grafik' => $grafik
    ]);
}
}