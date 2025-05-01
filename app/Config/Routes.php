<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

// Group untuk halaman Page
$routes->group('page', function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('home', 'Home::index');
    $routes->get('home/(:segment)', 'Home::kategori/$1');
    $routes->get('artikel/(:any)', 'Page::artikel/$1');
    $routes->get('about', 'Page::about');
    $routes->get('contact', 'Page::contact');
});

// Group untuk halaman Admin
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Artikel::admin_index');
    $routes->add('add', 'Artikel::add');
    $routes->add('edit/(:num)', 'Artikel::edit/$1');
    $routes->get('delete/(:any)', 'Artikel::delete/$1');
});

// Group untuk halaman User
$routes->group('user', function ($routes) {
    $routes->get('/', 'User::index');
    $routes->match(['get', 'post'], 'login', 'User::login'); // Akses: /user/login (GET buat nampilin form, POST buat proses login)
    $routes->post('logout', 'User::logout');
});
