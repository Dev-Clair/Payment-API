<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Route to API documentation
$app->get('/openapi', function () {
    require __DIR__ . '/openapi/index.php';
});

// Routes to Application Endpoints and Middlewares for CRUD Related Operations


$app->run();
