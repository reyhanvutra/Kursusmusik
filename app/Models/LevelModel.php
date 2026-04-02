<?php

namespace App\Models;

use CodeIgniter\Model;

class LevelModel extends Model
{
    protected $table = 'level';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_kursus',
        'nama_level',
        'deskripsi',
        'urutan',
        'harga',
        'pertemuan'
    ];
}