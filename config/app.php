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
    // Database connection
    PDO::class => function () {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_DATABASE'] ?? 'app';
        $username = $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? 'root';
        
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        return $pdo;
    },
    
    // Redis connection
    Redis::class => function () {
        $redis = new Redis();
        $host = $_ENV['REDIS_HOST'] ?? 'localhost';
        $port = (int) ($_ENV['REDIS_PORT'] ?? 6379);
        
        $redis->connect($host, $port);
        
        return $redis;
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