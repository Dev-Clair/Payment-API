<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Doctrine\ORM\Exception\ORMException;
use Monolog\Logger;
use Payment_API\Exception\A_Exception;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Throwable;

final class CustomErrorHandlerMiddleware
{
    private Logger $logger;

    public function __construct(private App $app)
    {
        $this->logger = $this->app->getContainer()->get(Logger::class);
    }

    public function __invoke(
        Request          $request,
        Throwable        $exception,
        bool             $displayErrorDetails,
        bool             $logErrors,
        bool             $logErrorDetails,
        ?LoggerInterface $logger = null
    ) {
        $statusCode = 500;
        if (
            $exception instanceof ORMException
            || $exception instanceof HttpNotFoundException
            || $exception instanceof \PDOException
        ) {
            $this->logger->critical($exception->getMessage());
        } else if ($exception instanceof A_Exception) {
            $this->logger->alert($exception->getMessage());
        }

        $payload = [
            'message' => $exception->getMessage()
        ];

        if ($displayErrorDetails) {
            $payload['details'] = $exception->getMessage();
            $payload['trace'] = $exception->getTrace();
        }

        $response = $this->app->getResponseFactory()->createResponse($statusCode);
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response;
    }
}
