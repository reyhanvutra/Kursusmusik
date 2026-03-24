<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
class Kasir extends BaseController
{
    public function dashboard()
    {
        return view('kasir/dashboard');
    }
}