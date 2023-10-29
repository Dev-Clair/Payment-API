<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Psr7\Response as Response;
use Slim\Psr7\Request as Request;
use Payment_API\Controller\CustomersController;
use Payment_API\Controller\MethodsController;
use Payment_API\Controller\PaymentsController;
use Payment_API\Middleware\ContentTypeMiddleware;
use Payment_API\Middleware\MethodTypeMiddleware;
use Payment_API\Middleware\CustomErrorHandlerMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../container/container.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

//Create New App Instance
$app = AppFactory::createFromContainer(container: $container);

// $app->addRoutingMiddleware();

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
        ->withHeader('Content-Type', 'application/json; charset=UTF-8');
});

// Methods Endpoints
$app->group('/v1/methods', function (RouteCollectorProxy $group) {
    $group->get('', [MethodsController::class, 'get'])
        ->add(new MethodTypeMiddleware(['GET', 'POST']));
    $group->post('', [MethodsController::class, 'post'])
        ->add(new MethodTypeMiddleware(['GET', 'POST']))
        ->add(new ContentTypeMiddleware('application/json; charset=UTF-8'));
    $group->put('/{id:[0-9]+}', [MethodsController::class, 'put'])
        ->add(new MethodTypeMiddleware(['PUT', 'DELETE']))
        ->add(new ContentTypeMiddleware('application/json; charset=UTF-8'));
    $group->delete('/{id:[0-9]+}', [MethodsController::class, 'delete'])
        ->add(new MethodTypeMiddleware(['PUT', 'DELETE']));
    $group->get('/deactivate/{id:[0-9]+}', [MethodsController::class, 'deactivate'])
        ->add(new MethodTypeMiddleware(['GET']));
    $group->get('/reactivate/{id:[0-9]+}', [MethodsController::class, 'reactivate'])
        ->add(new MethodTypeMiddleware(['GET']));
});

// Customers Endpoints
$app->group('/v1/customers', function (RouteCollectorProxy $group) {
    $group->get('', '\Payment_API\Controller\CustomersController:get')
        ->add(new MethodTypeMiddleware(['GET', 'POST']));
    $group->post('', '\Payment_API\Controller\CustomersController:post')
        ->add(new MethodTypeMiddleware(['GET', 'POST']))
        ->add(new ContentTypeMiddleware('application/json; charset=UTF-8'));
    $group->put('/{id:[0-9]+}', [CustomersController::class, 'put'])
        ->add(new MethodTypeMiddleware(['PUT', 'DELETE']))
        ->add(new ContentTypeMiddleware('application/json; charset=UTF-8'));
    $group->delete('/{id:[0-9]+}', [CustomersController::class, 'delete'])
        ->add(new MethodTypeMiddleware(['PUT', 'DELETE']));
    $group->get('/deactivate/{id:[0-9]+}', [CustomersController::class, 'deactivate'])
        ->add(new MethodTypeMiddleware(['GET']));
    $group->get('/reactivate/{id:[0-9]+}', [CustomersController::class, 'reactivate'])
        ->add(new MethodTypeMiddleware(['GET']));
});

// Payments Endpoints
$app->group('/v1/payments', function (RouteCollectorProxy $group) {
    $group->get('', [PaymentsController::class, 'get'])
        ->add(new MethodTypeMiddleware(['GET', 'POST']));
    $group->post('', [PaymentsController::class, 'post'])
        ->add(new MethodTypeMiddleware(['GET', 'POST']))
        ->add(new ContentTypeMiddleware('application/json; charset=UTF-8'));
    $group->put('/{id:[0-9]+}', [PaymentsController::class, 'put'])
        ->add(new MethodTypeMiddleware(['PUT', 'DELETE']))
        ->add(new ContentTypeMiddleware('application/json; charset=UTF-8'));
    $group->delete('/{id:[0-9]+}', [PaymentsController::class, 'delete'])
        ->add(new MethodTypeMiddleware(['PUT', 'DELETE']));
});

// Add Error Middleware
// $displayErrors = $_ENV['APP_ENV'] != 'development';

// $displayErrors = true;
// $customErrorHandler = new CustomErrorHandlerMiddleWare($app);

// $errorMiddleware = $app->addErrorMiddleware($displayErrors, true, true);
// $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
