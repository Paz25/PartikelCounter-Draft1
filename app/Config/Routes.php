<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('partikelcounterbuffer', ['controller' => 'PartikelCounterBufferController']);
$routes->resource('partikelcounterdata', ['controller' => 'PartikelCounterDataController']);

