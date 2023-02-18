<?php

use App\Controller;
use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;


return static function (RouteConfigurator $routes) {
    $routes->add('home', '/')
        ->controller([Controller\DefaultController::class, 'index'])
        ->methods(['GET']);
    $routes->add('add_text', '/add/text')
        ->controller([Controller\AddController::class, 'text'])
        ->methods(['POST']);
    $routes->add('view', '/view/{id}')
        ->controller([Controller\ViewController::class, 'view'])
        ->methods(['GET']);
    $routes->add('raw', '/raw/{id}')
        ->controller([Controller\ViewController::class, 'raw'])
        ->methods(['GET']);
    $routes->add('download', '/dl/{id}')
        ->controller([Controller\ViewController::class, 'download'])
        ->methods(['GET']);
};