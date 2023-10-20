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
use Payment_API\Interface\RepositoryInterface;
use Payment_API\Repositories\MethodsRepository;
use Payment_API\Repositories\CustomersRepository;
use Payment_API\Repositories\PaymentsRepository;
use Payment_API\Interface\EntityInterface;
use Payment_API\Entity\MethodsEntity;
use Payment_API\Entity\CustomersEntity;
use Payment_API\Entity\PaymentsEntity;
use Payment_API\Interface\SmsServiceInterface;
use Payment_API\Services\SmsService;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$container = new Container;

const APP_ROOT = __DIR__ . "/..";

$container->set('settings', function () {
    return [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'doctrine' => [
            'dev_mode' => true,
            'metadata_dirs' => [APP_ROOT . '/src/Entity'],
            'connection' => [
                'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
                'host' => $_ENV['MARIADB_HOST'] ?? 'localhost',
                'port' => 3306,
                'dbname' => $_ENV['MARIADB_DB_NAME'] ?? 'payments',
                'user' => $_ENV['MARIADB_DB_USER'] ?? 'root',
                'password' => $_ENV['MARIADB_DB_USER_PASSWORD'] ?? ''
            ]
        ]

    ];
});

$container->set(EntityManager::class, function (Container $container): EntityManager {
    /**
     * @var array $settings
     */
    $settings = $container->get('settings');

    $config = ORMSetup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode']
    );

    $conn = DriverManager::getConnection($settings['doctrine']['connection'], $config);

    return new EntityManager($conn, $config);
});

// $container->set(MethodsRepository::class, function (Container $container) {
//     $entityManager = $container->get(EntityManager::class);
//     return new MethodsRepository($entityManager);
// });

// $container->set(CustomersRepository::class, function (Container $container) {
//     $entityManager = $container->get(EntityManager::class);
//     return new CustomersRepository($entityManager);
// });

// $container->set(PaymentsRepository::class, function (Container $container) {
//     $entityManager = $container->get(EntityManager::class);
//     return new PaymentsRepository($entityManager);
// });

$container->set(RepositoryInterface::class, function (Container $container, string $repositoryType) {
    $entityManager = $container->get(EntityManager::class);

    switch ($repositoryType) {
        case 'MethodsRepository':
            return new MethodsRepository($entityManager);
        case 'CustomersRepository':
            return new CustomersRepository($entityManager);
        case 'PaymentsRepository':
            return new PaymentsRepository($entityManager);
        default:
            throw new InvalidArgumentException("Invalid repository type: $repositoryType");
    }
});

$container->set(EntityInterface::class, function (Container $container, string $entityType) {
    switch ($entityType) {
        case 'MethodsEntity':
            return new MethodsEntity;
        case 'CustomersEntity':
            return new CustomersEntity;
        case 'PaymentsEntity':
            return new PaymentsEntity;
        default:
            throw new InvalidArgumentException("Invalid entity type: $entityType");
    }
});

$container->set(
    SmsServiceInterface::class,
    function (Container $container) {
        return new SmsService;
    }
);

$container->set(Logger::class, function (Container $container) {
    $logger = new Logger('Payment_API');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/alert.log', Level::Alert));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/critical.log', Level::Critical));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/error.log', Level::Error));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/warning.log', Level::Warning));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/notice.log', Level::Notice));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/info.log', Level::Info));
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/debug.log', Level::Debug));
    return $logger;
});

return $container;
