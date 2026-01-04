<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
    $routes->get('/', 'Login::index');
    $routes->get('Login','Login::index');
    $routes->get('login', 'Login::index');
    $routes->post('login/proses', 'Login::proses');
    $routes->get('logout', 'Login::logout');

$routes->group('dashboard', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Dashboard::index');
});

$routes->group('ProdukKasir', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'ProdukKasir::index');
});


$routes->group('kasir', ['filter' => 'auth'], function($routes){
    $routes->get('/','Kasir::index');
    $routes->get('transaksi', 'Kasir::transaksi');
    $routes->post('bayar', 'Kasir::bayar');
    $routes->post('simpan', 'Kasir::simpan');
     $routes->get('nota', 'Kasir::nota');
    $routes->get('nota/(:num)', 'Kasir::nota/$1');
});

$routes->group('UserAdmin', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'UserAdmin::index');
    $routes->post('updateProfil', 'UserAdmin::updateProfil');
    $routes->post('updateFoto', 'UserAdmin::updateFoto');
});

$routes->group('UserKasir', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'UserKasir::index');
    $routes->post('updateProfil', 'UserKasir::updateProfil');
    $routes->post('updatePassword', 'UserKasir::updatePassword');
    $routes->post('updateFoto', 'UserAdmin::updateFoto');
});

$routes->group('KelolaUser', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'KelolaUser::index');
    $routes->post('tambah', 'KelolaUser::tambah'); 
    $routes->post('ubah/(:num)', 'KelolaUser::ubah/$1');
    $routes->get('nonaktif/(:num)', 'KelolaUser::nonaktif/$1');
    $routes->get('aktif/(:num)', 'KelolaUser::aktif/$1');
    $routes->get('hapus/(:num)', 'KelolaUser::hapus/$1');
});


$routes->group('kasir', ['filter' => 'auth'], function($routes){ 
    $routes->post('tambah', 'KasirController::tambah');
    $routes->post('ubah/(:num)', 'KasirController::ubah/$1');
    $routes->get('hapus/(:num)', 'KasirController::hapus/$1');
    $routes->get('aktif/(:num)', 'KasirController::aktif/$1');
    $routes->get('nonaktif/(:num)', 'KasirController::nonaktif/$1');
});

$routes->group('LaporanAdmin', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'LaporanAdmin::index');
    $routes->get('pdf', 'LaporanAdmin::pdf');
    $routes->get('detail/(:num)', 'LaporanAdmin::detail/$1'); 
});

$routes->group('laporankasir', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'LaporanKasir::index');
    $routes->get('pdf', 'LaporanKasir::pdf');
    $routes->get('detail/(:num)', 'LaporanKasir::detail/$1'); 
});

$routes->group('RiwayatTransaksi', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'RiwayatTransaksi::index');
    $routes->get('riwayattransaksi/detail/(:num)', 'RiwayatTransaksi::detail/$1');
});

$routes->group('detailOrder', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'DetailOrder::index');
    $routes->add('tambah', 'DetailOrder::tambah');
    $routes->add('ubah/(:any)', 'DetailOrder::ubah/$1');
    $routes->get('hapus/(:any)', 'DetailOrder::hapus/$1');
});

$routes->group('detailpembelian', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'DetailPembelian::index');
    $routes->add('tambah', 'DetailPembelian::tambah');
    $routes->add('ubah/(:any)', 'DetailPembelian::ubah/$1');
    $routes->get('hapus/(:any)', 'DetailPembelian::hapus/$1');
});

$routes->group('discount', ['filter' => 'auth'], function($routes){
    $routes->get('discount', 'Discount::index');
    $routes->get('/', 'Discount::index');
    $routes->post('tambah', 'Discount::tambah');
    $routes->post('ubah/(:num)', 'Discount::ubah/$1');
    $routes->post('hapus', 'Discount::hapus');
});

$routes->group('merek', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Merek::index');
    $routes->post('/', 'Merek::index');
    $routes->post('ubah/(:any)', 'Merek::ubah/$1');
    $routes->get('hapus/(:any)', 'Merek::hapus/$1');
});

$routes->group('Orders', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Orders::index');
    $routes->add('tambah', 'Orders::tambah');
    $routes->add('ubah/(:any)', 'Orders::ubah/$1');
    $routes->get('hapus/(:any)', 'Orders::hapus/$1');
});

$routes->group('pembayaran', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Pembayaran::index');
    $routes->add('tambah', 'Pembayaran::tambah');
    $routes->add('ubah/(:any)', 'Pembayaran::ubah/$1');
    $routes->get('hapus/(:any)', 'Pembayaran::hapus/$1');
});

$routes->group('pembelian', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Pembelian::index');
    $routes->add('tambah', 'Pembelian::tambah');
    $routes->add('ubah/(:any)', 'Pembelian::ubah/$1');
    $routes->get('hapus/(:any)', 'Pembelian::hapus/$1');
});

$routes->group('produk', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Produk::index');
});

$routes->group('profiltoko', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'ProfilToko::index');
    $routes->post('simpan', 'ProfilToko::simpan');
});

$routes->group('supplier', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'Supplier::index');
    $routes->post('tambah', 'Supplier::tambah');
    $routes->post('ubah/(:num)', 'Supplier::ubah/$1');
    $routes->post('hapus', 'Supplier::hapus');
    $routes->get('detail/(:num)', 'Supplier::detail/$1');
    $routes->post('tambah-produk', 'Supplier::tambahProduk');
    $routes->post('update-produk', 'Supplier::updateProduk');
    $routes->post('hapus-produk', 'Supplier::hapusProduk');
});

$routes->group('user', ['filter' => 'auth'], function($routes){
    $routes->get('/', 'User::index');
    $routes->add('tambah', 'User::tambah');
    $routes->add('ubah/(:any)', 'User::ubah/$1');
    $routes->get('hapus/(:any)', 'User::hapus/$1');
});
