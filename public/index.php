<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Payment_API\Middleware\CustomErrorHandlerMiddleWare;
use Payment_API\Controller\CustomersController;
use Payment_API\Controller\MethodsController;
use Payment_API\Controller\PaymentsController;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../container/container.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

//Create New App Instance
$app = AppFactory::createFromContainer(container: $container);

// Route to API documentation
$app->get('/openapi', function () {
    require __DIR__ . '/openapi/index.php';
});

/**
 * Routes to Application Endpoints and Middlewares for CRUD Related Operations
 */
// API Description and Version Info
$app->get('/v1', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode([
        "title" => "Payment API",
        "description" => "All-in-one payment gateway aggregator",
        "status" => "valid",
        "version" => "1.0"
    ]));
    return $response
        ->withStatus(200, 'OK')
        ->withHeader('Content-Type', 'application/json; charset=UTF+8');
});

// Methods Endpoints
$app->group('/v1/methods', function (RouteCollectorProxy $group) {
    $group->get('', [MethodsController::class, 'get']);
    $group->post('', [MethodsController::class, 'post']);
    $group->put('/{id:[0-9]+}', [MethodsController::class, 'put']);
    $group->delete('/{id:[0-9]+}', [MethodsController::class, 'delete']);
    $group->get('/deactivate/{id:[0-9]+}', [MethodsController::class, 'deactivate']);
    $group->get('/reactivate/{id:[0-9]+}', [MethodsController::class, 'reactivate']);
});

// Customers Endpoints
$app->group('/v1/customers', function (RouteCollectorProxy $group) {
    $group->get('', [CustomersController::class, 'get']);
    $group->post('', [CustomersController::class, 'post']);
    $group->put('/{id:[0-9]+}', [CustomersController::class, 'put']);
    $group->delete('/{id:[0-9]+}', [CustomersController::class, 'delete']);
    $group->get('/deactivate/{id:[0-9]+}', [CustomersController::class, 'deactivate']);
    $group->get('/reactivate/{id:[0-9]+}', [CustomersController::class, 'reactivate']);
});

// Payments Endpoints
$app->group('/v1/payments', function (RouteCollectorProxy $group) {
    $group->get('', [PaymentsController::class, 'get']);
    $group->post('', [PaymentsController::class, 'post']);
    $group->put('/{id:[0-9]+}', [PaymentsController::class, 'put']);
    $group->delete('/{id:[0-9]+}', [PaymentsController::class, 'delete']);
});

// Add Error Middleware
$displayErrors = $_ENV['APP_ENV'] != 'development';

$displayErrors = true;
$customErrorHandler = new CustomErrorHandlerMiddleWare($app);

$errorMiddleware = $app->addErrorMiddleware($displayErrors, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
