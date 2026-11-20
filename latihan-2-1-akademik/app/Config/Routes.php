<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('/', 'Auth::home', ['filter' => 'academic-access']);
$routes->post('logout', 'Auth::logout', ['filter' => 'academic-access']);

$routes->group('', ['filter' => 'academic-access:mahasiswa'], static function (RouteCollection $routes) {
    $routes->get('frs', 'Academic::frs');
    $routes->post('frs', 'Academic::submit');
    $routes->get('jadwal', 'Academic::schedule');
    $routes->get('nilai', 'Academic::transcript');
});

$routes->group('perwalian', ['filter' => 'academic-access:dosen'], static function (RouteCollection $routes) {
    $routes->get('/', 'Advising::index');
    $routes->get('(:num)', 'Advising::detail/$1');
    $routes->post('(:num)/setujui', 'Advising::approve/$1');
});
