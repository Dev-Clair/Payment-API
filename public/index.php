<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../container/container.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

//APP_ROOT
$app = AppFactory::createFromContainer(container: $container);

$app->get('/v1', function (Request $request, Response $response, $args) {
    return "Hello World!";
});

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

// Route to API documentation
$app->get('/openapi', function () {
    require __DIR__ . '/openapi/index.php';
});

// Routes to Application Endpoints and Middlewares for CRUD Related Operations

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$displayErrors = $_ENV['APP_ENV'] != 'development';

$app->run();
