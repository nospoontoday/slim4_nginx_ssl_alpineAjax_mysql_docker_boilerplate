<?php

declare(strict_types=1);

namespace App\Database;

use Exception;

/**
 * Custom exception for database connection errors
 * 
 * @ai-purpose Specific exception handling for database connection failures
 * @ai-dependencies Exception
 * @ai-pattern Custom exception with context information
 */
final class DatabaseConnectionException extends Exception
{
    private array $context;

    public function __construct(string $message = '', int $code = 0, ?Exception $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get additional context information about the error
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Get a user-friendly error message
     */
    public function getUserMessage(): string
    {
        return 'Database connection error occurred. Please try again later or contact support.';
    }
}
