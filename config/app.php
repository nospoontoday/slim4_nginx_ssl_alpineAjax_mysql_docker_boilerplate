<?php
/**
 * @file app.php
 * @purpose Application bootstrap and configuration
 * @ai-context Main application configuration file
 */

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\ErrorLogHandler;

return [
    // Database configuration
    App\Database\DatabaseConfig::class => function () {
        return new App\Database\DatabaseConfig();
    },
    
    // Database connection manager
    App\Database\DatabaseConnection::class => function (App\Database\DatabaseConfig $config, Logger $logger) {
        return App\Database\DatabaseConnection::getInstance($logger, $config->getAll());
    },
    
    // Database health service
    App\Services\DatabaseHealthService::class => function (
        App\Database\DatabaseConnection $dbConnection,
        App\Database\DatabaseConfig $dbConfig,
        Logger $logger
    ) {
        return new App\Services\DatabaseHealthService($dbConnection, $dbConfig, $logger);
    },
    
    // Legacy PDO connection (for backward compatibility)
    PDO::class => function (App\Database\DatabaseConnection $dbConnection) {
        return $dbConnection->getMysqlConnection();
    },
    
    // Legacy Redis connection (for backward compatibility)
    Redis::class => function (App\Database\DatabaseConnection $dbConnection) {
        return $dbConnection->getRedisConnection();
    },
    
    // Logger
    Logger::class => function () {
        $logger = new Logger('app');
        
        if ($_ENV['APP_ENV'] === 'production') {
            $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::WARNING));
        } else {
            $logger->pushHandler(new ErrorLogHandler());
        }
        
        return $logger;
    },
    
    // View renderer
    PhpRenderer::class => function () {
        return new PhpRenderer(__DIR__ . '/../templates');
    },
    
    // Settings
    'settings' => function () {
        return [
            'displayErrorDetails' => $_ENV['APP_DEBUG'] === 'true',
            'logError' => true,
            'logErrorDetails' => true,
            'addContentLengthHeader' => false,
            'determineRouteBeforeAppMiddleware' => true,
        ];
    },
];