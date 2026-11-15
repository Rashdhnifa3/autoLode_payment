<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Request;
use App\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$request = new Request();
$routes  = require __DIR__ . '/../routes.php';
$match = (new Router($routes))->resolve($request->method(), $request->path());

if ($match === null) {
    http_response_code(404);
    echo 'Page not found.';
    exit;
}

[$class, $method, $arguments] = $match;

$controller = new $class($request);
$controller->$method(...$arguments);
