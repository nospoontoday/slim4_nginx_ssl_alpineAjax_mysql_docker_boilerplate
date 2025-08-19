<?php

declare(strict_types=1);

namespace App\Database;

/**
 * Database configuration management
 * 
 * @ai-purpose Centralized database configuration with validation
 * @ai-dependencies Environment variables
 * @ai-pattern Configuration object with validation
 */
final class DatabaseConfig
{
    private array $config;

    public function __construct()
    {
        $this->config = $this->loadFromEnvironment();
        $this->validate();
    }

    /**
     * Load configuration from environment variables
     */
    private function loadFromEnvironment(): array
    {
        return [
            'mysql' => [
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
                'database' => $_ENV['DB_DATABASE'] ?? 'app',
                'username' => $_ENV['DB_USERNAME'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? 'root',
                'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
                'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
            ],
            'redis' => [
                'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
                'port' => (int) ($_ENV['REDIS_PORT'] ?? 6379),
                'prefix' => $_ENV['REDIS_PREFIX'] ?? 'app:',
                'timeout' => (int) ($_ENV['REDIS_TIMEOUT'] ?? 5),
                'retry_interval' => (int) ($_ENV['REDIS_RETRY_INTERVAL'] ?? 100),
            ],
            'connection' => [
                'max_retries' => (int) ($_ENV['DB_MAX_RETRIES'] ?? 3),
                'retry_delay' => (int) ($_ENV['DB_RETRY_DELAY'] ?? 1000),
                'connection_timeout' => (int) ($_ENV['DB_CONNECTION_TIMEOUT'] ?? 30),
                'query_timeout' => (int) ($_ENV['DB_QUERY_TIMEOUT'] ?? 60),
            ]
        ];
    }

    /**
     * Validate configuration values
     */
    private function validate(): void
    {
        // Validate MySQL configuration
        if (empty($this->config['mysql']['database'])) {
            throw new \InvalidArgumentException('Database name cannot be empty');
        }

        if (empty($this->config['mysql']['username'])) {
            throw new \InvalidArgumentException('Database username cannot be empty');
        }

        if ($this->config['mysql']['port'] < 1 || $this->config['mysql']['port'] > 65535) {
            throw new \InvalidArgumentException('Invalid MySQL port number');
        }

        // Validate Redis configuration
        if ($this->config['redis']['port'] < 1 || $this->config['redis']['port'] > 65535) {
            throw new \InvalidArgumentException('Invalid Redis port number');
        }

        if ($this->config['redis']['timeout'] < 1) {
            throw new \InvalidArgumentException('Redis timeout must be positive');
        }

        // Validate connection settings
        if ($this->config['connection']['max_retries'] < 0) {
            throw new \InvalidArgumentException('Max retries cannot be negative');
        }

        if ($this->config['connection']['retry_delay'] < 0) {
            throw new \InvalidArgumentException('Retry delay cannot be negative');
        }
    }

    /**
     * Get MySQL configuration
     */
    public function getMysqlConfig(): array
    {
        return $this->config['mysql'];
    }

    /**
     * Get Redis configuration
     */
    public function getRedisConfig(): array
    {
        return $this->config['redis'];
    }

    /**
     * Get connection settings
     */
    public function getConnectionConfig(): array
    {
        return $this->config['connection'];
    }

    /**
     * Get all configuration
     */
    public function getAll(): array
    {
        return $this->config;
    }

    /**
     * Check if running in development mode
     */
    public function isDevelopment(): bool
    {
        return ($_ENV['APP_ENV'] ?? 'development') === 'development';
    }

    /**
     * Check if debug mode is enabled
     */
    public function isDebugEnabled(): bool
    {
        return ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
    }
}
