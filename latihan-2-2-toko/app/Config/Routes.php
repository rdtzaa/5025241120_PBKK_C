<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Shop::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('daftar', 'Auth::registerForm');
$routes->post('daftar', 'Auth::register');
$routes->post('logout', 'Auth::logout', ['filter' => 'shop-access']);

$routes->group('', ['filter' => 'shop-access:pelanggan'], static function (RouteCollection $routes) {
    $routes->get('keranjang', 'Shop::cart');
    $routes->post('keranjang/(:num)', 'Shop::setQuantity/$1');
    $routes->post('keranjang/(:num)/hapus', 'Shop::remove/$1');
    $routes->post('checkout', 'Shop::checkout');
    $routes->get('pesanan', 'Shop::history');
    $routes->get('pesanan/(:num)', 'Shop::order/$1');
});

$routes->group('admin', ['filter' => 'shop-access:admin'], static function (RouteCollection $routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('produk', 'Admin::products');
    $routes->get('produk/baru', 'Admin::productForm');
    $routes->post('produk/baru', 'Admin::saveProduct');
    $routes->get('produk/(:num)/edit', 'Admin::productForm/$1');
    $routes->post('produk/(:num)/edit', 'Admin::saveProduct/$1');
    $routes->get('pesanan', 'Admin::orders');
    $routes->get('pesanan/(:num)', 'Admin::order/$1');
    $routes->post('pesanan/(:num)/status', 'Admin::changeStatus/$1');
});
