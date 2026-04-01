<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';

protected $allowedFields = [
    'nama_pembeli',
    'no_hp',
    'total_harga',
    'uang_bayar',
    'uang_kembali',
    'tanggal',
    'biaya_pendaftaran',
    'id_siswa'
];
}