<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Dashboard::index');
$routes->get('dashboard','Dashboard::index');


$routes->get('/', 'welcome_massage::index');


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

$routes->get('discount', 'Discount::index');
$routes->group('discount', function($routes){
    $routes->get('/', 'Discount::index');
    $routes->post('tambah', 'Discount::tambah');
    $routes->post('ubah/(:num)', 'Discount::ubah/$1');
    $routes->get('hapus/(:num)', 'Discount::hapus/$1');

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
});

$routes->group('profiltoko', function($routes){
    $routes->get('/', 'ProfilToko::index');
    $routes->add('tambah', 'ProfilToko::tambah');
    $routes->add('ubah/(:any)', 'ProfilToko::ubah/$1');
    $routes->get('hapus/(:any)', 'ProfilToko::hapus/$1');
});

$routes->group('supplier', function($routes){
    $routes->get('/', 'Supplier::index');
    $routes->match(['get','post'], 'tambah', 'Supplier::tambah');
    $routes->match(['get','post'], 'ubah/(:num)', 'Supplier::ubah/$1');
    $routes->get('hapus/(:num)', 'Supplier::hapus/$1');
});

$routes->group('user', function($routes){
    $routes->get('/', 'User::index');
    $routes->add('tambah', 'User::tambah');
    $routes->add('ubah/(:any)', 'User::ubah/$1');
    $routes->get('hapus/(:any)', 'User::hapus/$1');
});







