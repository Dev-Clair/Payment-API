<?php

declare(strict_types=1);

namespace Payment_API\Interface;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

interface ControllerInterface
{
    public function get(Request $request, Response $response, array $args): Response;
    public function post(Request $request, Response $response, array $args): Response;
    public function put(Request $request, Response $response, array $args): Response;
    public function delete(Request $request, Response $response, array $args): Response;
}
