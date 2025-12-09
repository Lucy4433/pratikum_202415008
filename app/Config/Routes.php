<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

    $routes->get('Login','Login::index');
    $routes->get('login', 'Login::index');
    $routes->post('login/proses', 'Login::proses');
    $routes->get('logout', 'Login::logout');

    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard','Dashboard::index');

$routes->group('ProdukKasir', function($routes){
    $routes->get('/', 'ProdukKasir::index');
});

$routes->group('kasir', function($routes){
    $routes->get('/','Kasir::index');
    $routes->get('transaksi', 'Kasir::transaksi');
    $routes->post('bayar', 'Kasir::bayar');
    $routes->post('simpan', 'Kasir::simpan');
     $routes->get('nota', 'Kasir::nota');
    $routes->get('nota/(:num)', 'Kasir::nota/$1');
});

$routes->group('UserAdmin', function($routes){
    $routes->get('/', 'UserAdmin::index');
    $routes->post('updateProfil', 'UserAdmin::updateProfil');
    $routes->post('updateFoto', 'UserAdmin::updateFoto');
});

$routes->group('UserKasir', function($routes){
    $routes->get('/', 'UserKasir::index');
    $routes->post('updateProfil', 'UserKasir::updateProfil');
    $routes->post('updatePassword', 'UserKasir::updatePassword');
    $routes->post('updateFoto', 'UserAdmin::updateFoto');
});

$routes->group('KelolaUser', function($routes){
    $routes->get('/', 'KelolaUser::index');
    $routes->post('tambah', 'KelolaUser::tambah'); 
    $routes->post('ubah/(:num)', 'KelolaUser::ubah/$1');
    $routes->get('nonaktif/(:num)', 'KelolaUser::nonaktif/$1');
    $routes->get('aktif/(:num)', 'KelolaUser::aktif/$1');
    $routes->get('hapus/(:num)', 'KelolaUser::hapus/$1');
});


$routes->group('kasir', function($routes){ //modul Kasir POS (scanner, transaksi, nota, pembayaran).
    $routes->post('tambah', 'KasirController::tambah');
    $routes->post('ubah/(:num)', 'KasirController::ubah/$1');
    $routes->get('hapus/(:num)', 'KasirController::hapus/$1');
    $routes->get('aktif/(:num)', 'KasirController::aktif/$1');
    $routes->get('nonaktif/(:num)', 'KasirController::nonaktif/$1');
});

$routes->group('LaporanAdmin', function($routes){
    $routes->get('/', 'LaporanAdmin::index');
    $routes->get('pdf', 'LaporanAdmin::pdf');
    $routes->get('detail/(:num)', 'LaporanAdmin::detail/$1'); 
});

$routes->group('laporankasir', function($routes){
    $routes->get('/', 'LaporanKasir::index');
    $routes->get('pdf', 'LaporanKasir::pdf');
    $routes->get('detail/(:num)', 'LaporanKasir::detail/$1'); 
});

$routes->group('RiwayatTransaksi', function($routes){
    $routes->get('/', 'RiwayatTransaksi::index');
    $routes->get('riwayattransaksi/detail/(:num)', 'RiwayatTransaksi::detail/$1');
});

$routes->group('detailOrder', function($routes){
    $routes->get('/', 'DetailOrder::index');
    $routes->add('tambah', 'DetailOrder::tambah');
    $routes->add('ubah/(:any)', 'DetailOrder::ubah/$1');
    $routes->get('hapus/(:any)', 'DetailOrder::hapus/$1');
});

$routes->group('detailpembelian', function($routes){
    $routes->get('/', 'DetailPembelian::index');
    $routes->add('tambah', 'DetailPembelian::tambah');
    $routes->add('ubah/(:any)', 'DetailPembelian::ubah/$1');
    $routes->get('hapus/(:any)', 'DetailPembelian::hapus/$1');
});

$routes->group('discount', function($routes){
    $routes->get('discount', 'Discount::index');
    $routes->get('/', 'Discount::index');
    $routes->post('tambah', 'Discount::tambah');
    $routes->post('ubah/(:num)', 'Discount::ubah/$1');
    $routes->post('hapus', 'Discount::hapus');
});

$routes->group('merek', function($routes){
    $routes->get('/', 'Merek::index');
    $routes->add('tambah', 'Merek::tambah');
    $routes->add('ubah/(:any)', 'Merek::ubah/$1');
    $routes->get('hapus/(:any)', 'Merek::hapus/$1');
});

$routes->group('Orders', function($routes){
    $routes->get('/', 'Orders::index');
    $routes->add('tambah', 'Orders::tambah');
    $routes->add('ubah/(:any)', 'Orders::ubah/$1');
    $routes->get('hapus/(:any)', 'Orders::hapus/$1');
});

$routes->group('pembayaran', function($routes){
    $routes->get('/', 'Pembayaran::index');
    $routes->add('tambah', 'Pembayaran::tambah');
    $routes->add('ubah/(:any)', 'Pembayaran::ubah/$1');
    $routes->get('hapus/(:any)', 'Pembayaran::hapus/$1');
});

$routes->group('pembelian', function($routes){
    $routes->get('/', 'Pembelian::index');
    $routes->add('tambah', 'Pembelian::tambah');
    $routes->add('ubah/(:any)', 'Pembelian::ubah/$1');
    $routes->get('hapus/(:any)', 'Pembelian::hapus/$1');
});

$routes->group('produk', function($routes){
    $routes->get('/', 'Produk::index');
    $routes->get('tambah', 'Produk::tambah');
    $routes->post('tambah', 'Produk::tambah');
    $routes->post('ubah/(:num)', 'Produk::ubah/$1'); 
    $routes->get('hapus/(:num)', 'Produk::hapus/$1');
    $routes->get('discount/(:num)', 'Discount::index/$1');
});

$routes->group('profiltoko', function($routes){
    $routes->get('/', 'ProfilToko::index');
    $routes->post('simpan', 'ProfilToko::simpan');
});

$routes->group('supplier', function($routes){
    $routes->get('/', 'Supplier::index');
    $routes->post('tambah', 'Supplier::tambah');
    $routes->post('ubah/(:num)', 'Supplier::ubah/$1');
    $routes->post('hapus', 'Supplier::hapus');
});

$routes->group('user', function($routes){
    $routes->get('/', 'User::index');
    $routes->add('tambah', 'User::tambah');
    $routes->add('ubah/(:any)', 'User::ubah/$1');
    $routes->get('hapus/(:any)', 'User::hapus/$1');
});
