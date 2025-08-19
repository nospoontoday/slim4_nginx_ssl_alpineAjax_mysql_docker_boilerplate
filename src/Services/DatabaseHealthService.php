<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\DatabaseConnection;
use App\Database\DatabaseConfig;
use Monolog\Logger;

/**
 * Database health monitoring and diagnostics service
 * 
 * @ai-purpose Monitor database health and provide diagnostic information
 * @ai-dependencies DatabaseConnection, DatabaseConfig, Logger
 * @ai-pattern Service pattern with health monitoring
 */
final class DatabaseHealthService
{
    public function __construct(
        private readonly DatabaseConnection $dbConnection,
        private readonly DatabaseConfig $dbConfig,
        private readonly Logger $logger
    ) {}

    /**
     * Perform comprehensive health check
     */
    public function performHealthCheck(): array
    {
        $startTime = microtime(true);
        
        $health = [
            'timestamp' => date('c'),
            'status' => 'unknown',
            'mysql' => $this->checkMysqlHealth(),
            'redis' => $this->checkRedisHealth(),
            'performance' => [],
            'recommendations' => []
        ];

        // Determine overall status
        if ($health['mysql']['status'] === 'healthy' && $health['redis']['status'] === 'healthy') {
            $health['status'] = 'healthy';
        } elseif ($health['mysql']['status'] === 'degraded' || $health['redis']['status'] === 'degraded') {
            $health['status'] = 'degraded';
        } else {
            $health['status'] = 'unhealthy';
        }

        // Add performance metrics
        $health['performance'] = [
            'response_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
        ];

        // Generate recommendations
        $health['recommendations'] = $this->generateRecommendations($health);

        $this->logger->info('Database health check completed', [
            'status' => $health['status'],
            'response_time' => $health['performance']['response_time_ms']
        ]);

        return $health;
    }

    /**
     * Check MySQL health
     */
    private function checkMysqlHealth(): array
    {
        try {
            $mysql = $this->dbConnection->getMysqlConnection();
            
            // Test basic connectivity
            $startTime = microtime(true);
            $mysql->query('SELECT 1');
            $responseTime = (microtime(true) - $startTime) * 1000;

            // Check server variables
            $stmt = $mysql->query("SHOW VARIABLES LIKE 'max_connections'");
            $maxConnections = $stmt->fetch()['Value'] ?? 'unknown';

            $stmt = $mysql->query("SHOW STATUS LIKE 'Threads_connected'");
            $currentConnections = $stmt->fetch()['Value'] ?? 'unknown';

            $health = [
                'status' => 'healthy',
                'response_time_ms' => round($responseTime, 2),
                'max_connections' => $maxConnections,
                'current_connections' => $currentConnections,
                'connection_usage_percent' => $this->calculateConnectionUsage($maxConnections, $currentConnections),
                'last_check' => date('c')
            ];

            // Determine if degraded
            if ($responseTime > 100) { // More than 100ms response time
                $health['status'] = 'degraded';
                $health['warnings'] = ['High response time detected'];
            }

            return $health;

        } catch (\Exception $e) {
            $this->logger->error('MySQL health check failed: ' . $e->getMessage());
            
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'last_check' => date('c')
            ];
        }
    }

    /**
     * Check Redis health
     */
    private function checkRedisHealth(): array
    {
        try {
            $redis = $this->dbConnection->getRedisConnection();
            
            // Test basic connectivity
            $startTime = microtime(true);
            $redis->ping();
            $responseTime = (microtime(true) - $startTime) * 1000;

            // Get Redis info
            $info = $redis->info();
            
            $health = [
                'status' => 'healthy',
                'response_time_ms' => round($responseTime, 2),
                'version' => $info['redis_version'] ?? 'unknown',
                'uptime_seconds' => $info['uptime_in_seconds'] ?? 'unknown',
                'connected_clients' => $info['connected_clients'] ?? 'unknown',
                'used_memory_human' => $info['used_memory_human'] ?? 'unknown',
                'last_check' => date('c')
            ];

            // Determine if degraded
            if ($responseTime > 50) { // More than 50ms response time
                $health['status'] = 'degraded';
                $health['warnings'] = ['High response time detected'];
            }

            return $health;

        } catch (\Exception $e) {
            $this->logger->error('Redis health check failed: ' . $e->getMessage());
            
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'last_check' => date('c')
            ];
        }
    }

    /**
     * Calculate connection usage percentage
     */
    private function calculateConnectionUsage(string|int $max, string|int $current): float
    {
        if (!is_numeric($max) || !is_numeric($current) || $max == 0) {
            return 0.0;
        }

        return round(($current / $max) * 100, 2);
    }

    /**
     * Generate recommendations based on health status
     */
    private function generateRecommendations(array $health): array
    {
        $recommendations = [];

        // MySQL recommendations
        if (isset($health['mysql']['connection_usage_percent'])) {
            $usage = $health['mysql']['connection_usage_percent'];
            if ($usage > 80) {
                $recommendations[] = 'MySQL connection pool is reaching capacity. Consider increasing max_connections or implementing connection pooling.';
            }
        }

        if (isset($health['mysql']['response_time_ms']) && $health['mysql']['response_time_ms'] > 100) {
            $recommendations[] = 'MySQL response time is high. Check for slow queries or database load.';
        }

        // Redis recommendations
        if (isset($health['redis']['response_time_ms']) && $health['redis']['response_time_ms'] > 50) {
            $recommendations[] = 'Redis response time is high. Check for memory pressure or network issues.';
        }

        // General recommendations
        if ($health['status'] === 'unhealthy') {
            $recommendations[] = 'Database connections are failing. Check network connectivity and service status.';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'All systems are operating normally.';
        }

        return $recommendations;
    }

    /**
     * Get connection statistics
     */
    public function getConnectionStats(): array
    {
        $status = $this->dbConnection->getConnectionStatus();
        
        return [
            'mysql_connected' => $status['mysql'],
            'redis_connected' => $status['redis'],
            'overall_status' => $status['overall'],
            'config_summary' => [
                'mysql_host' => $status['config']['mysql_host'],
                'mysql_database' => $status['config']['mysql_database'],
                'redis_host' => $status['config']['redis_host'],
                'redis_port' => $status['config']['redis_port']
            ]
        ];
    }
}
