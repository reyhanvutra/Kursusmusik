<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriKursusModel extends Model
{
    protected $table = 'kategori_kursus';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama_kategori','deskripsi','gambar'];

    protected $useTimestamps = true;
}