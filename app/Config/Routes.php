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

//Route untuk service API COIL ON OFF
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function($routes) {
    $routes->post('coil/on',  'Coil::on');
    $routes->post('coil/off', 'Coil::off');
    $routes->get('coil/status', 'Coil::status');
});
