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

