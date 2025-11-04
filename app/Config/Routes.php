<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'produk::index');
$routes->get('/produk', 'Produk::index');

$routes->get('/', 'welcome_massage::index');
$routes->get('/welcome_massage', 'welcome_massage::index');

$routes->group('merek', function($routes){
    $routes->get('/', 'Merek::index');
    $routes->add('tambah', 'Merek::tambah');
    $routes->add('ubah/(:any)', 'Merek::ubah/$1');
    $routes->get('hapus/(:any)', 'Merek::hapus/$1');
});

$routes->group('supplier', function($routes){
    $routes->get('/', 'Supplier::index');
    $routes->add('tambah', 'Supplier::tambah');
    $routes->add('ubah/(:any)', 'Supplier::ubah/$1');
    $routes->get('hapus/(:any)', 'Supplier::hapus/$1');
});
