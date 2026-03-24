<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;

class Owner extends BaseController
{
    public function dashboard()
    {
        return view('owner/dashboard');
    }
}