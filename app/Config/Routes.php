<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/', 'Home::index');
$routes->get('/', 'Home::index');
$routes->get('/particle', 'Particle::index');
$routes->get('/history', 'History::index');


$routes->resource('partikelcounterbuffer', ['controller' => 'PartikelCounterBufferController']);
$routes->resource('partikelcounterdata', ['controller' => 'PartikelCounterDataController']);

$routes->group('api', function ($routes) {
    $routes->get('iso-limits', 'IsoLimitsController::index');
    $routes->get('iso-limits/(:num)', 'IsoLimitsController::show/$1');
});
