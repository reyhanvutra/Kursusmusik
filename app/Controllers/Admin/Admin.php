<?php

namespace App\Controllers\Admin;
use App\Models\UserModel;
use App\Models\KursusModel;
use App\Controllers\BaseController;

class Admin extends BaseController
{
        protected $userModel;
        protected $kursusModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->kursusModel = new KursusModel();
    }
    public function dashboard()
    {
           
    $totalUser = $this->userModel->countAll();
     $totalKursus = $this->kursusModel->countAll();
        // Dummy data
        $data = [
            'total_kursus' => $totalKursus,
            'total_user' => $totalUser,
            'total_transaksi' => 20
        ];

        return view('admin/dashboard', $data);
    }
    public function user()
    {
        $data['users'] = $this->userModel->findAll();
        return view('admin/user/index', $data);
    }
    
    public function tambah_user()
    {
        return view('admin/user/tambah');
    }

    public function simpan_user()
    {
        $this->userModel->save([
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => md5($this->request->getPost('password')),
            'role' => $this->request->getPost('role'),
        ]);

        return redirect()->to('/admin/user');
    }

    public function edit_user($id)
    {
        $data['user'] = $this->userModel->find($id);
        return view('admin/user/edit', $data);
    }

    public function update_user($id)
    {
        $this->userModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ]);

        return redirect()->to('/admin/user');
    }

    public function hapus_user($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/user');
    }

    // Kursus
    public function kursus()
{
    $data['kursus'] = $this->kursusModel->findAll();
    return view('admin/kursus/index', $data);
}
public function tambah_kursus()
{
    return view('admin/kursus/tambah');
}
public function simpan_kursus()
{
    $file = $this->request->getFile('gambar');

    $namaGambar = $file->getRandomName();
    $file->move('uploads/', $namaGambar);

    $this->kursusModel->save([
        'nama_kursus' => $this->request->getPost('nama_kursus'),
        'harga' => $this->request->getPost('harga'),
        'instruktur' => $this->request->getPost('instruktur'),
        'durasi' => $this->request->getPost('durasi'),
        'slot' => $this->request->getPost('slot'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'gambar' => $namaGambar
    ]);

    return redirect()->to('/admin/kursus');
}
public function edit_kursus($id)
{
    $data['kursus'] = $this->kursusModel->find($id);
    return view('admin/kursus/edit', $data);
}
public function update_kursus($id)
{
    $this->kursusModel->update($id, [
        'nama_kursus' => $this->request->getPost('nama_kursus'),
        'harga' => $this->request->getPost('harga'),
        'instruktur' => $this->request->getPost('instruktur'),
        'durasi' => $this->request->getPost('durasi'),
        'slot' => $this->request->getPost('slot'),
        'deskripsi' => $this->request->getPost('deskripsi'),
    ]);

    return redirect()->to('/admin/kursus');
}
public function hapus_kursus($id)
{
    $this->kursusModel->delete($id);
    return redirect()->to('/admin/kursus');
}
}