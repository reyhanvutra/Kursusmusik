<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketDetailModel extends Model
{
    protected $table = 'paket_detail';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_paket',
        'id_kursus'
    ];
}