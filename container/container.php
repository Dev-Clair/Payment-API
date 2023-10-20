<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use DI\Container;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Payment_API\Interface\RepositoryInterface;
use Payment_API\Repositories\MethodsRepository;
use Payment_API\Repositories\CustomersRepository;
use Payment_API\Repositories\PaymentsRepository;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

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
            // Enables or disables Doctrine metadata caching
            // for either performance or convenience during development.
            'dev_mode' => true,

            // Path where Doctrine will cache the processed metadata
            // when 'dev_mode' is false.
            'cache_dir' => APP_ROOT . '/var/doctrine',

            // List of paths where Doctrine will search for metadata.
            // Metadata can be either YML/XML files or PHP classes annotated
            // with comments or PHP8 attributes.
            'metadata_dirs' => [APP_ROOT . '/src'],

            // The parameters Doctrine needs to connect to your database.
            // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
            // needs a 'path' parameter and doesn't use most of the ones shown in this example).
            // Refer to the Doctrine documentation to see the full list
            // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
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

    // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
    // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
    $cache = $settings['doctrine']['dev_mode'] ?
        DoctrineProvider::wrap(new ArrayAdapter()) :
        DoctrineProvider::wrap(new FilesystemAdapter(directory: $settings['doctrine']['cache_dir']));

    $config = ORMSetup::createAttributeMetadataConfiguration(
        $settings['doctrine']['metadata_dirs'],
        $settings['doctrine']['dev_mode'],
        null,
        null
    );

    return new EntityManager($settings['doctrine']['connection'], $config);
});

$container->set(MethodsRepository::class, function (Container $container) {
    $em = $container->get(EntityManager::class);
    return new MethodsRepository($em);
});

$container->set(CustomersRepository::class, function (Container $container) {
    $em = $container->get(EntityManager::class);
    return new CustomersRepository($em);
});

$container->set(PaymentsRepository::class, function (Container $container) {
    $em = $container->get(EntityManager::class);
    return new PaymentsRepository($em);
});


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
