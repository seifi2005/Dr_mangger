<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/constants.php';

use App\Core\Router;
use App\Core\Request;

env('APP_ENV', 'local');

$routes = require __DIR__ . '/config/routes.php';

$router = new Router($routes);
$request = Request::capture();

$response = $router->dispatch($request);
$response->send();
