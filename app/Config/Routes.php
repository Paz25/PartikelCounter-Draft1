<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/', 'Home::index');
$routes->get('/', 'Home::index');
$routes->get('/particle', 'Particle::index');
$routes->get('/history', 'History::index');

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->resource('partikelcounterbuffer', ['controller' => 'PartikelCounterBufferController']);
    $routes->resource('partikelcounterdata', ['controller' => 'PartikelCounterDataController']);
    $routes->get('iso-limits', 'IsoLimitsController::index');
    $routes->get('iso-limits/(:num)', 'IsoLimitsController::show/$1');
    $routes->post('coil/on', 'CoilController::on');
    $routes->post('coil/off', 'CoilController::off');
    $routes->get('coil/status', 'CoilController::status');
});
