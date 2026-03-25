<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiDetailModel extends Model
{
    protected $table = 'transaksi_detail';

    protected $allowedFields = [
        'id_transaksi',
        'tipe',
        'id_item',
        'harga'
    ];
}