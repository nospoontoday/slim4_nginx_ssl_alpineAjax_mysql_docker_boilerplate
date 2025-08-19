<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;
use Redis;
use RedisException;
use Monolog\Logger;

/**
 * Database connection manager with connection pooling and health checks
 * 
 * @ai-purpose Centralized database connection management for MySQL and Redis
 * @ai-dependencies PDO, Redis, Logger
 * @ai-pattern Singleton pattern with connection pooling
 */
final class DatabaseConnection
{
    private static ?self $instance = null;
    private ?PDO $mysqlConnection = null;
    private ?Redis $redisConnection = null;
    private Logger $logger;
    private array $config;
    private bool $isConnected = false;

    private function __construct(Logger $logger, array $config)
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(Logger $logger, array $config): self
    {
        if (self::$instance === null) {
            self::$instance = new self($logger, $config);
        }
        return self::$instance;
    }

    /**
     * Get MySQL connection with automatic reconnection
     */
    public function getMysqlConnection(): PDO
    {
        if ($this->mysqlConnection === null || !$this->isMysqlConnected()) {
            $this->connectMysql();
        }

        return $this->mysqlConnection;
    }

    /**
     * Get Redis connection with automatic reconnection
     */
    public function getRedisConnection(): Redis
    {
        if ($this->redisConnection === null || !$this->isRedisConnected()) {
            $this->connectRedis();
        }

        return $this->redisConnection;
    }

    /**
     * Establish MySQL connection
     */
    private function connectMysql(): void
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $this->config['mysql']['host'],
                $this->config['mysql']['port'],
                $this->config['mysql']['database']
            );

            $this->mysqlConnection = new PDO(
                $dsn,
                $this->config['mysql']['username'],
                $this->config['mysql']['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true, // Connection pooling
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                ]
            );

            $this->logger->info('MySQL connection established successfully');
            $this->isConnected = true;

        } catch (PDOException $e) {
            $this->logger->error('MySQL connection failed: ' . $e->getMessage());
            throw new DatabaseConnectionException('Failed to connect to MySQL: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Establish Redis connection
     */
    private function connectRedis(): void
    {
        try {
            $this->redisConnection = new Redis();
            
            $connected = $this->redisConnection->connect(
                $this->config['redis']['host'],
                $this->config['redis']['port']
            );

            if (!$connected) {
                throw new RedisException('Failed to connect to Redis server');
            }

            // Set Redis options for better performance
            $this->redisConnection->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
            $this->redisConnection->setOption(Redis::OPT_PREFIX, $this->config['redis']['prefix'] ?? 'app:');
            $this->redisConnection->setOption(Redis::OPT_READ_TIMEOUT, 5);
            $this->redisConnection->setOption(Redis::OPT_TCP_KEEPALIVE, 1);

            $this->logger->info('Redis connection established successfully');

        } catch (RedisException $e) {
            $this->logger->error('Redis connection failed: ' . $e->getMessage());
            throw new DatabaseConnectionException('Failed to connect to Redis: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Check if MySQL connection is alive
     */
    private function isMysqlConnected(): bool
    {
        if ($this->mysqlConnection === null) {
            return false;
        }

        try {
            $this->mysqlConnection->query('SELECT 1');
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Check if Redis connection is alive
     */
    private function isRedisConnected(): bool
    {
        if ($this->redisConnection === null) {
            return false;
        }

        try {
            $this->redisConnection->ping();
            return true;
        } catch (RedisException) {
            return false;
        }
    }

    /**
     * Test database connectivity
     */
    public function testConnections(): array
    {
        $results = [
            'mysql' => false,
            'redis' => false,
            'overall' => false
        ];

        try {
            $mysql = $this->getMysqlConnection();
            $mysql->query('SELECT 1');
            $results['mysql'] = true;
        } catch (Exception $e) {
            $this->logger->error('MySQL connection test failed: ' . $e->getMessage());
        }

        try {
            $redis = $this->getRedisConnection();
            $redis->ping();
            $results['redis'] = true;
        } catch (Exception $e) {
            $this->logger->error('Redis connection test failed: ' . $e->getMessage());
        }

        $results['overall'] = $results['mysql'] && $results['redis'];
        return $results;
    }

    /**
     * Close all connections
     */
    public function closeConnections(): void
    {
        if ($this->mysqlConnection !== null) {
            $this->mysqlConnection = null;
        }

        if ($this->redisConnection !== null) {
            $this->redisConnection->close();
            $this->redisConnection = null;
        }

        $this->isConnected = false;
        $this->logger->info('All database connections closed');
    }

    /**
     * Get connection status
     */
    public function getConnectionStatus(): array
    {
        return [
            'mysql' => $this->isMysqlConnected(),
            'redis' => $this->isRedisConnected(),
            'overall' => $this->isConnected,
            'config' => [
                'mysql_host' => $this->config['mysql']['host'],
                'mysql_database' => $this->config['mysql']['database'],
                'redis_host' => $this->config['redis']['host'],
                'redis_port' => $this->config['redis']['port']
            ]
        ];
    }
}
