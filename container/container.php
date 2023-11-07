<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Payment_API\Repositories\MethodsRepository;
use Payment_API\Repositories\CustomersRepository;
use Payment_API\Repositories\PaymentsRepository;
use Payment_API\Controller\MethodsController;
use Payment_API\Controller\CustomersController;
use Payment_API\Controller\PaymentsController;
use Payment_API\Interface\SmsServiceInterface;
use Payment_API\Services\SmsAlertService\TwilioSmsAlertService;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeload();

$container = new Container();

const APP_ROOT = __DIR__ . "/..";

/**
 *  Register bindings: Configuration Settings
 */
$container->set('settings', function () {
    return [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'doctrine' => [
            'dev_mode' => true,
            'metadata_dirs' => [APP_ROOT . '/src/Entity'],
            'connection' => [
                'driver' => $_ENV['DB_DRIVER'],
                'host' => $_ENV['MARIADB_HOST'],
                'port' => 3306,
                'dbname' => $_ENV['MARIADB_DB_NAME'],
                'user' => $_ENV['MARIADB_DB_USER'],
                'password' => $_ENV['MARIADB_DB_USER_PASSWORD']
            ]
        ]

    ];
});

/**
 * Register bindings: ORM EntityManager
 */
$container->set(EntityManager::class, function (Container $container): EntityManager {
    /**
     * @var array $settings
     */
    $settings = $container->get('settings');

    $config = ORMSetup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode']
    );

    $conn = DriverManager::getConnection($settings['doctrine']['connection']);

    return new EntityManager($conn, $config);
});

/**
 * Register bindings: Repositories
 */
$container->set(MethodsRepository::class, function (Container $container): MethodsRepository {
    $entityManager = $container->get(EntityManager::class);
    return new MethodsRepository($entityManager);
});

$container->set(CustomersRepository::class, function (Container $container): CustomersRepository {
    $entityManager = $container->get(EntityManager::class);
    return new CustomersRepository($entityManager);
});

$container->set(PaymentsRepository::class, function (Container $container): PaymentsRepository {
    $entityManager = $container->get(EntityManager::class);
    return new PaymentsRepository($entityManager);
});

/**
 * Register bindings: Controllers
 */
$container->set(MethodsController::class, function (Container $container): MethodsController {
    $methodsRepository = $container->get(MethodsRepository::class);
    $logger = $container->get(Logger::class);

    return new MethodsController($methodsRepository, $logger);
});

$container->set(CustomersController::class, function (Container $container): CustomersController {
    $smsService = $container->get(SmsServiceInterface::class);
    $customersRepository = $container->get(CustomersRepository::class);
    $logger = $container->get(Logger::class);

    return new CustomersController($smsService, $customersRepository, $logger);
});

$container->set(PaymentsController::class, function (Container $container): PaymentsController {
    $paymentsRepository = $container->get(PaymentsRepository::class);
    $logger = $container->get(Logger::class);

    return new PaymentsController($paymentsRepository, $logger);
});

/**
 * Register bindings: Services Interface
 */
$container->set(
    SmsServiceInterface::class,
    function (Container $container): TwilioSmsAlertService {
        return new TwilioSmsAlertService;
    }
);

/**
 * Register bindings: Logger
 */
$container->set(Logger::class, function (Container $container): Logger {
    $logger = new Logger('Payment_API');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/emergency.log', Level::Emergency));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/alert.log', Level::Alert));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/critical.log', Level::Critical));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/error.log', Level::Error));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/warning.log', Level::Warning));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/info.log', Level::Info));
    return $logger;
});

return $container;
