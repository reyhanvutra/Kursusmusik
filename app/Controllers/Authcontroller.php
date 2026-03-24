<?php

namespace App\Controllers;

use App\Models\UserModel;

class Authcontroller extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function proses_login()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = md5($this->request->getPost('password'));

        $user = $model->where('email', $email)
                      ->where('password', $password)
                      ->first();

        if($user){
            $session->set([
                'id_user' => $user['id'],
                'nama' => $user['nama'],
                'role' => $user['role'],
                'login' => true
            ]);

            // Redirect sesuai role
            if($user['role'] == 'admin'){
                return redirect()->to('/admin/dashboard');
            } elseif($user['role'] == 'kasir'){
                return redirect()->to('/kasir/dashboard');
            } else {
                return redirect()->to('/owner/dashboard');
            }

        } else {
            return redirect()->back()->with('error','Email atau Password salah');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}