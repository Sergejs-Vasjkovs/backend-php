<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization');

use Dotenv\Dotenv;
use App\Controller\ProductController;
use App\Controller\CategoryController;
use App\Core\Http\Request;
use App\Core\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] ?? '0');

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/api/products', [ProductController::class, 'index']);
    $r->addRoute('GET', '/api/products/{id:\d+}', [ProductController::class, 'show']);

    $r->addRoute('POST', '/api/products', [ProductController::class, 'create']);
    $r->addRoute('PUT', '/api/products/{id:\d+}', [ProductController::class, 'update']);
    $r->addRoute('DELETE', '/api/products/{id:\d+}', [ProductController::class, 'delete']);

    $r->addRoute('GET', '/api/categories', [CategoryController::class, 'index']);
    $r->addRoute('GET', '/api/categories/{id:\d+}', [CategoryController::class, 'show']);
    $r->addRoute('POST', '/api/categories/refresh-cache', [CategoryController::class, 'refreshCache']);
});

$request = Request::getInstance();
$httpMethod = $request->getMethod();
$uri = $request->getUri();

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        $response = Response::error('Not Found', 404);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $response = Response::error('Method Not Allowed', 405);
        break;
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;
        $controller = new $controller();

        $params = [];
        if (isset($vars['id'])) {
            $params[] = (int)$vars['id'];
        }
        $response = $controller->$method(...$params);
        break;
}

if (isset($response)) {
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();
}
