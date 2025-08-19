#!/usr/bin/env php
<?php

/**
 * Database Connection Test Script
 * 
 * This script tests the database connections for both MySQL and Redis
 * Run with: php scripts/test-db-connection.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DatabaseConfig;
use App\Database\DatabaseConnection;
use App\Services\DatabaseHealthService;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Set up basic logging
$logger = new Logger('db-test');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

echo "🔍 Testing Database Connections...\n";
echo "=====================================\n\n";

try {
    // Load configuration
    echo "📋 Loading configuration...\n";
    $config = new DatabaseConfig();
    echo "✅ Configuration loaded successfully\n\n";
    
    // Test database connection
    echo "🔌 Testing database connections...\n";
    $dbConnection = DatabaseConnection::getInstance($logger, $config->getAll());
    
    // Test MySQL
    echo "  MySQL: ";
    try {
        $mysql = $dbConnection->getMysqlConnection();
        $mysql->query('SELECT 1');
        echo "✅ Connected successfully\n";
    } catch (Exception $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "\n";
    }
    
    // Test Redis
    echo "  Redis: ";
    try {
        $redis = $dbConnection->getRedisConnection();
        $redis->ping();
        echo "✅ Connected successfully\n";
    } catch (Exception $e) {
        echo "❌ Connection failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Test health service
    echo "🏥 Testing health service...\n";
    $healthService = new DatabaseHealthService($dbConnection, $config, $logger);
    $health = $healthService->performHealthCheck();
    
    echo "  Overall Status: " . strtoupper($health['status']) . "\n";
    echo "  MySQL Status: " . strtoupper($health['mysql']['status']) . "\n";
    echo "  Redis Status: " . strtoupper($health['redis']['status']) . "\n";
    echo "  Response Time: {$health['performance']['response_time_ms']}ms\n";
    
    if (!empty($health['recommendations'])) {
        echo "\n💡 Recommendations:\n";
        foreach ($health['recommendations'] as $recommendation) {
            echo "  - $recommendation\n";
        }
    }
    
    echo "\n";
    
    // Get connection stats
    echo "📊 Connection Statistics:\n";
    $stats = $healthService->getConnectionStats();
    echo "  MySQL Connected: " . ($stats['mysql_connected'] ? 'Yes' : 'No') . "\n";
    echo "  Redis Connected: " . ($stats['redis_connected'] ? 'Yes' : 'No') . "\n";
    echo "  Overall Status: " . ($stats['overall_status'] ? 'Connected' : 'Disconnected') . "\n";
    
    echo "\n";
    
    // Test configuration
    echo "⚙️  Configuration Summary:\n";
    $allConfig = $config->getAll();
    echo "  MySQL Host: {$allConfig['mysql']['host']}:{$allConfig['mysql']['port']}\n";
    echo "  MySQL Database: {$allConfig['mysql']['database']}\n";
    echo "  Redis Host: {$allConfig['redis']['host']}:{$allConfig['redis']['port']}\n";
    echo "  Environment: " . ($config->isDevelopment() ? 'Development' : 'Production') . "\n";
    echo "  Debug Mode: " . ($config->isDebugEnabled() ? 'Enabled' : 'Disabled') . "\n";
    
    echo "\n✅ Database connection test completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
