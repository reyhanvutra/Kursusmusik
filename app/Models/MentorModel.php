<?php

namespace App\Models;
use CodeIgniter\Model;

class MentorModel extends Model
{
    protected $table = 'mentor';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama','no_hp','keahlian','aktif'
    ];
}