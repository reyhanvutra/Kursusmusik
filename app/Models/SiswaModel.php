<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'no_hp',
        'alamat',
        'tanggal_daftar',
        'created_at',
        'sudah_daftar',
        'aktif'
    ];
}