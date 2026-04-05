<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\KursusModel;
use App\Models\LogActivityModel;
use Dompdf\Dompdf;

class Owner extends BaseController
{
    protected $transaksiModel;
    protected $kursusModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->kursusModel = new KursusModel();
    }

    // ================= DASHBOARD =================
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

        // 👨‍🎓 siswa aktif (FIX ❗)
        $siswa_aktif = $this->transaksiModel
            ->select('COUNT(DISTINCT id_siswa) as total')
            ->first();

        // 📊 grafik per bulan
        $grafik = $this->transaksiModel
            ->select('MONTH(tanggal) as bulan, SUM(total_harga) as total')
            ->groupBy('MONTH(tanggal)')
            ->orderBy('bulan', 'ASC')
            ->findAll();

        return view('owner/dashboard', [
            'pendapatan' => $pendapatan['total_harga'] ?? 0,
            'total_transaksi' => $total_transaksi,
            'kursus_aktif' => $kursus_aktif,
            'siswa_aktif' => $siswa_aktif['total'] ?? 0,
            'grafik' => $grafik
        ]);
    }

   public function laporan()
{
    $tanggal = $this->request->getGet('tanggal');
    $bulan   = $this->request->getGet('bulan');
    $tahun   = $this->request->getGet('tahun');
    $nama    = $this->request->getGet('nama');

    $builder = $this->transaksiModel
        ->select('
            transaksi.*,
            siswa.nama as nama_siswa,
            kursus.nama_kursus,
            level.nama_level,
            kategori_kursus.nama_kategori
        ')
        ->join('siswa', 'siswa.id = transaksi.id_siswa', 'left')
        ->join('transaksi_detail', 'transaksi_detail.id_transaksi = transaksi.id')
        ->join('level', 'level.id = transaksi_detail.id_item')
        ->join('kursus', 'kursus.id = level.id_kursus')
        ->join('kategori_kursus', 'kategori_kursus.id = kursus.id_kategori', 'left');

    // FILTER
    if ($tanggal) {
        $builder->where('DATE(transaksi.tanggal)', $tanggal);
    }

    if ($bulan) {
        $builder->where('MONTH(transaksi.tanggal)', $bulan);
    }

    if ($tahun) {
        $builder->where('YEAR(transaksi.tanggal)', $tahun);
    }

    if ($nama) {
        $builder->like('siswa.nama', $nama);
    }

    $builder->orderBy('transaksi.tanggal', 'DESC');

    $transaksi = $builder->paginate(10);
    $pager = $this->transaksiModel->pager;

    // TOTAL
    $builderTotal = $this->transaksiModel->selectSum('total_harga');

    if ($tanggal) {
        $builderTotal->where('DATE(tanggal)', $tanggal);
    }
    if ($bulan) {
        $builderTotal->where('MONTH(tanggal)', $bulan);
    }
    if ($tahun) {
        $builderTotal->where('YEAR(tanggal)', $tahun);
    }

    $total = $builderTotal->first()['total_harga'] ?? 0;

    return view('owner/laporan', [
        'transaksi' => $transaksi,
        'pager' => $pager,
        'total' => $total
    ]);
}

   public function pdf()
{
    $tanggal = $this->request->getGet('tanggal');
    $bulan   = $this->request->getGet('bulan');
    $tahun   = $this->request->getGet('tahun');
    $nama    = $this->request->getGet('nama');

    $builder = $this->transaksiModel
        ->select('
            transaksi.*,
            siswa.nama as nama_siswa,
            kursus.nama_kursus,
            level.nama_level,
            kategori_kursus.nama_kategori
        ')
        ->join('siswa', 'siswa.id = transaksi.id_siswa', 'left')
        ->join('transaksi_detail', 'transaksi_detail.id_transaksi = transaksi.id')
        ->join('level', 'level.id = transaksi_detail.id_item')
        ->join('kursus', 'kursus.id = level.id_kursus')
        ->join('kategori_kursus', 'kategori_kursus.id = kursus.id_kategori', 'left');

    if ($tanggal) {
        $builder->where('DATE(transaksi.tanggal)', $tanggal);
    }
    if ($bulan) {
        $builder->where('MONTH(transaksi.tanggal)', $bulan);
    }
    if ($tahun) {
        $builder->where('YEAR(transaksi.tanggal)', $tahun);
    }
    if ($nama) {
        $builder->like('siswa.nama', $nama);
    }

    $data['transaksi'] = $builder->findAll();

    $html = view('owner/laporan_pdf', $data);

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("laporan.pdf");
}

  public function dataKursus()
{
    $nama = $this->request->getGet('nama');

    $builder = $this->transaksiModel
        ->select('
            kursus.nama_kursus,
            kategori_kursus.nama_kategori,
            level.nama_level,
            COUNT(transaksi_detail.id) as total_terjual,
            SUM(transaksi_detail.harga) as total_pendapatan
        ')
        ->join('transaksi_detail', 'transaksi_detail.id_transaksi = transaksi.id')
        ->join('level', 'level.id = transaksi_detail.id_item')
        ->join('kursus', 'kursus.id = level.id_kursus')
        ->join('kategori_kursus', 'kategori_kursus.id = kursus.id_kategori', 'left')
        ->groupBy('level.id');

    if ($nama) {
        $builder->like('kursus.nama_kursus', $nama);
    }

    $data = $builder->paginate(10);
    $pager = $this->transaksiModel->pager;

    return view('owner/datakursus', [
        'data' => $data,
        'pager' => $pager
    ]);
}
   public function dataKursusPDF()
{
    $nama = $this->request->getGet('nama');

    $builder = $this->transaksiModel
        ->select('
            kursus.nama_kursus,
            kategori_kursus.nama_kategori,
            level.nama_level,
            COUNT(transaksi_detail.id) as total_terjual,
            SUM(transaksi_detail.harga) as total_pendapatan
        ')
        ->join('transaksi_detail', 'transaksi_detail.id_transaksi = transaksi.id')
        ->join('level', 'level.id = transaksi_detail.id_item')
        ->join('kursus', 'kursus.id = level.id_kursus')
        ->join('kategori_kursus', 'kategori_kursus.id = kursus.id_kategori', 'left')
        ->groupBy('level.id');

    if ($nama) {
        $builder->like('kursus.nama_kursus', $nama);
    }

    $data['data'] = $builder->findAll();

    $html = view('owner/datakursuspdf', $data);

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream("laporan_kursus.pdf");
}

    // ================= LOG ACTIVITY =================
    public function log()
    {
        $nama = $this->request->getGet('nama');
        $role = $this->request->getGet('role');

        $logModel = new LogActivityModel();

        $builder = $logModel;

        if ($nama) {
            $builder->like('nama_user', $nama);
        }

        if ($role) {
            $builder->where('role', $role);
        }

        $data = $builder->orderBy('created_at', 'DESC')->paginate(10);
        $pager = $logModel->pager;

        return view('owner/log_activity', [
            'data' => $data,
            'pager' => $pager
        ]);
    }
}