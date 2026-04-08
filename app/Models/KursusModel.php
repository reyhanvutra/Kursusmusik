<?php

namespace App\Models;

use CodeIgniter\Model;

class KursusModel extends Model
{
    protected $table = 'kursus';
    protected $primaryKey = 'id';

 protected $allowedFields = [
    'nama_kursus',
    'deskripsi',
    'id_kategori'
];
}