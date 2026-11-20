<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');

$routes->group('', ['filter' => 'access'], static function (RouteCollection $routes) {
    $routes->get('/', 'Inventory::index');
    $routes->get('komponen/(:num)/foto', 'Inventory::photo/$1');
    $routes->post('logout', 'Auth::logout');
});

$routes->group('komponen', ['filter' => 'access:admin'], static function (RouteCollection $routes) {
    $routes->get('baru', 'Inventory::form');
    $routes->post('baru', 'Inventory::save');
    $routes->get('(:num)/edit', 'Inventory::form/$1');
    $routes->post('(:num)/edit', 'Inventory::save/$1');
    $routes->post('(:num)/hapus', 'Inventory::delete/$1');
});
