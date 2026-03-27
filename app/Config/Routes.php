<?php

use CodeIgniter\Router\RouteCollection;
use app\Filters\AuthFilter;

/**
 * @var RouteCollection $routes
 */
// LOGIN
$routes->get('/', 'Authcontroller::login');
$routes->post('/proses_login', 'Authcontroller::proses_login');
$routes->get('/logout', 'Authcontroller::logout');

// ADMIN
$routes->group('admin', ['filter' => ['auth','role:admin']], function($routes){

    $routes->get('dashboard', 'Admin\Admin::dashboard');

    // USER
    $routes->get('user', 'Admin\Admin::user');
    $routes->get('user/tambah', 'Admin\Admin::tambah_user');
    $routes->post('user/simpan', 'Admin\Admin::simpan_user');
    $routes->get('user/edit/(:num)', 'Admin\Admin::edit_user/$1');
    $routes->post('user/update/(:num)', 'Admin\Admin::update_user/$1');
    $routes->get('user/hapus/(:num)', 'Admin\Admin::hapus_user/$1');

    // kursus
     $routes->get('kursus', 'Admin\Admin::kursus');
    $routes->get('kursus/tambah', 'Admin\Admin::tambah_kursus');
    $routes->post('kursus/simpan', 'Admin\Admin::simpan_kursus');
    $routes->get('kursus/edit/(:num)', 'Admin\Admin::edit_kursus/$1');
    $routes->post('kursus/update/(:num)', 'Admin\Admin::update_kursus/$1');
    $routes->get('kursus/hapus/(:num)', 'Admin\Admin::hapus_kursus/$1');

    // paket
    $routes->get('paket', 'Admin\Admin::paket');
    $routes->get('paket/tambah', 'Admin\Admin::tambah_paket');
    $routes->post('paket/simpan', 'Admin\Admin::simpan_paket');
    $routes->get('paket/edit/(:num)', 'Admin\Admin::edit_paket/$1');
    $routes->post('paket/update/(:num)', 'Admin\Admin::update_paket/$1');
    $routes->get('paket/hapus/(:num)', 'Admin\Admin::hapus_paket/$1');
});
// KASIR
$routes->group('kasir', ['filter' => ['auth', 'role:kasir']], function($routes){
   $routes->get('dashboard', 'Kasir\Kasir::dashboard');
    $routes->get('pilih', 'Kasir\Kasir::pilih');
    $routes->get('transaksi', 'Kasir\Kasir::transaksi');
    $routes->post('simpan', 'Kasir\Kasir::simpan');
    $routes->get('detail/(:num)', 'Kasir\Kasir::detail/$1');
    $routes->get('cetak/(:num)', 'Kasir\Kasir::cetak/$1');
    $routes->get('detail/kursus/(:num)', 'Kasir\Kasir::detailKursus/$1');
    $routes->get('detail/paket/(:num)', 'Kasir\Kasir::detailPaket/$1');

});
// OWNER
$routes->group('owner', ['filter' => ['auth', 'role:owner']], function($routes){
    $routes->get('dashboard', 'Owner\Owner::dashboard');
});
