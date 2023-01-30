<?php

use App\Controller;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;


return static function (RouteCollection $routes) {
    $routes->add(
        'home',
        new Route('/', [
            '_controller' => [Controller\DefaultController::class, 'index'],
        ])
    );
    $routes->add(
        'add_paste',
        new Route('/add', [
            '_controller' => [Controller\DefaultController::class, 'addPaste'],
        ])
    );
    $routes->add(
        'view',
        new Route('/view/{id}', [
            '_controller' => [Controller\ViewController::class, 'view'],
        ])
    );
    $routes->add(
        'raw',
        new Route('/raw/{id}', [
            '_controller' => [Controller\ViewController::class, 'raw'],
        ])
    );
    $routes->add(
        'download',
        new Route('/dl/{id}', [
            '_controller' => [Controller\ViewController::class, 'download'],
        ])
    );
};