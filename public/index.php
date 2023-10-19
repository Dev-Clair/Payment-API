<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Payment_API\Controller\CustomersController;
use Payment_API\Controller\MethodsController;
use Payment_API\Controller\PaymentsController;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../container/container.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

//Create New App Instance
$app = AppFactory::createFromContainer(container: $container);

// Route to API documentation
$app->get('/openapi', function () {
    require __DIR__ . '/openapi/index.php';
});

// Routes to Application Endpoints and Middlewares for CRUD Related Operations
// API Version Info
$app->get('/v1', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode([
        "info" => "Payment API",
        "status" => "valid",
        "version" => "1.0"
    ]));
    return $response;
});

// Methods Endpoints
$app->group('/v1/methods', function (RouteCollectorProxy $group) {
    $group->get('', '');
    $group->post('', '');
    $group->put('/{id:[0-9]+}', '');
    $group->delete('/{id:[0-9]+}', '');
    $group->get('/deactivate/{id:[0-9]+}', '');
    $group->get('/reactivate/{id:[0-9]+}', '');
});

// Customers Endpoints
$app->group('/v1/customers', function (RouteCollectorProxy $group) {
    $group->get('', '');
    $group->post('', '');
    $group->put('/{id:[0-9]+}', '');
    $group->delete('/{id:[0-9]+}', '');
    $group->get('/deactivate/{id:[0-9]+}', '');
    $group->get('/reactivate/{id:[0-9]+}', '');
});

// Payments (transactions) Endpoints
$app->group('/v1/payments', function (RouteCollectorProxy $group) {
    $group->get('', '');
    $group->post('', '');
    $group->put('/{id:[0-9]+}', '');
    $group->delete('/{id:[0-9]+}', '');
});

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$displayErrors = $_ENV['APP_ENV'] != 'development';

$app->run();
