<?php

namespace App\Models;

use CodeIgniter\Model;

class LogActivityModel extends Model
{
    protected $table = 'log_activity';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'nama_user',
        'role',
        'aktivitas',
        'ip_address',
        'created_at',
        'updated_at'
    ];

    // 🔥 AUTO TIMESTAMP
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $dateFormat = 'datetime';

    // ================= FUNCTION SIMPAN LOG =================
    public function log($aktivitas)
    {
        return $this->insert([
            'user_id'    => session()->get('id'),
            'nama_user'  => session()->get('nama'),
            'role'       => session()->get('role'),
            'aktivitas'  => $aktivitas,
            'ip_address' => service('request')->getIPAddress()
        ]);
    }

    // ================= FILTER LOG =================
    public function getLog($filter = [])
    {
        $builder = $this->orderBy('id', 'DESC');

        if (!empty($filter['nama_user'])) {
            $builder->like('nama_user', $filter['nama_user']);
        }

        if (!empty($filter['role'])) {
            $builder->where('role', $filter['role']);
        }

        if (!empty($filter['tanggal'])) {
            $builder->where('DATE(created_at)', $filter['tanggal']);
        }

        return $builder;
    }
}