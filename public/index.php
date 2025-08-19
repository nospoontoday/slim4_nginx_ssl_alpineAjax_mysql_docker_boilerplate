<?php
/**
 * @file index.php
 * @purpose Single entry point for the application
 * @ai-context Main entry point for all requests
 */

declare(strict_types=1);

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\RoutingMiddleware;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set error reporting
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Build container
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require __DIR__ . '/../config/app.php');

$container = $containerBuilder->build();

// Create app
$app = AppFactory::createFromContainer($container);

// Register middleware
$app->addBodyParsingMiddleware(); // Add this to parse JSON request bodies
$app->addRoutingMiddleware();
$app->add(new ErrorMiddleware(
    $app->getCallableResolver(),
    $app->getResponseFactory(),
    $_ENV['APP_DEBUG'] === 'true',
    true,
    true
));

// Register routes
(require __DIR__ . '/../config/routes.php')($app);

// Run app
$app->run();